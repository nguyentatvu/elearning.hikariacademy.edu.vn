<?php
namespace App\Http\Controllers;
use \App;
use App\Payment;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\LmsCategory;
use App\LmsContent;
use App\LmsSeries;
use App\LmsStudentView;
use App\Role;
use App\Services\LmsContentService;
use App\Services\LmsSeriesComboService;
use App\Services\LmsSeriesService;
use App\Services\LmsStudentViewService;
use App\Services\PaymentMethodService;
use App\Services\UserService;
use Carbon\Carbon;
use mysql_xdevapi\Exception;
use PhpParser\Node\Stmt\If_;
use DB;
use Image;
use ImageSettings;
use File;
use Response;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class StudentLmsController extends Controller
{
    // Content preparation properties
    private $prepContent = [];

    // Services
    private $lmsContentService;
    private $lmsSeriesService;
    private $lmsStudentViewService;
    private $lmsSeriesComboService;
    private $paymentMethodService;

    private $userService;


    public function __construct(
        LmsContentService $lmsContentService,
        LmsSeriesService $lmsSeriesService,
        LmsStudentViewService $lmsStudentViewService,
        LmsSeriesComboService $lmsSeriesComboService,
        PaymentMethodService $paymentMethodService,
        UserService $userService
    ) {
        $this->lmsContentService = $lmsContentService;
        $this->lmsSeriesService = $lmsSeriesService;
        $this->lmsStudentViewService = $lmsStudentViewService;
        $this->lmsSeriesComboService = $lmsSeriesComboService;
        $this->paymentMethodService = $paymentMethodService;
        $this->userService = $userService;
    }

    /**
     * Check validity of URL
     *
     * @param array $params
     * @return mixed
     */
    private function checkValidURL(array &$params)
    {
        $this->prepContent['series_id'] =
            optional($this->lmsSeriesService->getByCondition('slug', $params['slug']))->id;
        $this->prepContent['series_combo'] =
            $this->lmsSeriesComboService->getByCondition('slug', $params['combo_slug']);
        $this->prepContent['series_type'] =
            optional($this->prepContent['series_combo'])->type;
        $this->prepContent['series_combo_id'] =
            optional($this->prepContent['series_combo'])->id;

        // If 'stt' does not exist, there's no need to check it.
        // If 'stt' exists, perform the check.
        if ($params['stt'] === '') {
            $content = $this->lmsContentService->findById((int) $params['stt']);
        } else {
            $content = true;
        }

        if (
            !$this->prepContent['series_id'] ||
            !$this->prepContent['series_combo_id'] ||
            !$content
        ) {
            // return redirect()->to('/');
        }
    }

    /**
     * Check validity of payment,
     * if user (or guest) access the purchase content, redirect first content of the series
     *
     * @param array $params
     * @return mixed
     */
    private function checkValidPayment(array &$params)
    {
        // Both a guest user and a student who hasn't purchased the series have the same trial access.
        // A student who has purchased the series is granted full access to all content in the series.
        $userId = auth()->id() ?? -1;
        $seriesId = $this->prepContent['series_id'];
        $seriesComboId = $this->prepContent['series_combo_id'];

        $this->prepContent['is_valid_payment'] = true;
        // = $this->paymentMethodService->checkSerieValidity($userId, $seriesComboId);

        if (
            $this->prepContent['series_combo']
            && $this->prepContent['series_combo']->cost == 0
        ) {
            $this->prepContent['is_free_series'] = true;
            $this->prepContent['is_valid_payment'] = true;
        } else {
            $this->prepContent['is_free_series'] = false;
        }

        if (($params['stt']) === '') {
            if ($this->prepContent['is_valid_payment']) {
                $lastViewedContent = $this->lmsStudentViewService->getLastViewedContentOfStudent($seriesId);
                $params['stt'] = $lastViewedContent->lmscontent_id ?? $this->getFirstContentId($seriesId);
            } else {
                $params['stt'] = $this->getFirstContentId($seriesId);
            }

            return;
        }

        // $isTrialContent = $this->lmsContentService->checkTrialContent($params['stt']);
        if (!$this->prepContent['is_valid_payment'] && !$isTrialContent) {
            $params['stt'] = $this->getFirstContentId($seriesId);
        }
    }

    /**
     * Get first content id
     *
     * @param string $seriesId
     * @return mixed
     */
    private function getFirstContentId(string $seriesId)
    {
        $content = $this->lmsContentService->getFirstContentOfSeries($seriesId);
        return $content->id ?? null;
    }

    /**
     * Prepare content list
     *
     * @param array $params
     * @return void
     */
    private function prepareContentList(array &$params)
    {
        $this->prepContent['detail_content'] = $this->lmsContentService->findById((int) $params['stt']);
        $this->prepContent['active_content_id_list'] = $this->lmsContentService->getActiveContentIdList($params['stt']);
        $this->prepContent['contents'] = $this->lmsContentService->getListContents($this->prepContent['series_id'])->sortBy('stt');

        $contentView = $this->lmsStudentViewService->getViewsBySeries();
        $this->prepContent['finished_content_ids'] = $contentView->where('finish', LmsStudentView::FINISH)
            ->pluck('lmscontent_id')->toArray();
        $this->prepContent['viewed_content_ids'] = $contentView->where('finish', LmsStudentView::NOT_FINISHED)
            ->pluck('lmscontent_id')->toArray();

        $this->prepContent['contents']->each(function ($item) use ($params) {
            $this->setURLToPurchasedContents($item, $params);
        });
    }

    /**
     * Set URL to owned nested content
     *
     * @param App\LmsContent $lms_content
     * @param array $params
     * @return void
     */
    private function setURLToPurchasedContents(LmsContent &$lms_content, array &$params, int $chapter_index = 0)
    {
        $typeMap = config('constant.series.type_map');
        if (in_array($lms_content->type, $typeMap['title'])) {
            foreach ($lms_content->childContents as $childContent) {
                $this->setURLToPurchasedContents($childContent, $params, $chapter_index);
            }
        }

        // Set css class if series is purchased
        if ($this->prepContent['is_valid_payment']) {
            if (in_array($lms_content->id, $this->prepContent['viewed_content_ids'])) {
                $lms_content->checkbox_icon = 'empty-box.svg';
            } elseif (in_array($lms_content->id, $this->prepContent['finished_content_ids'])) {
                $lms_content->checkbox_icon = 'checked-box.png';
            } else {
                $lms_content->checkbox_icon = 'empty-box.svg';
            }
        }
        if (in_array($lms_content->id, $this->prepContent['active_content_id_list'])) {
            $lms_content->css_class = 'active-content';
        }

        // Set route
        $routes = config('constant.series.routes');
        $params['stt'] = $lms_content->id;
        foreach ($routes as $type => $route) {
            if (in_array($lms_content->type, $typeMap[$type])) {
                $lms_content->url = route($route, $params);
                break;
            }
        }

        if ($chapter_index > 0 && !$this->prepContent['is_valid_payment']) {
            return;
        }
    }

    /**
     * Save student view
     */
    private function saveStudentView(string $contentId)
    {
        if (!$this->prepContent['is_valid_payment']) {
            return;
        }

        $studentView = $this->lmsStudentViewService
            ->getByConditions([
                'lmscontent_id' => $contentId,
                'users_id' => Auth::id(),
            ]);
        if (!$studentView) {
            $this->lmsStudentViewService->insert([
                'lmscontent_id' => $contentId,
                'users_id' => Auth::id(),
                'finish' => LmsStudentView::NOT_FINISHED,
                'created_date' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    /**
     * Prepare contents and view
     *
     * @param string $combo_slug
     * @param string $slug
     * @param string $stt
     * @return void
     */
    private function processLessonContent(string $combo_slug, string $slug, string $stt)
    {
        $params = compact('combo_slug', 'slug', 'stt');

        $this->checkValidURL($params);
        $this->checkValidPayment($params);
        $this->saveStudentView($stt);
        $this->prepareContentList($params);
    }

    /**
     * Get prepared content variables that will be used in view
     *
     * @return array
     */
    private function getPreparedContentVariables()
    {
        return [
            'contents' => $this->prepContent['contents'],
            'isValidPayment' => $this->prepContent['is_valid_payment'],
            'isFreeSeries' => $this->prepContent['is_free_series'],
            'seriesType' => $this->prepContent['series_type'],
            'detailContent' => $this->prepContent['detail_content'],
            'seriesCombo' => $this->prepContent['series_combo']
        ];
    }

    /**
     * Show lesson
     *
     * @return \Illuminate\Http\Response
     */
    public function showLesson(string $combo_slug = '', string $slug = '', string $stt = '')
    {
        $this->processLessonContent($combo_slug, $slug, $stt);

        return view('client.lesson-detail.video', array_merge(
            $this->getPreparedContentVariables(),
            ['type' => 'lesson', 'video_url' => $this->prepContent['detail_content']->file_path]
        ));
    }

    /**
     * Show exercise
     *
     * @return \Illuminate\Http\Response
     */
    public function showExercise(string $combo_slug = '', string $slug = '', string $stt = '')
    {
        $this->processLessonContent($combo_slug, $slug, $stt);
        $exercises = $this->lmsContentService->getFormattedExerciseContent($stt);

        return view('client.lesson-detail.exercise', array_merge(
            $this->getPreparedContentVariables(),
            [
                'type' => 'exercise',
                'count_records' => count($exercises),
                'records' => $exercises,
                'slug' => $stt,
                'series' => $slug,
                'combo_slug' => $combo_slug
            ]
        ));
    }

    /**
     * Show audit
     *
     * @return \Illuminate\Http\Response
     */
    public function showAudit(string $combo_slug = '', string $slug = '', string $stt = '')
    {
        $this->processLessonContent($combo_slug, $slug, $stt);

        return view('client.lesson-detail.audit', array_merge(
            $this->getPreparedContentVariables(),
            ['type' => 'audit']
        ));
    }

    /**
     * Show flashcard
     *
     * @return \Illuminate\Http\Response
     */
    public function showFlashcard(string $combo_slug = '', string $slug = '', string $stt = '')
    {
        $this->processLessonContent($combo_slug, $slug, $stt);

        return view('client.lesson-detail.flashcard', array_merge(
            $this->getPreparedContentVariables(),
            ['type' => 'flashcard']
        ));
    }

    /**
     * Get next lesson
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getNextLesson(Request $request)
    {
        $series_combo_slug = $request->get('series_combo_slug');
        $series_slug = $request->get('series_slug');
        $content_id = $request->get('content_id');

        $nextContent = $this->lmsContentService->getNextContent($content_id, $series_slug);
        if (!$nextContent) {
            return response()->json([
                'success' => false,
                'url' => null
            ]);
        }

        $typeMap = config('constant.series.type_map');
        $routes = config('constant.series.routes');
        $params = [
            'combo_slug' => $series_combo_slug,
            'slug' => $series_slug,
            'stt' => $nextContent->id
        ];

        foreach ($routes as $type => $route) {
            if (in_array($nextContent->type, $typeMap[$type])) {
                return response()->json([
                    'success' => true,
                    'url' => route($route, $params)
                ]);
            }
        }
    }

    /**
     * Save exercise score
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function saveExerciseScore(Request $request)
    {
        $contentId = $request->get('content_id');
        $earnedPoint = $request->get('earned_point');

        $studentView = $this->lmsStudentViewService->getByConditions([
            'lmscontent_id' => $contentId,
            'users_id' => Auth::id(),
        ]);

        if ($earnedPoint < 1 || !$studentView) {
            return;
        }

        $studentView->update([
            'finish' => LmsStudentView::FINISH
        ], [
            'lmscontent_id' => $contentId,
            'users_id' => Auth::id(),
        ]);
    }
    public function studentAudittest(Request $request, $combo_slug = '', $slug = '', $stt = '')
    {
        $this->processLessonContent($combo_slug, $slug, $stt);

        if (Auth::check()) {

            // Retrieve test records from the database
            $records = DB::table('lmscontents')
                ->join('lms_test', 'lms_test.content_id', '=', 'lmscontents.id')
                ->where('lmscontents.id', $stt)
                ->where('lmscontents.delete_status', 0)
                ->where('lms_test.delete_status', 0)
                ->whereNotNull('lms_test.dang')
                ->select('lms_test.id', 'dang', 'cau', 'mota', 'dapan', 'display', DB::raw("CONCAT_WS('-,-',luachon1,luachon2,luachon3,luachon4) AS answers"))
                ->orderBy('lms_test.id', 'asc')
                ->get();

            if (!$records->isEmpty()) {
                $this->updateAndInsertContentView($slug, $stt);

                // Process the records
                foreach ($records as $record) {
                    $record->mota = change_furigana(trim($record->mota), 'return');
                    if ($record->dang == '7') {
                        $record->mota = str_replace("\n", "\n\n", $record->mota);
                    }
                    $record->answers = array_map(function ($answer) {
                        return change_furigana($answer, 'return');
                    }, explode('-,-', trim($record->answers)));
                }

                // Initialize variables for score calculation
                $totalQuestions = count($records);
                $correctCount = 0;
                $studentAnswers = [];

                // Check if the student has submitted answers
                if ($request->isMethod('post')) {
                    $dataQuest = $request->except(['_token', 'content_id', 'time']);

                    $studentAnswers = $dataQuest; // Student's answers

                    // Compare student's answers with correct answers
                    foreach ($records as $record) {
                        $questionId = $record->id;
                        $correctAnswer = $record->dapan;
                        $studentAnswer = isset($studentAnswers['quest_' . $questionId]) ? $studentAnswers['quest_' . $questionId] : null;

                        if ($studentAnswer == $correctAnswer) {
                            $correctCount++;
                            $record->correct = 1; // Indicate the answer is correct
                        } else {
                            $record->correct = 0; // Indicate the answer is incorrect
                        }

                        $record->check = $studentAnswer; // Store the student's answer
                    }

                    // Calculate the score
                    $point = ($correctCount / $totalQuestions) * 100;

                    // Pass the score to the view
                    $data['point'] = $point;
                    $data['value'] = $correctCount;
                    $data['passed'] = ($point >= 50) ? 1 : 0; // Assume passing score is 50%

                    // Update the student's view record to indicate test is completed
                    $this->updateContentViewFinishStatus($stt, Auth::id());
                }

                $data['student_answers'] = $studentAnswers;
            }

            // Define the back URL
            $back_url = url()->previous();

            // Set view parameters
            $data['class'] = 'audit';
            $data['title'] = 'Khóa học';
            $data['stt'] = $stt;
            $data['slug'] = $slug;
            $data['combo_slug'] = $combo_slug;
            $data['records'] = $records;
            $data['layout'] = getLayout();
            $data['back_url'] = $back_url;
            $view_name = 'client.lesson-detail.audit';

            return view($view_name, array_merge(
                $this->getPreparedContentVariables(),
                $data
            ));
        }

        return redirect('home');
    }

    private function updateAndInsertContentView($slug, $stt)
    {
        $current_content_view = LmsStudentView::query()
            ->join('lmscontents', 'lmscontents.id', '=', 'lms_student_view.lmscontent_id')
            ->join('lmsseries', 'lmsseries.id', '=', 'lmscontents.lmsseries_id')
            ->where([
                ['users_id', Auth::id()],
                ['lmsseries.slug', $slug],
                ['lmscontents.delete_status', 0],
                ['lmscontents.id', $stt],
            ])->get();
        $current_lms_content = LmsContent::where([
            'id' => $stt,
            'delete_status' => 0,
        ])->first();

        // Make sure current content view is empty and its content exists
        if ($current_content_view->isEmpty() && $current_lms_content != null) {
            LmsStudentView::insert([
                'lmscontent_id' => $stt,
                'users_id' => Auth::id(),
                'view_time' => 0,
                'finish' => 0,
                'type' => $current_lms_content->type,
            ]);
        }
    }

    public function storeResuttest(Request $request, $combo_slug = '', $slug = '', $stt = '')
    {
        $this->processLessonContent($combo_slug, $slug, $stt);

        if ($request->isMethod('post')) {
            try {
                $content_id = $stt;
                $time = $request->time;
                $dataQuest = $request->all();
                unset($dataQuest['_token']);
                unset($dataQuest['content_id']);
                unset($dataQuest['time']);

                $records = DB::table('lmscontents')
                    ->join('lms_test', 'lms_test.content_id', '=', 'lmscontents.id')
                    ->where('lmscontents.id', $stt)
                    ->where('lmscontents.delete_status', 0)
                    ->where('lms_test.delete_status', 0)
                    ->select('lms_test.id', 'dang', 'cau', 'mota', 'dapan', 'diem', 'display', DB::raw("CONCAT_WS(',', luachon1, luachon2, luachon3, luachon4) AS answers"))
                    ->orderBy('lms_test.id')
                    ->get();
                $totalValue = 0;
                $point = 0;

                foreach ($records as $keyRecord => $valueRecord) {
                    $point += $valueRecord->diem;
                    $correct = 0;
                    $check = 999;

                    foreach ($dataQuest as $key => $value) {
                        $idKey = filter_var(str_replace('quest_', '', $key), FILTER_SANITIZE_NUMBER_INT);
                        if ($valueRecord->id == $idKey) {
                            if ($valueRecord->dapan == $value) {
                                $totalValue += (int) $valueRecord->diem;
                                $correct = 1;
                            }
                            $check = $value;
                            unset($dataQuest[$key]);
                            break;
                        }
                    }
                    $records[$keyRecord]->correct = $correct;
                    $records[$keyRecord]->check = $check;
                }

                foreach ($records as $key => $value) {
                    $records[$key]->mota = change_furigana(trim($value->mota), 'return');
                    $records[$key]->answers = explode(',', trim($value->answers));
                }

                foreach ($records as $key => $record) {
                    $valueAnswers = array();
                    foreach ($record->answers as $answer) {
                        $valueAnswers[] = change_furigana($answer, 'return');
                    }
                    $records[$key]->answers = $valueAnswers;
                }

                $passed = (int) $totalValue / (int) $point;
                $sendUrl = null;

                if ($passed >= 0.01) {
                    if (Auth::user() != null) {
                        $rewardPoint = 1;
                        if ($passed >= 1) {
                            $rewardPoint = 3;
                        } elseif ($passed > 0.8) {
                            $rewardPoint = 2;
                        }
                        try {
                            DB::beginTransaction();
                            DB::table('lms_test_result')->insert([
                                'lmscontent_id' => $content_id,
                                'combo_slug' => $combo_slug,
                                'finish' => 1,
                                'total_point' => $point,
                                'users_id' => Auth::id(),
                                'point' => $totalValue,
                                'time_result' => $time,
                                'created_by' => Auth::id(),
                            ]);
                            $this->userService->updatePoint($rewardPoint, $content_id, Auth::id());

                            DB::table('lms_student_view')
                                ->where('users_id', Auth::id())
                                ->where('lmscontent_id', $content_id)
                                ->update(['finish' => 1]);

                            DB::commit();
                        } catch (Exception $e) {
                            DB::rollBack();
                        }
                    }

                    $record = DB::table('lmscontents')
                        ->where('id', (int) $stt)
                        ->select('stt', 'lmsseries_id')
                        ->get();

                    if (!$record->isEmpty()) {
                        $recordurl = DB::table('lmscontents')
                            ->where('stt', '>=', ((int) $record[0]->stt + 1))
                            ->where('lmsseries_id', $record[0]->lmsseries_id)
                            ->whereNotIn('type', [0, 8])
                            ->select('id', 'type')
                            ->first();

                        if (!empty($recordurl)) {
                            switch ($recordurl->type) {
                                case 1:
                                case 2:
                                case 6:
                                    $sendUrl = PREFIX . 'learning-management/lesson/show/' . $combo_slug . '/' . $slug . '/' . $recordurl->id;
                                    break;
                                case 3:
                                case 4:
                                    $sendUrl = PREFIX . 'learning-management/lesson/exercise/' . $combo_slug . '/' . $slug . '/' . $recordurl->id;
                                    break;
                                case 5:
                                    $sendUrl = PREFIX . 'learning-management/lesson/audit/' . $combo_slug . '/' . $slug . '/' . $recordurl->id;
                                    break;
                                default:
                                    $sendUrl = null;
                                    break;
                            }
                        }
                    }
                }

                if (Auth::check() && Auth::user()->role_id != 6) {
                    $check = DB::table('lms_student_view')
                        ->select('id')
                        ->where('lmscontent_id', $stt)
                        ->where('users_id', Auth::id())
                        ->get();

                    if ($check->isEmpty()) {
                        return redirect('home');
                    }
                    $data['hi_combo'] = DB::table('lmsseries_combo')
                        ->where('slug', $combo_slug)
                        ->where('delete_status', 0)
                        ->first();

                    $data['checkpay'] = DB::table('payment_method')
                        ->select(DB::raw("(SELECT COUNT(id) FROM payment_method WHERE payment_method.item_id = " . $data['hi_combo']->id . " AND payment_method.user_id = " . Auth::id() . " AND DATE_ADD(responseTime, INTERVAL IF(" . $data['hi_combo']->time . " = 0,90,IF(" . $data['hi_combo']->time . " = 1,180,365)) DAY) > NOW()) as payment"))
                        ->first();
                    if ($data['checkpay']->payment == 0) {
                        return redirect('home');
                    }
                }

                if (Auth::check()) {
                    $data['total_course'] = DB::table('lmsseries')
                        ->join('lmscontents', 'lmsseries.id', '=', 'lmscontents.lmsseries_id')
                        ->where([
                            ['lmsseries.delete_status', 0],
                            ['lmsseries.slug', $slug],
                            ['lmscontents.delete_status', 0],
                        ])
                        ->whereNotIn('lmscontents.type', [0, 8])
                        ->distinct()
                        ->count();

                    $data['current_course'] = DB::table('lms_student_view')
                        ->join('lmscontents', 'lmscontents.id', '=', 'lms_student_view.lmscontent_id')
                        ->join('lmsseries', 'lmsseries.id', '=', 'lmscontents.lmsseries_id')
                        ->where([
                            ['users_id', Auth::id()],
                            ['lmsseries.slug', $slug],
                            ['lms_student_view.finish', 1],
                            ['lmscontents.delete_status', 0],
                            ['lmsseries.delete_status', 0],
                        ])
                        ->whereNotIn('lmscontents.type', [0, 8])
                        ->distinct('lms_student_view.lmscontent_id')
                        ->count();
                }

                $data['class'] = 'exams';
                $data['title'] = 'Khóa học';
                $data['slug'] = $slug;
                $data['stt'] = $stt;
                $data['records'] = $records;
                $data['totalValue'] = $totalValue;
                $data['point'] = $point;
                $data['combo_slug'] = $combo_slug;
                $data['back_url'] = $sendUrl;
                $data['sendUrl'] = $sendUrl;
                $data['passed'] = $passed;
                $data['active_class'] = 'audit';
                $data['layout'] = getLayout();

                $view_name = 'client.lesson-detail.audit';

                return view($view_name, array_merge(
                    $this->getPreparedContentVariables(),
                    $data
                ));
            } catch (Exception $e) {
                return redirect(PREFIX . 'learning-management/lesson/audit/' . $combo_slug . '/' . $slug . '/' . $stt);
            }
        }
    }

}
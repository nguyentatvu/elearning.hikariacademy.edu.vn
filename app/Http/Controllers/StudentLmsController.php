<?php

namespace App\Http\Controllers;

use \App;
use App\Exceptions\RedirectException;
use App\Payment;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\LmsCategory;
use App\LmsContent;
use App\LmsSeries;
use App\LmsStudentView;
use App\Role;
use App\Services\CommentService;
use App\Services\LmsContentService;
use App\Services\LmsSeriesComboService;
use App\Services\LmsSeriesService;
use App\Services\LmsStudentViewService;
use App\Services\PaymentMethodService;
use App\Services\UserRoadmapService;
use App\Services\UserService;
use Carbon\Carbon;
use Exception;
use PhpParser\Node\Stmt\If_;
use Image;
use ImageSettings;
use File;
use Response;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    private $userRoadmapService;
    private $commentService;

    public function __construct(
        LmsContentService $lmsContentService,
        LmsSeriesService $lmsSeriesService,
        LmsStudentViewService $lmsStudentViewService,
        LmsSeriesComboService $lmsSeriesComboService,
        PaymentMethodService $paymentMethodService,
        UserService $userService,
        UserRoadmapService $userRoadmapService,
        CommentService $commentService
    ) {
        $this->lmsContentService = $lmsContentService;
        $this->lmsSeriesService = $lmsSeriesService;
        $this->lmsStudentViewService = $lmsStudentViewService;
        $this->lmsSeriesComboService = $lmsSeriesComboService;
        $this->paymentMethodService = $paymentMethodService;
        $this->userService = $userService;
        $this->userRoadmapService = $userRoadmapService;
        $this->commentService = $commentService;
    }

    /**
     * Check chosen roadmap
     *
     * @return boolean
     */
    private function checkRoadmapChosen()
    {
        $chosenRoadmapList = $this->userRoadmapService->userChosenRoadmapList(Auth::id() ?? -1);
        $series = $this->prepContent['series'];

        if (
            isset($chosenRoadmapList[$series->id]) &&
            $chosenRoadmapList[$series->id] == false &&
            $this->prepContent['is_valid_payment'] &&
            !$this->prepContent['is_free_series']
        ) {
            flash('Thông báo', 'Bạn phải chọn lộ trình trước khi học!', 'error');
            throw new RedirectException(redirect()->to('/'));
        }
    }

    /**
     * Tranfer flash message
     *
     * @param array $params
     * @return void
     */
    private function transferFlashMessage($params)
    {
        if (session()->has('flash_message') && $params['stt'] === '') {
            $message = session()->get('flash_message');

            flash($message['title'], $message['text'], $message['type']);
        }
    }

    /**
     * Check validity of URL
     *
     * @param array $params
     * @return mixed
     */
    private function checkValidURL(array &$params)
    {
        $this->prepContent['series'] =
            $this->lmsSeriesService->getByCondition('slug', $params['slug']);
        $this->prepContent['series_id'] =
            optional($this->prepContent['series'])->id;
        $this->prepContent['series_combo'] =
            $this->lmsSeriesComboService->getByCondition('slug', $params['combo_slug']);

        $this->prepContent['series_type'] =
            optional($this->prepContent['series_combo'])->type;
        $this->prepContent['series_combo_id'] =
            optional($this->prepContent['series_combo'])->id;

        // If 'stt' does not exist, there's no need to check it.
        // If 'stt' exists, perform the check.
        if ($params['stt'] === '') {
            $content = true;
        } else {
            $content = $this->lmsContentService->findById((int) $params['stt']);
        }

        if (
            !$this->prepContent['series_id'] ||
            !$this->prepContent['series_combo_id'] ||
            !$content
        ) {
            throw new RedirectException(redirect()->to('/'));
        }

        // Set previous url
        if ($this->prepContent['series_combo']->checkMultipleCombo) {
            $this->prepContent['prev_url'] = route('series.introduction-detail-combo', [
                'combo_slug' => $this->prepContent['series_combo']->slug
            ]);
        } else {
            $this->prepContent['prev_url'] = route('series.introduction-detail', [
                'combo_slug' => $this->prepContent['series_combo']->slug,
                'slug' => $this->prepContent['series']->slug
            ]);
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

        $this->prepContent['is_valid_payment']
            = $this->paymentMethodService->checkSerieValidity($userId, $seriesComboId);

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
                $lastFinishedContent = $this->lmsStudentViewService->getLastFinishedContentOfStudent($seriesId);
                $params['stt'] = $lastFinishedContent->lmscontent_id ?? $this->getFirstContentId($seriesId);
                $this->redirectToContent($params);
            } else {
                $this->redirectToFirstTrialContent($seriesId, $params);
            }

            return;
        }

        $isTrialContent = $this->lmsContentService->checkTrialContent($params['stt']);
        if (!$this->prepContent['is_valid_payment'] && !$isTrialContent) {
            $this->redirectToFirstTrialContent($seriesId, $params);
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
     * Redirect to first trial content
     *
     * @param string $seriesId
     * @param array $params
     * @return Illuminate\Http\RedirectResponse
     */
    private function redirectToFirstTrialContent(string $seriesId, array $params)
    {
        $content = $this->lmsContentService->getFirstTrialContentOfSeries($seriesId);
        $typeMap = config('constant.series.type_map');
        $routeMap = config('constant.series.routes');

        if (isset($content)) {
            foreach ($typeMap as $key => $value) {
                if (in_array($content->type, $value)) {
                    $type = $key;
                    break;
                }
            }

            if (isset($routeMap[$type])) {
                $params['stt'] = $content->id;
                throw new RedirectException(redirect()->route($routeMap[$type], $params));
            }
        }

        throw new RedirectException(redirect()->to('/'));
    }

    /**
     * Redirect to the right content
     *
     * @param array $params
     * @return Illuminate\Http\RedirectResponse
     */
    private function redirectToContent(array &$params)
    {
        $content = $this->lmsContentService->findById((int) $params['stt']);
        $typeMap = config('constant.series.type_map');
        $routeMap = config('constant.series.routes');

        if (isset($content)) {
            foreach ($typeMap as $key => $value) {
                if (in_array($content->type, $value)) {
                    $type = $key;
                    break;
                }
            }

            if (isset($routeMap[$type])) {
                $params['stt'] = $content->id;
                throw new RedirectException(redirect()->route($routeMap[$type], $params));
            }
        }

        throw new RedirectException(redirect()->to('/'));
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

        $this->prepContent['test_content'] = $this->lmsContentService->getAllByConditions([
            'lmsseries_id' => $this->prepContent['series_id'],
            'type' => LmsContent::TEST,
            'delete_status' => 0
        ])->toArray();

        $this->prepContent['test_content_result'] = $this->getPassedTestInfo(
            $this->prepContent['test_content'],
            Auth::id() ?? "-1"
        );

        $this->prepContent['contents']->each(function ($item) use ($params) {
            $this->setRelatedInfoToDropdownContents($item, $params);
        });
    }

    /**
     * Set URL to owned nested content
     *
     * @param App\LmsContent $lms_content
     * @param array $params
     * @param int $chapter_index
     * @return void
     */
    private function setRelatedInfoToDropdownContents(LmsContent &$lms_content, array &$params, int $chapter_index = 0)
    {
        $typeMap = config('constant.series.type_map');
        if (in_array($lms_content->type, $typeMap['title'])) {
            foreach ($lms_content->childContents as $childContent) {
                $this->setRelatedInfoToDropdownContents($childContent, $params, $chapter_index);
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
        } else {
            $lms_content->checkbox_icon = 'empty-box.svg';
        }
        if (in_array($lms_content->id, $this->prepContent['active_content_id_list'])) {
            $lms_content->css_class = 'active-content';
        }

        // Check blocked content
        $blockedContentInfo = $lms_content->checkBlockedContent($this->prepContent['test_content_result']);
        if ($blockedContentInfo['isContentBlocked']) {
            $lms_content->contentLink = 'javascript:void(0);';
            $lms_content->clickEvent = "showBLockedContentAlert('" . $blockedContentInfo['incompleteTestTitle'] . "')";
        } else {
            // Set route
            $routes = config('constant.series.routes');
            $params['stt'] = $lms_content->id;
            foreach ($routes as $type => $route) {
                if (in_array($lms_content->type, $typeMap[$type])) {
                    $lms_content->url = route($route, $params);
                    break;
                }
            }
            $lms_content->contentLink = $lms_content->url;
            $lms_content->clickEvent = '';
        }

        if ($chapter_index > 0 && !$this->prepContent['is_valid_payment']) {
            return;
        }
    }

    /**
     * Get passed test info
     *
     * @param array $testContents
     * @param string $userId
     * @return mixed
     */
    private function getPassedTestInfo(array $testContents, string $userId)
    {
        $testContentIds = array_column($testContents, 'id');
        $passedTestList = DB::table('lms_test_result')
            ->select('lmscontent_id')
            ->whereIn('lmscontent_id', $testContentIds)
            ->where('users_id', $userId)
            ->whereRaw('point >= 0.5 * total_point')
            ->groupBy('lmscontent_id')
            ->pluck('lmscontent_id')
            ->toArray();

        $result = [];
        foreach ($testContents as $testContent) {
            $result[$testContent['stt']]['is_passed'] = in_array($testContent['id'], $passedTestList);
            $result[$testContent['stt']]['title'] = $testContent['bai'];
        }

        return $result;
    }

    /**
     * Save student view
     */
    private function saveStudentView(string $contentId)
    {
        $this->prepContent['is_finished_content'] = false;
        if (!$this->prepContent['is_valid_payment']) {
            return;
        }

        $studentView = $this->lmsStudentViewService
            ->getByConditions([
                'lmscontent_id' => $contentId,
                'users_id' => Auth::id(),
            ]);

        if (!$studentView && Auth::check()) {
            $this->lmsStudentViewService->insert([
                'lmscontent_id' => $contentId,
                'users_id' => Auth::id(),
                'finish' => LmsStudentView::NOT_FINISHED,
                'created_date' => date('Y-m-d H:i:s'),
            ]);

            $this->userService->updateSeriesViewsHistory(
                Auth::user()->series_views_history ?? [],
                $this->prepContent['series_id']
            );
        }

        $this->prepContent['is_finished_content'] = optional($studentView)->finish == LmsStudentView::FINISH;
    }

    /**
     * Get student view
     *
     * @return void
     */
    private function getStudentView()
    {
        if (!Auth::check()) {
            $this->prepContent['content_view_count'] = 0;
        } else {
            $this->prepContent['content_view_count'] =
                $this->lmsStudentViewService->getViewCountOfSeries(
                    $this->prepContent['series_id'],
                    Auth::id()
                );
        }

        $this->prepContent['series_content_count'] =
            $this->lmsContentService->getContentCountBySeries(
                $this->prepContent['series_id']
            );
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

        $this->transferFlashMessage($params);
        $this->checkValidURL($params);
        $this->checkValidPayment($params);
        $this->checkRoadmapChosen($params);
        $this->saveStudentView($stt);
        $this->getStudentView();
        $this->prepareContentList($params);
        $this->getCommentInCourseOfUser($combo_slug, $slug, $stt);
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
            'seriesCombo' => $this->prepContent['series_combo'],
            'isFinishedContent' => $this->prepContent['is_finished_content'],
            'series' => $this->prepContent['series'],
            'contentViewCount' => $this->prepContent['content_view_count'],
            'seriesContentCount' => $this->prepContent['series_content_count'],
            'comments' => $this->prepContent['comments'],
            'description' => $this->prepContent['detail_content']->description,
            'prevUrl' => $this->prepContent['prev_url'],
            'testContentResult' => $this->prepContent['test_content_result']
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
                'combo_slug' => $combo_slug,
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

        $detail = $this->getPreparedContentVariables();

        return view('client.lesson-detail.audit', array_merge(
            $this->getPreparedContentVariables(),
            [
                'type' => 'audit',
            ]
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
        $preparedContent = $this->getPreparedContentVariables();
        $flashcardId = $preparedContent['detailContent']->flashcard_id;
        $flashcard = $this->lmsContentService->getFlashcardContent($flashcardId);
        return view('client.lesson-detail.flashcard', array_merge(
            $preparedContent,
            ['type' => 'flashcard'],
            ['flashcard' => $flashcard]
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
     * Get previous lesson
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPreviousLesson(Request $request)
    {
        $series_combo_slug = $request->get('series_combo_slug');
        $series_slug = $request->get('series_slug');
        $content_id = $request->get('content_id');

        $previousContent = $this->lmsContentService->getPreviousContent($content_id, $series_slug);
        if (!$previousContent) {
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
            'stt' => $previousContent->id
        ];

        foreach ($routes as $type => $route) {
            if (in_array($previousContent->type, $typeMap[$type])) {
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
    public function finishContent(Request $request)
    {
        $contentId = $request->get('content_id');
        $earnedPoints = $request->get('earned_points');
        $contentType = $request->get('content_type');

        $studentView = $this->lmsStudentViewService->getByConditions([
            'lmscontent_id' => $contentId,
            'users_id' => Auth::id(),
            'finish' => LmsStudentView::NOT_FINISHED
        ]);

        if ($studentView && $earnedPoints > 0) {
            $user = Auth::user();
            $this->userService->updatePointHistory([$contentType => $earnedPoints]);
            $user->update([
                'reward_point' => $user->reward_point + $earnedPoints
            ]);

            $studentView->update([
                'finish' => LmsStudentView::FINISH,
                'reward_point' => $earnedPoints,
                'updated_at' => date('Y-m-d H:i:s')
            ], [
                'lmscontent_id' => $contentId,
                'users_id' => Auth::id(),
            ]);
        }
    }

    /**
     * Handles student audit test, processes lesson content and answers submitted by student.
     * Calculates student's score and returns the audit view.
     *
     * @param Request $request
     * @param string $combo_slug
     * @param string $slug
     * @param string $stt
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Updates or inserts the student's view for the current content.
     *
     * @param string $slug
     * @param string $stt
     * @return void
     */
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

    /**
     * Stores the result of the student's test, calculates score, and updates the view status.
     * Redirects to the appropriate lesson or next content based on the result.
     *
     * @param Request $request
     * @param string $combo_slug
     * @param string $slug
     * @param string $stt
     * @return \Illuminate\Http\Response
     */
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

                if ($passed > 0.65) {
                    // Add rewarded point to user and save point history
                    $pointRule = getRewardPointRule('learning')['test']['thresholds'];
                    $pointPercent = (int) ($passed * 100);

                    for ($i = 0; $i < count($pointRule); $i++) {
                        if ($pointPercent >= $pointRule[$i]['percentage']) {
                            $rewardedPoint = $pointRule[$i]['points'];
                        }
                    }

                    if (isset($rewardedPoint) && $rewardedPoint > 0) {
                        $this->userService->updatePointHistory(['exercise_test' => $rewardedPoint], Auth::id());
                        Auth::user()->update(['reward_point' => Auth::user()->reward_point + $rewardedPoint]);
                    }

                    $this->userService->updatePoint($rewardedPoint, $content_id, Auth::id());
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


    /**
     * Show handwriting
     *
     * @param string $combo_slug
     * @param string $slug
     * @param string $stt
     * @return \Illuminate\Http\Response
     */
    public function showHandwriting(string $combo_slug = '', string $slug = '', string $stt = '')
    {
        $this->processLessonContent($combo_slug, $slug, $stt);
        $preparedContent = $this->getPreparedContentVariables();
        $japaneseHandwritingPracticeId = $preparedContent['detailContent']->japanese_writing_practice_id;
        $handwriting = $this->lmsContentService->getHandwritingContent($japaneseHandwritingPracticeId);

        return view('client.lesson-detail.handwriting', array_merge(
            $preparedContent,
            ['type' => 'handwriting'],
            ['handwriting' => $handwriting]
        ));
    }

    /*
     * Show pronunciation
     *
     * @param string $combo_slug
     * @param string $slug
     * @param string $stt
     * @return \Illuminate\Http\Response
     */
    public function showPronunciation(string $combo_slug = '', string $slug = '', string $stt = '')
    {
        $this->processLessonContent($combo_slug, $slug, $stt);
        $preparedContent = $this->getPreparedContentVariables();
        $pronunciationId = $preparedContent['detailContent']->pronunciation_id;
        $pronunciation = $this->lmsContentService->getPronunciationContent($pronunciationId);

        return view('client.lesson-detail.pronunciation', array_merge(
            $preparedContent,
            ['type' => 'pronunciation'],
            ['pronunciation' => $pronunciation]
        ));
    }

    /**
     * Show roadmap
     */
    public function roadMap(Request $request, $comboSlug, $slug)
    {
        $data['title'] = 'Roadmap';
        $data['class'] = 'roadmap';
        $serie = DB::table('lmsseries')->where('slug', $slug)->first();
        $serieCombo = DB::table('lmsseries_combo')->where('slug', $comboSlug)->first();
        $serieId = $serie->id;
        $lastViewedContent = null;
        if (auth()->check()) {
            $lastViewedContent = $this->lmsStudentViewService->getLastViewedContentOfStudent($serieId);
        }

        $roadMap = DB::table('roadmaps')->where('lmsseries_id', $serieId)->orderBy('duration_months')->get();

        $data['road_map'] = $roadMap;
        $data['last_view'] = $lastViewedContent;
        $data['serie'] = $serie;
        $data['serie_combo'] = $serieCombo;

        return view('client.pages.roadmap', $data);
    }

    /**
     * Filter and group day by week
     */
    function groupLessonsByWeek($roadmap)
    {
        $weeks = [];
        $maxDayNumber = 0;

        // Find the maximum day number in the roadmap
        foreach ($roadmap as $day) {
            $maxDayNumber = max($maxDayNumber, $day->day_number);
        }

        // First, group existing days into weeks
        foreach ($roadmap as $day) {
            $weekNumber = intval(($day->day_number - 1) / 7) + 1;

            if (!isset($weeks[$weekNumber])) {
                $weeks[$weekNumber] = [
                    'week' => "Tuần $weekNumber",
                    'message' => "Bài học của tuần $weekNumber",
                    'days' => []
                ];
            }

            // Store days in an associative array with day_number as key
            $weeks[$weekNumber]['days'][$day->day_number] = $day;
        }

        // Fill in missing days up to maxDayNumber
        $totalWeeks = ceil($maxDayNumber / 7);
        for ($weekNum = 1; $weekNum <= $totalWeeks; $weekNum++) {
            // Create week if it doesn't exist
            if (!isset($weeks[$weekNum])) {
                $weeks[$weekNum] = [
                    'week' => "Tuần $weekNum",
                    'message' => "Bài học của tuần $weekNum",
                    'days' => []
                ];
            }

            // Calculate start and end day numbers for this week
            $startDay = ($weekNum - 1) * 7 + 1;
            $endDay = $weekNum * 7;

            // For the last week, only go up to maxDayNumber
            if ($weekNum == $totalWeeks) {
                $endDay = $maxDayNumber;
            }

            // Fill in missing days
            for ($dayNum = $startDay; $dayNum <= $endDay; $dayNum++) {
                if (!isset($weeks[$weekNum]['days'][$dayNum])) {
                    // Create rest day object
                    $restDay = (object) [
                        'day_number' => $dayNum,
                        'lesson_list' => [
                            (object) [
                                'id' => null,
                                'name' => 'Ngày nghỉ',
                                'type' => 'rest'
                            ]
                        ]
                    ];
                    $weeks[$weekNum]['days'][$dayNum] = $restDay;
                }
            }

            // Sort days by day_number and reset array keys
            ksort($weeks[$weekNum]['days']);
            $weeks[$weekNum]['days'] = array_values($weeks[$weekNum]['days']);
        }

        return array_values($weeks);
    }

    /**
     * Load roadmap detail
     */
    public function loadRoadMapDetail(Request $request)
    {
        $slug = $request->slug;
        $month = $request->month;
        $serie = DB::table('lmsseries')->where('slug', $slug)->first();
        $serieId = $serie->id;

        $lastViewedContent = $this->lmsStudentViewService->getLastViewedContentOfStudent($serieId);

        $roadMap = DB::table('roadmaps')
            ->where('lmsseries_id', $serieId)
            ->where('duration_months', $month)
            ->first();
        $FINISH = 1;
        $lmsContentViewedUser = DB::table('lms_student_view')
            ->join('lmscontents', 'lms_student_view.lmscontent_id', '=', 'lmscontents.id')
            ->where('lms_student_view.users_id', Auth::id())
            ->where('lmscontents.lmsseries_id', $serieId)
            ->where('finish', $FINISH)
            ->pluck('lms_student_view.lmscontent_id');

        $roadMapContent = json_decode($roadMap->contents);
        $lastRoadmapDay = $roadMapContent[count($roadMapContent) - 1]->day_number;
        $dayViewedContent = null;
        $dayCount = count($roadMapContent);

        foreach ($roadMapContent as &$day) {
            if (isset($day->lesson_list)) {
                foreach ($day->lesson_list as &$lesson) {
                    $lesson->finish = in_array($lesson->id, $lmsContentViewedUser->toArray()) ? 1 : 0;
                }
            }
        }

        if ($lastViewedContent) {
            foreach ($roadMapContent as $day) {
                foreach ($day->lesson_list as $lesson) {
                    if ($lesson->id == $lastViewedContent->lmscontent_id) {
                        $dayViewedContent = $day->day_number;
                        break;
                    }
                }
            }
            $lastViewedContent = $lastViewedContent->lmscontent_id;
        } else {
            $lastViewedContent = null;
        }


        $weeks = $this->groupLessonsByWeek($roadMapContent);
        $processedWeeks = $this->processCourseCompletion($weeks);

        return response()->json([
            'road_map' => $processedWeeks,
            'last_view' => $lastViewedContent,
            'day_last_view' => $dayViewedContent,
            'detail' => $roadMap,
            'day_count' => $dayCount,
            'last_roadmap_day' => $lastRoadmapDay
        ]);
    }

    protected function processCourseCompletion($weeks)
    {
        $allWeeksFinished = true;

        // Process each week
        foreach ($weeks as &$week) {
            $allDaysInWeekFinished = true;

            // Process each day in the week
            foreach ($week['days'] as &$day) {
                if (isset($day->lesson_list) && !empty($day->lesson_list)) {
                    $allLessonsFinished = true;

                    // Check if day is a rest day
                    $isRestDay = false;
                    if (
                        count($day->lesson_list) === 1 &&
                        isset($day->lesson_list[0]->type) &&
                        $day->lesson_list[0]->type === 'rest'
                    ) {
                        $isRestDay = true;
                    }

                    // If not a rest day, check all lessons
                    if (!$isRestDay) {
                        foreach ($day->lesson_list as $lesson) {
                            if (!isset($lesson->finish) || $lesson->finish !== 1) {
                                $allLessonsFinished = false;
                                break;
                            }
                        }
                    }

                    // Add finish_day property if all lessons are finished or it's a rest day
                    $day->finish_day = $allLessonsFinished || $isRestDay;

                    if (!$day->finish_day) {
                        $allDaysInWeekFinished = false;
                    }
                }
            }

            // Add finish_week property if all days in week are finished
            $week['finish_week'] = $allDaysInWeekFinished;

            if (!$allDaysInWeekFinished) {
                $allWeeksFinished = false;
            }
        }

        // Add finish_course property if all weeks are finished
        $weeks['finish_course'] = $allWeeksFinished;

        return $weeks;
    }

    /**
     * Get comments in course of user
     *
     * @param string $combo_slug
     * @param string $slug
     * @param string $stt
     * @return mixed
     */
    protected function getCommentInCourseOfUser(string $combo_slug, string $slug, string $stt)
    {
        $this->prepContent['comments'] = null;

        if (Auth::check()) {
            $userId = Auth::user()->id;
            $this->prepContent['comments'] = $this->commentService->getCommentsInCourse($combo_slug, $slug, $stt, $userId);
        }
    }
}

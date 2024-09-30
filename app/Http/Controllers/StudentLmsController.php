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

    public function __construct(
        LmsContentService $lmsContentService,
        LmsSeriesService $lmsSeriesService,
        LmsStudentViewService $lmsStudentViewService,
        LmsSeriesComboService $lmsSeriesComboService,
        PaymentMethodService $paymentMethodService
    ) {
        $this->lmsContentService = $lmsContentService;
        $this->lmsSeriesService = $lmsSeriesService;
        $this->lmsStudentViewService = $lmsStudentViewService;
        $this->lmsSeriesComboService = $lmsSeriesComboService;
        $this->paymentMethodService = $paymentMethodService;
    }

    /**
     * Check validity of URL
     *
     * @param array $params
     * @return mixed
     */
    private function checkValidURL(array &$params) {
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
        }
        else {
            $content = true;
        }

        if (!$this->prepContent['series_id'] ||
            !$this->prepContent['series_combo_id'] ||
            !$content)
        {
            return redirect()->to('/');
        }
    }

    /**
     * Check validity of payment,
     * if user (or guest) access the purchase content, redirect first content of the series
     *
     * @param array $params
     * @return mixed
     */
    private function checkValidPayment(array &$params) {
        // Both a guest user and a student who hasn't purchased the series have the same trial access.
        // A student who has purchased the series is granted full access to all content in the series.
        $userId = auth()->id() ?? -1;
        $seriesId = $this->prepContent['series_id'];
        $seriesComboId = $this->prepContent['series_combo_id'];

        $this->prepContent['is_valid_payment']
            = $this->paymentMethodService->checkSerieValidity($userId, $seriesComboId);

        if ($this->prepContent['series_combo']
            && $this->prepContent['series_combo']->cost == 0)
        {
            $this->prepContent['is_free_series'] = true;
            $this->prepContent['is_valid_payment'] = true;
        } else {
            $this->prepContent['is_free_series'] = false;
        }

        if (($params['stt']) === '') {
            if ($this->prepContent['is_valid_payment']) {
                $lastViewedContent = $this->lmsStudentViewService->getLastViewedContentOfStudent($seriesId);
                $params['stt'] = $lastViewedContent->lmscontent_id ?? $this->getFirstContentId($seriesId);
            }
            else {
                $params['stt'] = $this->getFirstContentId($seriesId);
            }

            return;
        }

        $isTrialContent = $this->lmsContentService->checkTrialContent($params['stt']);
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
    private function getFirstContentId(string $seriesId) {
        $content = $this->lmsContentService->getFirstContentOfSeries($seriesId);
        return $content->id ?? null;
    }

    /**
     * Prepare content list
     *
     * @param array $params
     * @return void
     */
    private function prepareContentList(array &$params) {
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
        $routes = [
            'video' => 'learning-management.lesson.show',
            'exercise' => 'learning-management.lesson.exercise',
            'audit' => 'learning-management.lesson.audit',
            'flashcard' => 'learning-management.lesson.flashcard'
        ];
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
    private function saveStudentView(string $contentId) {
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
    private function processLessonContent(string $combo_slug, string $slug, string $stt) {
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
    private function getPreparedContentVariables() {
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
    public function showLesson (string $combo_slug = '', string  $slug = '', string $stt = '') {
        $this->processLessonContent($combo_slug, $slug, $stt);

        return view('client.lesson-detail.video', array_merge($this->getPreparedContentVariables(),
            ['type' => 'lesson']
        ));
    }

    /**
     * Show exercise
     *
     * @return \Illuminate\Http\Response
     */
    public function showExercise(string $combo_slug = '', string  $slug = '', string $stt = '') {
        $this->processLessonContent($combo_slug, $slug, $stt);

        return view('client.lesson-detail.exercise', array_merge($this->getPreparedContentVariables(),
            ['type' => 'exercise']
        ));
    }

    /**
     * Show audit
     *
     * @return \Illuminate\Http\Response
     */
    public function showAudit (string $combo_slug = '', string  $slug = '', string $stt = '') {
        $this->processLessonContent($combo_slug, $slug, $stt);

        return view('client.lesson-detail.audit', array_merge($this->getPreparedContentVariables(),
            ['type' => 'audit']
        ));
    }

    /**
     * Show flashcard
     *
     * @return \Illuminate\Http\Response
     */
    public function showFlashcard (string $combo_slug = '', string  $slug = '', string $stt = '') {
        $this->processLessonContent($combo_slug, $slug, $stt);

        return view('client.lesson-detail.flashcard', array_merge($this->getPreparedContentVariables(),
            ['type' => 'flashcard']
        ));
    }
}
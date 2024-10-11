<?php

namespace App\Services;

use App\Http\Resources\ExerciseResource;
use App\Http\Resources\FlashcardResource;
use App\Http\Resources\TestResource;
use App\LmsContent;
use App\Repositories\LmsContentRepository;
use App\Repositories\LmsExamRepository;
use App\Repositories\LmsTestRepository;
use App\Repositories\PaymentMethodRepository;

class LmsContentService extends BaseService
{
    private $paymentMethodRepository;
    private $lmsTestRepository;
    private $lmsExamRepository;
    private $lmsFlashcardService;
    private $lmsSeriesService;
    private $handwritingService;
    private $lmsSeriesComboService;

    public function __construct(
        LmsContentRepository $repository,
        PaymentMethodRepository $paymentMethodRepository,
        LmsTestRepository $lmsTestRepository,
        LmsExamRepository $lmsExamRepository,
        LmsFlashcardService $lmsFlashcardService,
        LmsSeriesService $lmsSeriesService,
        HandwritingService $handwritingService,
        LmsSeriesComboService $lmsSeriesComboService
    ) {
        parent::__construct($repository);
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->lmsTestRepository = $lmsTestRepository;
        $this->lmsExamRepository = $lmsExamRepository;
        $this->lmsFlashcardService = $lmsFlashcardService;
        $this->lmsSeriesService = $lmsSeriesService;
        $this->handwritingService = $handwritingService;
        $this->lmsSeriesComboService = $lmsSeriesComboService;
    }

    /**
     * Get contents
     *
     * @param int $userId
     * @param int $seriesComboId
     * @param int $seriesId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getContents(int $userId, int $seriesComboId, int $seriesId)
    {
        $seriesCombo = $this->lmsSeriesComboService->getBySeriesId($seriesComboId, $seriesId, ['id']);

        if (!$seriesCombo) {
            return null;
        }

        $isValid = $this->paymentMethodRepository->checkSerieValidity($userId, $seriesComboId);

        return $this->repository->getContents($seriesId, $isValid);
    }

    /**
     * Get contents by series id
     *
     * @param int $seriesId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getContentsBySerieId(int $seriesId)
    {
        return $this->repository->getContentsBySerieId($seriesId);
    }

    /**
     * Get Content by Id
     *
     * @param int $userId
     * @param int $seriesComboId
     * @param int $id
     * @return any
     */
    public function getContentById(int $userId, int $seriesComboId, int $id)
    {
        $isValid = $this->paymentMethodRepository->checkSerieValidity($userId, $seriesComboId);
        $lmsContent = $this->repository->getContentById($id);
        $content = [];

        if (!$lmsContent) {
            return null;
        }

        if (!$isValid && $lmsContent->el_try != 1) {
            return null;
        }

        if ($lmsContent->type == LmsContent::TEST) {
            $content = $this->getTestContent($id);
        }

        if (in_array($lmsContent->type, [LmsContent::PARTIAL_EXERCISE, LmsContent::SUMMARY_EXERCISE])) {
            $content = $this->getExerciseContent($id);
        }

        if ($lmsContent->type == LmsContent::FLASHCARD) {
            $content = $this->getFlashcardContent($lmsContent->flashcard_id);

            if ($content) {
                $content = FlashcardResource::collection($content->flashcardDetails);
            }
        }

        $lmsContent->content = $content;

        return $lmsContent;
    }

    /**
     * Get in progress content
     *
     * @param int $userId
     * @param int $seriesComboId
     * @param int $seriesId
     * @return any
     */
    public function getInProgressContent(int $userId, int $seriesComboId, int $seriesId)
    {
        $isValid = $this->paymentMethodRepository->checkSerieValidity($userId, $seriesComboId);

        if (!$isValid) {
            return null;
        }

        $lmsContent = $this->repository->getInProgressContent($userId, $seriesId);
        $content = [];

        if (!$lmsContent) {
            return null;
        }

        if ($lmsContent->type == LmsContent::TEST) {
            $content = $this->getTestContent($lmsContent->id);
        }

        if (in_array($lmsContent->type, [LmsContent::PARTIAL_EXERCISE, LmsContent::SUMMARY_EXERCISE])) {
            $content = $this->getExerciseContent($lmsContent->id);
        }

        if ($lmsContent->type == LmsContent::FLASHCARD) {
            $content = $this->getFlashcardContent($lmsContent->flashcard_id);

            if ($content) {
                $content = FlashcardResource::collection($content->flashcardDetails);
            }
        }

        $lmsContent->content = $content;

        return $lmsContent;
    }

    /**
     * Get test content
     *
     * @param int $lessonId
     * @return TestResource
     */
    protected function getTestContent(int $lessonId)
    {
        $test = $this->lmsTestRepository->getTestContent($lessonId);

        return TestResource::collection($test);
    }

    /**
     * Get exercise content
     *
     * @param int $lessonId
     * @return ExerciseResource
     */
    protected function getExerciseContent(int $lessonId)
    {
        $exercise = $this->lmsExamRepository->getExerciseContent($lessonId);

        return ExerciseResource::collection($exercise);
    }

    /**
     * Get flashcard content by id
     *
     * @param int $flashcardId
     * @return mixed
     */
    protected function getFlashcardContent(int $flashcardId)
    {
        $flashcard = $this->lmsFlashcardService->getFlashcardContentById($flashcardId);

        return $flashcard;
    }

    /**
     * Get list contents
     *
     * @param string $seriesId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function getListContents(string $seriesId) {
        return $this->repository->getListContents($seriesId);
    }

    /**
     * Get the list of active content IDs, where "active" refers to the ancestors
     * of contents that the current content is part of
     *
     * @param string $contentId
     * @return array
     */
    public function getActiveContentIdList(string $contentId) {
        $content = $this->repository->findByIdWithAncestors($contentId);

        if (is_null($content)) {
            return [];
        }

        // A content either has no parent or 2 levels of parents
        if (is_null($content->parentContent)) {
            return [$content->id];
        }

        return [
            $content->id,
            $content->parentContent->id,
            $content->parentContent->parentContent->id
        ];
    }

    /**
     * Check if the content is a trial content
     *
     * @param string $contentId
     * @return boolean
     */
    public function checkTrialContent(string $contentId) {
        return optional($this->repository->findById((int) $contentId))->el_try === LmsContent::TRIAL_TYPE;
    }

    /**
     * Get the first content of the series
     *
     * @param string $seriesId
     * @return mixed(LmsContent|null)
     */
    public function getFirstContentOfSeries(string $seriesId) {
        return $this->repository->getFirstContentOfSeries($seriesId);
    }

    /**
     * Get next content
     *
     * @param mixed $contentId
     * @param mixed $seriesSlug
     * @return mixed(LmsContent|null)
     */
    public function getNextContent($contentId, $seriesSlug) {
        $seriesId = optional($this->lmsSeriesService->getByCondition('slug', $seriesSlug))->id;
        $contentOrder = optional($this->repository->findById($contentId))->stt;
        if (is_null($seriesId) || is_null($contentOrder)) {
            return null;
        }

        return $this->repository->getNextContent($contentOrder, $seriesId);
    }

    /**
     * Get exercise content
     *
     * @param string $contentId
     * @return \Illuminate\Support\Collection
     */
    public function getFormattedExerciseContent(string $contentId) {
        $exercises =  $this->repository->getFormattedExerciseContent($contentId);

        if (!$exercises->isEmpty()) {
            foreach ($exercises as $key => $value) {
                $exercises[$key]->mota = change_furigana(mb_convert_encoding(str_replace('＿＿', '__', $value->mota), "UTF-8", "auto"), 'return');
                $exercises[$key]->answers = explode('-,-', trim($value->answers));
            }
            foreach ($exercises as $key => $record) {
                $valueAnswers = array();
                foreach ($record->answers as $answer) {
                    $valueAnswers[] = change_furigana($answer, 'return');
                }
                $exercises[$key]->answers = $valueAnswers;
            }
        }

        return $exercises;
    }

    /**
     * Get handwriting content by id
     *
     * @param int $handwritingId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getHandwritingContent(int $handwritingId)
    {
        $handwriting = $this->handwritingService->findByIdWithRelations($handwritingId, ['hiraganaWritingPractices', 'kanjiWritingPractices']);

        return $handwriting;
    }
}

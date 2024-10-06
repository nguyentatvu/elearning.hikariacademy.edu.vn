<?php

namespace App\Services;

use App\Http\Resources\ExerciseResource;
use App\Http\Resources\FlashcardResource;
use App\Http\Resources\TestResource;
use App\LmsContent;
use App\Repositories\LmsContentRepository;
use App\Repositories\LmsExamRepository;
use App\Repositories\LmsFlashcardRepository;
use App\Repositories\LmsSeriesRepository;
use App\Repositories\LmsTestRepository;
use App\Repositories\PaymentMethodRepository;

class LmsContentService extends BaseService
{
    private $paymentMethodRepository;
    private $lmsTestRepository;
    private $lmsExamRepository;
    private $lmsFlashcardRepository;
    private $handwritingService;

    public function __construct(
        LmsContentRepository $repository,
        PaymentMethodRepository $paymentMethodRepository,
        LmsTestRepository $lmsTestRepository,
        LmsExamRepository $lmsExamRepository,
        LmsFlashcardRepository $lmsFlashcardRepository,
        HandwritingService $handwritingService
    ) {
        parent::__construct($repository);
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->lmsTestRepository = $lmsTestRepository;
        $this->lmsExamRepository = $lmsExamRepository;
        $this->lmsFlashcardRepository = $lmsFlashcardRepository;
        $this->handwritingService = $handwritingService;
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
        $content = $this->repository->getContentById($id);

        if (!$content) {
            return null;
        }

        if (!$isValid && $content->el_try != 1) {
            return null;
        }

        if ($content->type == LmsContent::TEST) {
            $test = $this->getTestContent($id);
        }

        if (in_array($content->type, [LmsContent::PARTIAL_EXERCISE, LmsContent::SUMMARY_EXERCISE])) {
            $exercise = $this->getExerciseContent($id);
        }

        if ($content->type == LmsContent::FLASHCARD) {
            $flashcard = $this->getFlashcardContent($content->flashcard_id);
        }

        $content->test = $test ?? [];
        $content->exercise = $exercise ?? [];
        $content->flashcard = $flashcard ?? [];

        return $content;
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

        $content = $this->repository->getInProgressContent($userId, $seriesId);

        if (!$content) {
            return null;
        }

        if ($content->type == LmsContent::TEST) {
            $test = $this->getTestContent($content->id);
        }

        if (in_array($content->type, [LmsContent::PARTIAL_EXERCISE, LmsContent::SUMMARY_EXERCISE])) {
            $exercise = $this->getExerciseContent($content->id);
        }

        if ($content->type == LmsContent::FLASHCARD) {
            $flashcard = $this->getFlashcardContent($content->flashcard_id);
        }

        $content->test = $test ?? [];
        $content->exercise = $exercise ?? [];
        $content->flashcard = $flashcard ?? [];

        return $content;
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
     * @return FlashcardResource
     */
    protected function getFlashcardContent(int $flashcardId)
    {
        $flashcard = $this->lmsFlashcardRepository->getFlashcardContentById($flashcardId);

        return new FlashcardResource($flashcard);
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
        return $this->repository->findById((int) $contentId)->el_try === LmsContent::TRIAL_TYPE;
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

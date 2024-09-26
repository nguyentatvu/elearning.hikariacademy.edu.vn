<?php

namespace App\Services;

use App\Http\Resources\ExerciseResource;
use App\Http\Resources\FlashcardResource;
use App\Http\Resources\TestResource;
use App\LmsContent;
use App\Repositories\LmsContentRepository;
use App\Repositories\LmsExamRepository;
use App\Repositories\LmsFlashcardRepository;
use App\Repositories\LmsTestRepository;
use App\Repositories\PaymentMethodRepository;

class LmsContentService
{
    private $lmsContentRepository;
    private $paymentMethodRepository;
    private $lmsTestRepository;
    private $lmsExamRepository;
    private $lmsFlashcardRepository;

    public function __construct(
        LmsContentRepository $lmsContentRepository,
        PaymentMethodRepository $paymentMethodRepository,
        LmsTestRepository $lmsTestRepository,
        LmsExamRepository $lmsExamRepository,
        LmsFlashcardRepository $lmsFlashcardRepository
    ) {
        $this->lmsContentRepository = $lmsContentRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->lmsTestRepository = $lmsTestRepository;
        $this->lmsExamRepository = $lmsExamRepository;
        $this->lmsFlashcardRepository = $lmsFlashcardRepository;
    }

    /**
     * Get contents by series id
     *
     * @param int $seriesId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getContentsBySerieId(int $seriesId)
    {
        return $this->lmsContentRepository->getContentsBySerieId($seriesId);
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
        $content = $this->lmsContentRepository->getContentById($id);

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

        $content = $this->lmsContentRepository->getInProgressContent($userId, $seriesId);

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
}

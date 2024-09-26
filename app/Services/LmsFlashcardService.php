<?php

namespace App\Services;

use App\Repositories\LmsFlashcardRepository;

class LmsFlashcardService
{
    private $lmsFlashcardRepository;

    public function __construct(LmsFlashcardRepository $lmsFlashcardRepository)
    {
        $this->lmsFlashcardRepository = $lmsFlashcardRepository;
    }

    /**
     * Gets the flashcard content by id
     *
     * @param int $flashcardId
     * @return LmsFlashcard
     */
    public function getFlashcardContentById(int $flashcardId)
    {
        return $this->lmsFlashcardRepository->getFlashcardContentById($flashcardId);
    }
}

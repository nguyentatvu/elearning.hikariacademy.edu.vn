<?php

namespace App\Services;

use App\Repositories\LmsFlashcardRepository;

class LmsFlashcardService extends BaseService
{
    public function __construct(LmsFlashcardRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Gets the flashcard content by id
     *
     * @param int $flashcardId
     * @return LmsFlashcard
     */
    public function getFlashcardContentById(int $flashcardId)
    {
        return $this->repository->getFlashcardContentById($flashcardId);
    }
}

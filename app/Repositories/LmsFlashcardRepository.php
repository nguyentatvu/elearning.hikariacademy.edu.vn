<?php

namespace App\Repositories;

class LmsFlashcardRepository extends BaseRepository
{
    /**
     * Gets the flashcard content by id
     *
     * @param int $flashcardId
     * @return Flashcard
     */
    public function getFlashcardContentById(int $flashcardId)
    {
        $flashcard = $this->model::where('id', $flashcardId)
            ->select('id', 'name')
            ->with('flashcardDetails')
            ->first();

        return $flashcard;
    }
}

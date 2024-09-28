<?php

namespace App\Repositories;

class ConversationRepository extends BaseRepository
{
    /**
     * Get all the conversations
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getListByUserId(int $userId)
    {
        $conversations = $this->model::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return $conversations;
    }
}

<?php

namespace App\Repositories;

class MessageHistoryRepository extends BaseRepository
{
    /**
     * Get message history by user id
     *
     * @param string $conversationId
     * @param int $userId
     * @return mixed
     */
    public function getConversationMessagesByUser(string $difyConversationId, int $userId)
    {
        $messageHistory = $this->model::where('user_id', $userId)
            ->whereHas('conversation', function ($query) use ($difyConversationId) {
                $query->where('dify_conversation_id', $difyConversationId);
            })
            ->paginate(10);

        return $messageHistory;
    }
}

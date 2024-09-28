<?php

namespace App\Services;

use App\Repositories\ConversationRepository;

class ConversationService
{
    private $conversationRepository;
    private $apiKey;
    private $chatbotUrl;

    public function __construct(ConversationRepository $conversationRepository)
    {
        $this->apiKey = env('CHAT_BOT_API_KEY');
        $this->chatbotUrl = env('CHAT_BOT_URL') . '/' . config('constant.chatbot.endpoint.delete_conversation');
        $this->conversationRepository = $conversationRepository;
    }

    /**
     * Get all the conversations
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getListByUserId(int $userId)
    {
        return $this->conversationRepository->getListByUserId($userId);
    }

    /**
     * Create new conversation if not exist
     *
     * @param array $condition
     * @param array $data
     * @return mixed
     */
    public function updateOrCreate(array $condition = [], array $data = [])
    {
        return $this->conversationRepository->updateOrCreate($condition, $data);
    }

    /**
     * Create new conversation if not exist
     *
     * @param array $condition
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate(array $condition)
    {
        return $this->conversationRepository->firstOrCreate($condition);
    }

    /**
     * Delete conversation by key value conditions and delete on dify
     *
     * @param array $conditions
     * @param string $userEmail
     * @return bool
     */
    public function deleteConversation(array $conditions = [], string $userEmail)
    {
        $chatbotUrl = $this->chatbotUrl . '/' . $conditions['dify_conversation_id'];
        $method = 'DELETE';
        $body = [
            'user' => $userEmail,
        ];

        $response = callApi($chatbotUrl, $this->apiKey, $method, $body);
        app_log()->info('Delete conversation on dify', ['response' => $response]);

        return $this->conversationRepository->deletebyKeyValueConditions($conditions);
    }
}

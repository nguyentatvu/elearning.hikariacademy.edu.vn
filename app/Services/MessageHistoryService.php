<?php

namespace App\Services;

use App\Repositories\MessageHistoryRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class MessageHistoryService
{
    private $messageHistoryRepository;
    private $conversationService;

    public function __construct(
        MessageHistoryRepository $messageHistoryRepository,
        ConversationService $conversationService
    ) {
        $this->messageHistoryRepository = $messageHistoryRepository;
        $this->conversationService = $conversationService;
    }

    /**
     * Send message
     *
     * @param int $userId
     * @param array $data
     * @return mixed
     */
    public function sendMessage(int $userId, array $data)
    {
        $apiKey = env('CHAT_BOT_API_KEY');
        $chatbotUrl = env('CHAT_BOT_URL') . '/' . config('constant.chatbot.endpoint.chat_messages');
        $method = 'POST';
        $body = [
            'inputs' => new stdClass(),
            'query' => $data['message'],
            'response_mode' => 'blocking',
            'conversation_id' => $data['conversation_id'] ?? "",
            'user' => $data['email'],
            'files' => [],
        ];

        $response = callApi($chatbotUrl, $apiKey, $method, $body);

        DB::beginTransaction();

        try {
            // Create conversation data
            $conversationId = $response['conversation_id'] ?? "";
            $createdConversation = $this->createConversationIfNotExists($userId, $conversationId, $data);

            // Store message history
            $messageHistory = [
                'user_id' => $userId,
                'conversation_id' => $createdConversation->id,
                'user_message' => $data['message'],
                'bot_response' => $response['answer'] ?? "",
            ];

            $createdMessageHistory = $this->store($messageHistory);
            $createdMessageHistory->dify_conversation_id = $conversationId;

            DB::commit();

            return $createdMessageHistory;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("In file " . $e->getFile() . " on line " . $e->getLine() . " function sendMessage: " . $e->getMessage());

            return null;
        }
    }

    /**
     * Store message history
     *
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        return $this->messageHistoryRepository->create($data);
    }

    /**
     * Get message history by user id
     *
     * @param string $conversationId
     * @param int $userId
     * @return mixed
     */
    public function getConversationMessagesByUser(string $difyConversationId, int $userId)
    {
        return $this->messageHistoryRepository->getConversationMessagesByUser($difyConversationId, $userId);
    }

    /**
     * Create conversation data
     *
     * @param int $userId
     * @param string $conversationId
     * @param array $data
     * @return mixed
     */
    protected function createConversationIfNotExists(int $userId, string $conversationId, array $data)
    {
        $conversationData = [];
        $conversationData['title'] = substr($data['message'], 0, 25);
        $conversationData['user_id'] = $userId;

        return $this->conversationService->firstOrCreate([['dify_conversation_id' => $conversationId], $conversationData]);
    }
}

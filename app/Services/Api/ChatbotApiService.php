<?php

namespace App\Services\Api;

class ChatbotApiService extends BaseApiService
{
    public function ask(string $message, array $history = []): array
    {
        return $this->post('chatbot.ask', [], [
            'message' => $message,
            'history' => $history,
        ]);
    }
}

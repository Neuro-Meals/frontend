<?php

namespace App\Http\Controllers;

use App\Services\Api\ChatbotApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    private const HISTORY_LIMIT = 10;

    public function landing(Request $request, ChatbotApiService $chatbotApi)
    {
        return $this->handleChat($request, $chatbotApi, 'landing');
    }

    public function customer(Request $request, ChatbotApiService $chatbotApi)
    {
        return $this->handleChat($request, $chatbotApi, 'customer');
    }

    private function handleChat(Request $request, ChatbotApiService $chatbotApi, string $context)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'history' => 'sometimes|array|max:10',
        ]);

        $message = $request->input('message');
        $rawHistory = $request->input('history', []);

        $history = [];
        foreach ($rawHistory as $item) {
            $role = $item['role'] ?? '';
            $content = $item['content'] ?? ($item['message'] ?? '');
            if (in_array($role, ['user', 'assistant']) && !empty($content)) {
                $history[] = ['role' => $role, 'content' => $content];
            }
        }
        $history = array_slice($history, -self::HISTORY_LIMIT);

        $hasToken = session('api_token') !== null;

        if (!$hasToken && $context === 'landing') {
            return response()->json([
                'success' => false,
                'message' => __('Please log in or create an account to chat with our AI assistant.'),
            ]);
        }

        try {
            $response = $chatbotApi->ask($message, $history);

            if (isset($response['success']) && $response['success'] === false) {
                return response()->json([
                    'success' => false,
                    'message' => $response['message'] ?? __('The AI assistant is temporarily unavailable.'),
                ], 200);
            }

            $answer = $response['answer'] ?? ($response['data']['answer'] ?? null);

            if (!$answer) {
                return response()->json([
                    'success' => false,
                    'message' => __('The AI assistant did not return a response. Please try again.'),
                ]);
            }

            return response()->json([
                'success' => true,
                'context' => $context,
                'reply' => $answer,
            ]);
        } catch (\Exception $e) {
            Log::error('Chatbot API error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Sorry, I\'m having trouble connecting right now. Please try again later.'),
            ]);
        }
    }
}

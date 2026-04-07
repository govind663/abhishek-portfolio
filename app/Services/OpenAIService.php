<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openai.key');

        if (!$this->apiKey) {
            Log::error('OpenAI API key not set in config/services.php');
            throw new \Exception('OpenAI API key not configured.');
        }
    }

    /**
     * Send message to OpenAI and get reply
     */
    public function chat(
        string $message,
        string $systemPrompt = '',
        string $model = 'gpt-4o-mini',
        float $temperature = 0.7,
        int $maxTokens = 500
    ): array {
        try {
            // ==== OpenAI request
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout(30)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $message],
                ],
                'temperature' => $temperature,
                'max_tokens' => $maxTokens,
            ]);

            if (!$response->successful()) {
                $status = $response->status();
                $body = $response->body();

                Log::error('OpenAI API Error', compact('status', 'body'));

                $fallbackMessage = ($status == 429)
                    ? 'AI assistant is temporarily unavailable due to quota limits. Please try again later.'
                    : 'AI service is currently unavailable.';

                return [
                    'reply' => $fallbackMessage,
                    'suggestions' => []
                ];
            }

            $data = $response->json();
            $reply = $data['choices'][0]['message']['content'] ?? "Sorry, I couldn't generate a response.";

            // ==== Dynamic suggestions based on user query
            $lowerMessage = strtolower($message);
            $suggestions = [];

            if (str_contains($lowerMessage, 'resume')) {
                $suggestions = ['Show my projects', 'Contact for hire'];
            } elseif (str_contains($lowerMessage, 'skill')) {
                $suggestions = ['Show projects', 'View resume'];
            } elseif (str_contains($lowerMessage, 'project')) {
                $suggestions = ['View resume', 'How to contact'];
            } else {
                // default suggestions
                $suggestions = [
                    'Tell me about your skills',
                    'Show your projects',
                    'How can I hire you?',
                ];
            }

            return [
                'reply' => $reply,
                'suggestions' => $suggestions
            ];

        } catch (\Exception $e) {
            Log::error('OpenAIService Exception: ' . $e->getMessage());

            return [
                'reply' => 'AI assistant is temporarily unavailable.',
                'suggestions' => []
            ];
        }
    }
}
<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OpenAIService;
use App\Models\AiChat;
use Illuminate\Support\Facades\Cache;

class AiChatController extends Controller
{
    protected OpenAIService $openAI;

    public function __construct(OpenAIService $openAI)
    {
        $this->openAI = $openAI;
    }

    public function chat(Request $request)
    {
        $message = trim($request->input('message', ''));
        if ($message === '') {
            return response()->json([
                'reply' => 'Please type a message.',
                'suggestions' => []
            ]);
        }

        $messageLower = strtolower($message);

        // ==== Knowledge base (fast keyword replies)
        $knowledgeBase = [
            'resume' => 'You can view or download Abhishek’s resume here: <a href="/resume" target="_blank">View Resume</a>',
            'hire' => 'You can contact Abhishek through the contact page: <a href="/contact-us">Contact Now</a>',
            'contact' => 'You can contact Abhishek through the contact page: <a href="/contact-us">Contact Now</a>',
            'project' => 'Abhishek has built multiple Laravel applications including inventory systems, QR code tracking systems, and REST API platforms. <a href="/portfolio">View Portfolio</a>',
            'portfolio' => 'Abhishek has built multiple Laravel applications including inventory systems, QR code tracking systems, and REST API platforms. <a href="/portfolio">View Portfolio</a>',
            'skill' => 'Abhishek is skilled in PHP, Laravel (8-12), CodeIgniter, MySQL, REST API Development, Payment Gateway Integration (Razorpay, Stripe, Easebuzz), Task Scheduling, Queues & Jobs, Middleware, JWT/Sanctum Authentication, OOP & MVC, and building scalable web applications.',
            'education' => 'Abhishek completed B.E in Information Technology from D.Y. Patil Institute of Engineering and Technology, Pune (CGPA: 6.32). Higher Secondary from Tilak Junior College, Navi Mumbai (CGPA: 5.47), and SSC from National Public High School, Nerul (CGPA: 6.32).',
            'experience' => 'Abhishek has 4+ years of experience working at Notion Technologies, Coreocean Solutions LLP, and Speed TechServe Pvt. Ltd. He has built feature-rich web applications, RESTful APIs, admin dashboards, multi-role authentication systems, and reusable Laravel components.',
        ];

        $reply = 'I am not sure about that. Could you ask something else?';
        $suggestions = ['Resume', 'Skills', 'Projects', 'Experience', 'Education', 'Hire'];

        foreach ($knowledgeBase as $key => $value) {
            if (str_contains($messageLower, $key)) {
                $reply = $value;
                break;
            }
        }

        // ==== Caching key
        $cacheKey = 'chat_' . md5($messageLower);

        if ($reply === 'I am not sure about that. Could you ask something else?') {
            // Check DB cache first
            if (Cache::has($cacheKey)) {
                $cached = Cache::get($cacheKey);
                $reply = $cached['reply'];
                $suggestions = $cached['suggestions'] ?? $suggestions;
            } else {
                // ==== Fallback to OpenAI
                $systemPrompt = "
                You are an AI assistant for Abhishek Jha's portfolio website.
                Abhishek Jha is a PHP/Laravel Developer with 4+ years experience.
                Skills: PHP, Laravel, CodeIgniter, MySQL, REST API Development, Payment Gateway Integration, Task Scheduling, Queues & Jobs, JWT/Sanctum Authentication.
                Location: Navi Mumbai, India.
                Keep answers short, friendly, and professional.
                ";

                $result = $this->openAI->chat($message, $systemPrompt);
                $reply = $result['reply'];
                if (!empty($result['suggestions'])) {
                    $suggestions = $result['suggestions'];
                }

                // ==== Store in cache for 1 day
                Cache::put($cacheKey, [
                    'reply' => $reply,
                    'suggestions' => $suggestions
                ], 86400); // seconds
            }
        }

        // ==== Store chat in database
        AiChat::create([
            'user_id' => null, // null if guest
            'user_message' => $message,
            'ai_reply' => $reply,
            'suggestions' => $suggestions,
        ]);

        return response()->json([
            'reply' => $reply,
            'suggestions' => $suggestions
        ]);
    }
}
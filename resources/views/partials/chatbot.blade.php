{{-- Chatbot CSS --}}
<link rel="stylesheet" href="{{ asset('frontend/assets/css/chatbot.css') }}">

{{-- CSRF token meta --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Chatbot Toggle Button -->
<div id="chat-toggle">💬</div>

<!-- Chatbot Box -->
<div id="chat-box">
    <div>AI Portfolio Assistant</div>

    <!-- Chat messages container -->
    <div id="chat-messages">
        <p><b>AI:</b> Hi 👋 I'm Abhishek's AI assistant. Ask me about skills, projects, or resume.</p>

        <!-- Suggestions container -->
        <div id="suggestions">
            @php
                // Default suggestions (can be overridden by database later)
                $defaultSuggestions = ['What are your skills?', 'Show your projects', 'Can I see your resume?', 'How can I hire you?'];
            @endphp
            @foreach($defaultSuggestions as $suggestion)
                <button class="ai-btn" onclick="askAI('{{ $suggestion }}')">{{ $suggestion }}</button>
            @endforeach
        </div>
    </div>

    <!-- Input field -->
    <div style="padding:10px;border-top:1px solid #eee;">
        <input type="text" id="chat-input" placeholder="Ask something...">
    </div>

    <!-- Send button -->
    <div style="padding:10px;text-align:right;">
        <button id="chat-send-btn">Send</button>
    </div>
</div>

{{-- Chatbot JS --}}
<script>
    // CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
</script>

{{-- Chatbot JS --}}
<script>
    const AI_CHAT_ROUTE = "{{ route('ai.chat') }}";
</script>
<script src="{{ asset('frontend/assets/js/chatbot.js') }}" defer></script>
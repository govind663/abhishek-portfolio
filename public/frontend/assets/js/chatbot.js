// ==========================
// Chatbot Elements
// ==========================
const chatBox = document.getElementById("chat-box");
const toggleBtn = document.getElementById("chat-toggle");
const chatMessages = document.getElementById("chat-messages");
const chatInput = document.getElementById("chat-input");
const suggestionsDiv = document.getElementById("suggestions");
let typingTimeout;

// ==========================
// Local cache for repeated questions
// ==========================
const chatCache = new Map();

// ==========================
// Chatbox toggle visibility
// ==========================
toggleBtn.addEventListener("click", () => {
    chatBox.style.display = chatBox.style.display === "none" ? "flex" : "none";
    if (chatBox.style.display === "flex") scrollToBottom();
});

// ==========================
// Enter key triggers send
// ==========================
chatInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") sendMessage();
});

// ==========================
// Send button
// ==========================
let sendBtn = document.getElementById("chat-send-btn");
if (!sendBtn) {
    sendBtn = document.createElement("button");
    sendBtn.id = "chat-send-btn";
    sendBtn.innerText = "Send";
    sendBtn.addEventListener("click", sendMessage);
    chatBox.querySelector("div:last-child").appendChild(sendBtn);
}

// ==========================
// Click on suggestion
// ==========================
function askAI(question) {
    chatInput.value = question;
    sendMessage();
}

// ==========================
// Send message to backend with cache check
// ==========================
function sendMessage() {
    const message = chatInput.value.trim();
    if (!message) return;

    appendMessage("You", message);
    chatInput.value = "";

    if (chatCache.has(message)) {
        const cached = chatCache.get(message);
        typeWriter("AI", cached.reply, 0);
        updateSuggestions(cached.suggestions);
        return;
    }

    const typingEl = appendMessage("AI", "Typing...");

    fetch(AI_CHAT_ROUTE, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ message })
    })
    .then(res => res.json())
    .then(data => {
        removeMessage(typingEl);
        typeWriter("AI", data.reply || "No response", 0);
        if (data.suggestions && Array.isArray(data.suggestions)) {
            updateSuggestions(data.suggestions);
        }
        chatCache.set(message, {
            reply: data.reply || "No response",
            suggestions: data.suggestions || []
        });
    })
    .catch(() => {
        removeMessage(typingEl);
        appendMessage("AI", "Server error. Please try again.");
    });
}

// ==========================
// Append message to chat
// ==========================
function appendMessage(sender, text) {
    const p = document.createElement("p");
    p.innerHTML = `<b style="color: ${sender === "AI" ? "var(--link-color)" : "var(--chat-text)"}">${sender}:</b> ${text}`;
    chatMessages.appendChild(p);
    scrollToBottom();
    return p;
}

// ==========================
// Remove message element
// ==========================
function removeMessage(el) {
    if (el && el.parentNode) el.parentNode.removeChild(el);
}

// ==========================
// Typing animation (word-by-word)
// ==========================
function typeWriter(sender, text, i) {
    if (i === 0) appendMessage(sender, "");
    const messages = chatMessages.querySelectorAll("p");
    const lastMsg = messages[messages.length - 1];
    const words = text.split(" ");
    if (i < words.length) {
        lastMsg.innerHTML = `<b style="color: ${sender === "AI" ? "var(--link-color)" : "var(--chat-text)"}">${sender}:</b> ${words.slice(0, i + 1).join(" ")}|`;
        scrollToBottom();
        typingTimeout = setTimeout(() => typeWriter(sender, text, i + 1), 25);
    } else {
        lastMsg.innerHTML = `<b style="color: ${sender === "AI" ? "var(--link-color)" : "var(--chat-text)"}">${sender}:</b> ${text}`;
        scrollToBottom();
    }
}

// ==========================
// Scroll chat to bottom
// ==========================
function scrollToBottom() {
    chatMessages.scrollTo({
        top: chatMessages.scrollHeight,
        behavior: "smooth"
    });
}

// ==========================
// Update suggestions buttons dynamically
// ==========================
function updateSuggestions(suggestions) {
    suggestionsDiv.innerHTML = "";
    suggestions.forEach((q) => {
        const btn = document.createElement("button");
        btn.className = "ai-btn";
        btn.innerText = q;
        btn.onclick = () => askAI(q);
        suggestionsDiv.appendChild(btn);
    });
}

// ==========================
// Clear typing if new message
// ==========================
chatInput.addEventListener("input", () => {
    if (typingTimeout) clearTimeout(typingTimeout);
});

// ==========================
// Dark Mode Toggle with Icon
// ==========================
const body = document.body;

const darkToggle = document.createElement("button");
darkToggle.id = "dark-toggle-btn";
darkToggle.style.position = "fixed";
darkToggle.style.bottom = "20px";
darkToggle.style.left = "20px";
darkToggle.style.padding = "10px 14px";
darkToggle.style.borderRadius = "50%";
darkToggle.style.border = "none";
darkToggle.style.background = "#007bff";
darkToggle.style.color = "#fff";
darkToggle.style.cursor = "pointer";
darkToggle.style.zIndex = 999;
darkToggle.style.boxShadow = "0 4px 12px rgba(0,0,0,0.3)";
darkToggle.style.transition = "all 0.4s ease, transform 0.3s ease";
document.body.appendChild(darkToggle);

// Load saved theme
const savedTheme = localStorage.getItem("chatbot-theme");
if (savedTheme) setTheme(savedTheme);
else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) setTheme("dark");
else setTheme("light");

// Toggle click event
darkToggle.addEventListener("click", () => {
    darkToggle.style.transform = "rotate(360deg) scale(1.2)";
    setTimeout(() => darkToggle.style.transform = "rotate(0deg) scale(1)", 300);
    const current = body.dataset.theme === "dark" ? "light" : "dark";
    setTheme(current);
    localStorage.setItem("chatbot-theme", current);
});

// Apply theme and update icon
function setTheme(theme) {
    if (theme === "dark") {
        body.dataset.theme = "dark";
        darkToggle.innerText = "🌙"; // Moon icon
        document.documentElement.style.setProperty('--chat-bg', '#1e1e2f');
        document.documentElement.style.setProperty('--chat-text', '#eee');
        document.documentElement.style.setProperty('--chat-header-bg', '#0d6efd');
        document.documentElement.style.setProperty('--chat-header-text', '#fff');
        document.documentElement.style.setProperty('--input-bg', '#2a2a3a');
        document.documentElement.style.setProperty('--input-text', '#eee');
        document.documentElement.style.setProperty('--input-border', '#555');
        document.documentElement.style.setProperty('--btn-bg', '#2a2a3a');
        document.documentElement.style.setProperty('--btn-text', '#eee');
        document.documentElement.style.setProperty('--btn-hover-bg', '#3a3a4a');
        document.documentElement.style.setProperty('--btn-hover-text', '#fff');
        document.documentElement.style.setProperty('--link-color', '#66b0ff');
        document.documentElement.style.setProperty('--link-hover-color', '#a0d1ff');
        document.documentElement.style.setProperty('--shadow-light', 'rgba(0,0,0,0.4)');
        document.documentElement.style.setProperty('--shadow-dark', 'rgba(0,0,0,0.6)');
    } else {
        body.dataset.theme = "light";
        darkToggle.innerText = "🌞"; // Sun icon
        document.documentElement.style.setProperty('--chat-bg', '#fff');
        document.documentElement.style.setProperty('--chat-text', '#333');
        document.documentElement.style.setProperty('--chat-header-bg', '#007bff');
        document.documentElement.style.setProperty('--chat-header-text', '#fff');
        document.documentElement.style.setProperty('--input-bg', '#fff');
        document.documentElement.style.setProperty('--input-text', '#333');
        document.documentElement.style.setProperty('--input-border', '#ccc');
        document.documentElement.style.setProperty('--btn-bg', '#f1f1f1');
        document.documentElement.style.setProperty('--btn-text', '#333');
        document.documentElement.style.setProperty('--btn-hover-bg', '#e0e0e0');
        document.documentElement.style.setProperty('--btn-hover-text', '#333');
        document.documentElement.style.setProperty('--link-color', '#007bff');
        document.documentElement.style.setProperty('--link-hover-color', '#0056b3');
        document.documentElement.style.setProperty('--shadow-light', 'rgba(0,0,0,0.1)');
        document.documentElement.style.setProperty('--shadow-dark', 'rgba(0,0,0,0.25)');
    }
}
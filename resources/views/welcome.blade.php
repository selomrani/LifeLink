<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Grace – LifeLink Assistant</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --red:       #c0392b;
            --red-light: #fdf0ef;
            --red-mid:   #e8c5c2;
            --ink:       #1a1a1a;
            --muted:     #6b6b6b;
            --surface:   #f9f6f4;
            --white:     #ffffff;
            --border:    #e8e2de;
            --radius:    14px;
        }

        html, body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
            background: var(--surface);
            color: var(--ink);
        }

        .layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            background: var(--white);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            padding: 32px 24px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 36px; height: 36px;
            background: var(--red);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }

        .logo-icon svg { width: 18px; height: 18px; fill: white; }

        .logo-name {
            font-family: 'DM Serif Display', serif;
            font-size: 20px;
            color: var(--ink);
            letter-spacing: -0.3px;
        }

        .logo-name span { color: var(--red); }

        .sidebar-label {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 16px;
        }

        .grace-card {
            background: var(--red-light);
            border: 1px solid var(--red-mid);
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 32px;
        }

        .grace-avatar {
            width: 48px; height: 48px;
            background: var(--red);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 12px;
            font-family: 'DM Serif Display', serif;
            font-size: 20px;
            color: white;
        }

        .grace-name {
            font-family: 'DM Serif Display', serif;
            font-size: 16px;
            color: var(--ink);
            margin-bottom: 4px;
        }

        .grace-desc {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.6;
        }

        .status-dot {
            display: inline-block;
            width: 7px; height: 7px;
            background: #27ae60;
            border-radius: 50%;
            margin-right: 5px;
        }

        .info-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .info-list li {
            font-size: 12px;
            color: var(--muted);
            padding: 10px 12px;
            border-radius: 8px;
            background: var(--surface);
            border: 1px solid var(--border);
            line-height: 1.5;
        }

        .info-list li strong {
            display: block;
            font-size: 11px;
            font-weight: 500;
            color: var(--ink);
            margin-bottom: 2px;
        }

        .clear-btn {
            margin-top: auto;
            background: none;
            border: 1px solid var(--border);
            color: var(--muted);
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all .2s;
            text-align: center;
            width: 100%;
        }

        .clear-btn:hover { background: var(--surface); color: var(--red); border-color: var(--red-mid); }

        /* Chat area */
        .chat-area {
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        .chat-header {
            padding: 20px 32px;
            border-bottom: 1px solid var(--border);
            background: var(--white);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chat-header-title {
            font-family: 'DM Serif Display', serif;
            font-size: 18px;
            color: var(--ink);
        }

        .chat-header-sub {
            font-size: 12px;
            color: var(--muted);
        }

        .messages {
            flex: 1;
            overflow-y: auto;
            padding: 32px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            scroll-behavior: smooth;
        }

        .messages::-webkit-scrollbar { width: 4px; }
        .messages::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        .msg-row {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            animation: fadeUp .25s ease;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .msg-row.user { flex-direction: row-reverse; }

        .msg-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
            font-weight: 500;
        }

        .msg-avatar.grace-av {
            background: var(--red);
            color: white;
            font-family: 'DM Serif Display', serif;
            font-size: 14px;
        }

        .msg-avatar.user-av {
            background: var(--ink);
            color: white;
        }

        .msg-bubble {
            max-width: 62%;
            padding: 14px 18px;
            border-radius: 18px;
            font-size: 14px;
            line-height: 1.7;
            white-space: pre-wrap;
        }

        .msg-row.grace .msg-bubble {
            background: var(--white);
            border: 1px solid var(--border);
            border-bottom-left-radius: 4px;
            color: var(--ink);
        }

        .msg-row.user .msg-bubble {
            background: var(--red);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .msg-time {
            font-size: 10px;
            color: var(--muted);
            margin-top: 4px;
            padding: 0 4px;
        }

        .msg-col { display: flex; flex-direction: column; }
        .msg-row.user .msg-col { align-items: flex-end; }

        .typing-indicator {
            display: none;
            align-items: flex-end;
            gap: 10px;
        }

        .typing-indicator.visible { display: flex; }

        .typing-bubble {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 18px;
            border-bottom-left-radius: 4px;
            padding: 14px 18px;
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .typing-bubble span {
            width: 7px; height: 7px;
            background: var(--red-mid);
            border-radius: 50%;
            animation: bounce .9s infinite;
        }

        .typing-bubble span:nth-child(2) { animation-delay: .2s; }
        .typing-bubble span:nth-child(3) { animation-delay: .4s; }

        @keyframes bounce {
            0%, 80%, 100% { transform: translateY(0); background: var(--red-mid); }
            40%            { transform: translateY(-6px); background: var(--red); }
        }

        .empty-state {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            color: var(--muted);
            text-align: center;
            padding: 40px;
        }

        .empty-state-icon {
            width: 56px; height: 56px;
            background: var(--red-light);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 8px;
        }

        .empty-state h3 {
            font-family: 'DM Serif Display', serif;
            font-size: 20px;
            color: var(--ink);
        }

        .empty-state p { font-size: 13px; max-width: 300px; line-height: 1.6; }

        .suggestion-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            margin-top: 8px;
        }

        .chip {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 7px 14px;
            font-size: 12px;
            color: var(--ink);
            cursor: pointer;
            transition: all .2s;
            font-family: 'DM Sans', sans-serif;
        }

        .chip:hover { background: var(--red-light); border-color: var(--red-mid); color: var(--red); }

        .input-area {
            padding: 20px 32px 28px;
            background: var(--white);
            border-top: 1px solid var(--border);
        }

        .input-wrap {
            display: flex;
            gap: 10px;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 16px;
            padding: 10px 10px 10px 18px;
            transition: border-color .2s;
        }

        .input-wrap:focus-within { border-color: var(--red); }

        #msg-input {
            flex: 1;
            background: none;
            border: none;
            outline: none;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--ink);
            resize: none;
            max-height: 120px;
            line-height: 1.6;
        }

        #msg-input::placeholder { color: var(--muted); }

        .send-btn {
            width: 40px; height: 40px;
            background: var(--red);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: background .2s, transform .1s;
            align-self: flex-end;
        }

        .send-btn:hover { background: #a93226; }
        .send-btn:active { transform: scale(.95); }
        .send-btn:disabled { background: var(--red-mid); cursor: not-allowed; }
        .send-btn svg { width: 16px; height: 16px; fill: white; }

        .input-hint {
            font-size: 11px;
            color: var(--muted);
            margin-top: 8px;
            text-align: center;
        }

        @media (max-width: 700px) {
            .layout { grid-template-columns: 1fr; }
            .sidebar { display: none; }
            .messages { padding: 20px; }
            .input-area { padding: 16px 20px 20px; }
            .msg-bubble { max-width: 85%; }
        }
    </style>
</head>
<body>

<div class="layout">

    <aside class="sidebar">
        <div class="logo">
            <div class="logo-icon">
                <svg viewBox="0 0 24 24"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
            </div>
            <span class="logo-name">Life<span>Link</span></span>
        </div>

        <p class="sidebar-label">Your Guide</p>

        <div class="grace-card">
            <div class="grace-avatar">G</div>
            <div class="grace-name">Grace</div>
            <div class="grace-desc">
                <span class="status-dot"></span>Online now<br>
                Official LifeLink AI assistant. Here to guide you through your blood donation journey.
            </div>
        </div>

        <p class="sidebar-label">Quick topics</p>

        <ul class="info-list">
            <li><strong>Blood Compatibility</strong>Who can donate to whom?</li>
            <li><strong>Eligibility</strong>Am I able to donate?</li>
            <li><strong>The Process</strong>What happens during donation?</li>
            <li><strong>Before &amp; After</strong>How to prepare and recover</li>
        </ul>

        <button class="clear-btn" onclick="clearChat()">Clear conversation</button>
    </aside>

    <div class="chat-area">

        <div class="chat-header">
            <div class="msg-avatar grace-av">G</div>
            <div>
                <div class="chat-header-title">Grace</div>
                <div class="chat-header-sub"><span class="status-dot"></span>LifeLink AI Guide · Always here to help</div>
            </div>
        </div>

        <div class="messages" id="messages">

            <div class="empty-state" id="empty-state">
                <div class="empty-state-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="#c0392b">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    </svg>
                </div>
                <h3>Hello, I'm Grace</h3>
                <p>Your LifeLink guide. Ask me anything about blood donation, eligibility, or how LifeLink works.</p>
                <div class="suggestion-chips">
                    <button class="chip" onclick="sendSuggestion('Am I eligible to donate blood?')">Am I eligible?</button>
                    <button class="chip" onclick="sendSuggestion('What is the donation process like?')">What\'s the process?</button>
                    <button class="chip" onclick="sendSuggestion('What blood type is the universal donor?')">Universal donor?</button>
                    <button class="chip" onclick="sendSuggestion('How does LifeLink work?')">How does LifeLink work?</button>
                </div>
            </div>

            <div class="typing-indicator" id="typing">
                <div class="msg-avatar grace-av">G</div>
                <div class="typing-bubble">
                    <span></span><span></span><span></span>
                </div>
            </div>

        </div>

        <div class="input-area">
            <div class="input-wrap">
                <textarea
                    id="msg-input"
                    rows="1"
                    placeholder="Ask Grace anything about blood donation..."
                    autocomplete="off"
                ></textarea>
                <button class="send-btn" id="send-btn" onclick="sendMessage()">
                    <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                </button>
            </div>
            <p class="input-hint">Press Enter to send &middot; Shift+Enter for new line</p>
        </div>

    </div>
</div>

<script>
    const input    = document.getElementById('msg-input');
    const messages = document.getElementById('messages');
    const typing   = document.getElementById('typing');
    const sendBtn  = document.getElementById('send-btn');
    const csrf     = document.querySelector('meta[name="csrf-token"]').content;

    // Restore history from localStorage
    const STORAGE_KEY = 'grace_chat_history';
    let history = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');

    // Render saved history on page load
    if (history.length > 0) {
        document.getElementById('empty-state')?.remove();
        history.forEach(m => renderMessage(m.role, m.text, false));
    }

    // Auto-grow textarea
    input.addEventListener('input', () => {
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 120) + 'px';
    });

    // Enter to send
    input.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    function sendSuggestion(text) {
        input.value = text;
        sendMessage();
    }

    function renderMessage(role, text, animate = true) {
        const now = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const row = document.createElement('div');
        row.className = `msg-row ${role}` + (animate ? '' : ' no-anim');
        if (!animate) row.style.animation = 'none';
        row.innerHTML = `
            <div class="msg-avatar ${role === 'user' ? 'user-av' : 'grace-av'}">${role === 'user' ? 'You' : 'G'}</div>
            <div class="msg-col">
                <div class="msg-bubble">${escapeHtml(text)}</div>
                <span class="msg-time">${now}</span>
            </div>`;
        messages.insertBefore(row, typing);
        scrollBottom();
    }

    function escapeHtml(str) {
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\n/g, '<br>');
    }

    function scrollBottom() {
        messages.scrollTop = messages.scrollHeight;
    }

    function setLoading(on) {
        sendBtn.disabled = on;
        typing.classList.toggle('visible', on);
        if (on) scrollBottom();
    }

    function saveHistory(role, text) {
        history.push({ role, text });
        // keep last 20 messages
        if (history.length > 20) history = history.slice(-20);
        localStorage.setItem(STORAGE_KEY, JSON.stringify(history));
    }

    function clearChat() {
        history = [];
        localStorage.removeItem(STORAGE_KEY);
        // remove all message rows
        document.querySelectorAll('.msg-row').forEach(el => el.remove());
        // bring back empty state
        if (!document.getElementById('empty-state')) {
            const es = document.createElement('div');
            es.id = 'empty-state';
            es.className = 'empty-state';
            es.innerHTML = `
                <div class="empty-state-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="#c0392b">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    </svg>
                </div>
                <h3>Hello, I'm Grace</h3>
                <p>Your LifeLink guide. Ask me anything about blood donation, eligibility, or how LifeLink works.</p>
                <div class="suggestion-chips">
                    <button class="chip" onclick="sendSuggestion('Am I eligible to donate blood?')">Am I eligible?</button>
                    <button class="chip" onclick="sendSuggestion('What is the donation process like?')">What's the process?</button>
                    <button class="chip" onclick="sendSuggestion('What blood type is the universal donor?')">Universal donor?</button>
                    <button class="chip" onclick="sendSuggestion('How does LifeLink work?')">How does LifeLink work?</button>
                </div>`;
            messages.insertBefore(es, typing);
        }
    }

    async function sendMessage() {
        const text = input.value.trim();
        if (!text || sendBtn.disabled) return;

        document.getElementById('empty-state')?.remove();
        renderMessage('user', text);
        saveHistory('user', text);
        input.value = '';
        input.style.height = 'auto';
        setLoading(true);

        try {
            const res = await fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message: text }),
            });

            const data = await res.json();
            const reply = data.reply || "I'm sorry, something went wrong. Please try again.";
            renderMessage('grace', reply);
            saveHistory('grace', reply);

        } catch {
            const err = "I'm having trouble connecting right now. Please try again in a moment.";
            renderMessage('grace', err);
        } finally {
            setLoading(false);
            input.focus();
        }
    }

    scrollBottom();
</script>

</body>
</html>

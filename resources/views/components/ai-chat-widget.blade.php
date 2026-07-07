@props([
    'context' => 'landing',      // landing | customer
    'endpoint' => null,
    'position' => 'bottom-right', // bottom-right | bottom-left
    'greeting' => null,
    'placeholder' => null,
    'title' => null,
])

@php
$resolvedEndpoint = $endpoint ?? ($context === 'customer' ? route('ai.chat.customer') : route('ai.chat.landing'));
$resolvedTitle = $title ?? ($context === 'customer' ? __('Your AI Assistant') : __('Nutrio AI Assistant'));
$resolvedGreeting = $greeting ?? ($context === 'customer'
    ? __('Hi! I\'m your personal Nutrio AI. Ask me about your meals, plan, or progress.')
    : __('Hi! I\'m Nutrio AI. How can I help you today?'));
$resolvedPlaceholder = $placeholder ?? ($context === 'customer'
    ? __('Ask about your meals, delivery, or nutrition...')
    : __('Ask me about meals, plans, nutrition...'));
@endphp

@php
$wrapperId = 'ai-chat-' . uniqid();
$positionClass = $position === 'bottom-left' ? 'start-4' : 'end-4';
@endphp

<div id="{{ $wrapperId }}" class="ai-chat-widget position-fixed {{ $positionClass }} bottom-4 z-50" data-endpoint="{{ $resolvedEndpoint }}" data-context="{{ $context }}" style="max-width: 380px; width: 100%;">
    {{-- Toggle Button --}}
    <button type="button" class="ai-chat-toggle btn btn-primary rounded-circle shadow-lg d-flex align-items-center justify-content-center"
            style="width: 56px; height: 56px; background: linear-gradient(135deg, #259B00, #1a7a00); border: none;"
            aria-label="Open AI chat">
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-robot" viewBox="0 0 16 16">
            <path d="M6 12.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5ZM3 8.062C3 6.076 4.529 4.558 6.5 4.558a.5.5 0 0 1 .5.5v6.984a.5.5 0 0 1-.5.5C4.529 12.542 3 11.024 3 9.062Zm1 0c0 1.367 1.062 2.488 2.5 2.488V5.575C5.062 5.575 4 6.696 4 8.062Zm5-2.504a.5.5 0 0 1 .5-.5c1.971 0 3.5 1.518 3.5 3.5s-1.529 3.5-3.5 3.5a.5.5 0 0 1-.5-.5V5.558Zm1 .5v4.976c1.438 0 2.5-1.121 2.5-2.488S11.438 6.058 10 6.058ZM9.5 3a.5.5 0 0 1 .5.5v.5a.5.5 0 0 1-1 0v-.5a.5.5 0 0 1 .5-.5Zm-3 0a.5.5 0 0 1 .5.5v.5a.5.5 0 0 1-1 0v-.5a.5.5 0 0 1 .5-.5ZM12.5 16a.5.5 0 0 1-.5-.5v-1.5h-7V15.5a.5.5 0 0 1-1 0v-2a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5Z"/>
        </svg>
    </button>

    {{-- Chat Window --}}
    <div class="ai-chat-window card shadow-xl border-0 d-none" style="border-radius: 1rem; overflow: hidden;">
        <div class="card-header text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #259B00, #1a7a00);">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-white/20 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-stars" viewBox="0 0 16 16">
                        <path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.829l.645-1.936zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.734 1.734 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.734 1.734 0 0 0 3.794 1.148z"/>
                        <path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.087-.15 1.658-.462l-.14-.24c-.353.205-.696.34-.997.34-1.18 0-1.57-.906-.95-2.654l.742-3.49c.08-.348.111-.542.084-.625a.37.37 0 0 0-.293-.288L8.93 6.588z"/>
                    </svg>
                </div>
                <div>
                    <h6 class="mb-0 fw-semibold small">{{ $resolvedTitle }}</h6>
                    <small class="text-white/75" style="font-size: 10px;">Online</small>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white ai-chat-close" aria-label="Close chat"></button>
        </div>

        <div class="card-body p-3 ai-chat-messages" style="height: 320px; overflow-y: auto; background: #f8fafc;">
            <div class="ai-message ai-message-bot mb-3 d-flex gap-2">
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 12px;">
                    AI
                </div>
                <div class="bg-white border rounded-3 rounded-top-start-0 p-2 shadow-sm" style="max-width: 85%; font-size: 13px;">
                    {{ $resolvedGreeting }}
                </div>
            </div>
        </div>

        <div class="card-footer bg-white border-top-0 p-3">
            <form class="ai-chat-form d-flex gap-2">
                <input type="text" name="message" class="form-control form-control-sm ai-chat-input"
                       placeholder="{{ $resolvedPlaceholder }}" autocomplete="off" required>
                <button type="submit" class="btn btn-success btn-sm d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #259B00, #1a7a00); border: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-fill" viewBox="0 0 16 16">
                        <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 1.59 2.498A.5.5 0 0 0 7 12.5v-3.768l6.546-2.969a.5.5 0 0 0 .063-.768l-6.545-2.97v-3.77l5.48 8.605Z"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

@pushOnce('scripts')
<script>
    (function () {
        const widgets = document.querySelectorAll('.ai-chat-widget');

        widgets.forEach(widget => {
            const toggle = widget.querySelector('.ai-chat-toggle');
            const close = widget.querySelector('.ai-chat-close');
            const windowEl = widget.querySelector('.ai-chat-window');
            const form = widget.querySelector('.ai-chat-form');
            const input = widget.querySelector('.ai-chat-input');
            const messages = widget.querySelector('.ai-chat-messages');
            const endpoint = widget.dataset.endpoint || '/ai/chat';

            function scrollToBottom() {
                messages.scrollTop = messages.scrollHeight;
            }

            function appendMessage(text, sender) {
                const isBot = sender === 'bot';
                const div = document.createElement('div');
                div.className = `ai-message mb-3 d-flex gap-2 ${isBot ? '' : 'justify-content-end'}`;
                div.innerHTML = isBot
                    ? `<div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 12px;">AI</div>
                       <div class="bg-white border rounded-3 rounded-top-start-0 p-2 shadow-sm" style="max-width: 85%; font-size: 13px;">${escapeHtml(text)}</div>`
                    : `<div class="bg-success text-white rounded-3 rounded-top-end-0 p-2 shadow-sm" style="max-width: 85%; font-size: 13px;">${escapeHtml(text)}</div>`;
                messages.appendChild(div);
                scrollToBottom();
            }

            function appendTyping() {
                const id = 'typing-' + Date.now();
                const div = document.createElement('div');
                div.id = id;
                div.className = 'ai-message ai-message-typing mb-3 d-flex gap-2';
                div.innerHTML = `<div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 12px;">AI</div>
                                 <div class="bg-white border rounded-3 rounded-top-start-0 p-2 shadow-sm d-flex align-items-center gap-1" style="width: 60px;">
                                     <span class="spinner-grow spinner-grow-sm text-muted" style="animation-delay: 0s;"></span>
                                     <span class="spinner-grow spinner-grow-sm text-muted" style="animation-delay: .2s;"></span>
                                     <span class="spinner-grow spinner-grow-sm text-muted" style="animation-delay: .4s;"></span>
                                 </div>`;
                messages.appendChild(div);
                scrollToBottom();
                return id;
            }

            function removeTyping(id) {
                const el = document.getElementById(id);
                if (el) el.remove();
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            toggle.addEventListener('click', () => {
                windowEl.classList.remove('d-none');
                toggle.classList.add('d-none');
                input.focus();
            });

            close.addEventListener('click', () => {
                windowEl.classList.add('d-none');
                toggle.classList.remove('d-none');
            });

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const text = input.value.trim();
                if (!text) return;

                appendMessage(text, 'user');
                input.value = '';

                const typingId = appendTyping();

                try {
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        },
                        body: JSON.stringify({ message: text }),
                    });

                    const result = await response.json();
                    removeTyping(typingId);

                    if (!response.ok || result.error) {
                        appendMessage(result.message || 'Sorry, I could not process that. Please try again.', 'bot');
                        return;
                    }

                    appendMessage(result.reply || 'Here is what I found.', 'bot');
                } catch (err) {
                    removeTyping(typingId);
                    appendMessage('Sorry, I\'m having trouble connecting right now. Please try again later.', 'bot');
                }
            });
        });
    })();
</script>
@endPushOnce

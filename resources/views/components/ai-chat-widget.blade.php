@props([
    'context' => 'landing',
    'endpoint' => null,
    'position' => 'bottom-right',
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
$wrapperId = 'ai-chat-' . uniqid();
$positionClass = $position === 'bottom-left' ? 'left-4' : 'right-4';
@endphp

<div id="{{ $wrapperId }}" class="ai-chat-widget fixed {{ $positionClass }} bottom-4 z-50 w-full max-w-sm" data-endpoint="{{ $resolvedEndpoint }}" data-context="{{ $context }}">
    {{-- Toggle Button --}}
    <button type="button" class="ai-chat-toggle w-14 h-14 rounded-full bg-gradient-to-br from-green-600 to-green-700 text-white shadow-2xl flex items-center justify-center hover:scale-105 active:scale-95 transition-transform duration-200 ml-auto"
            aria-label="{{ __('Open AI chat') }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="currentColor" viewBox="0 0 16 16">
            <path d="M6 12.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5ZM3 8.062C3 6.076 4.529 4.558 6.5 4.558a.5.5 0 0 1 .5.5v6.984a.5.5 0 0 1-.5.5C4.529 12.542 3 11.024 3 9.062Zm1 0c0 1.367 1.062 2.488 2.5 2.488V5.575C5.062 5.575 4 6.696 4 8.062Zm5-2.504a.5.5 0 0 1 .5-.5c1.971 0 3.5 1.518 3.5 3.5s-1.529 3.5-3.5 3.5a.5.5 0 0 1-.5-.5V5.558Zm1 .5v4.976c1.438 0 2.5-1.121 2.5-2.488S11.438 6.058 10 6.058ZM9.5 3a.5.5 0 0 1 .5.5v.5a.5.5 0 0 1-1 0v-.5a.5.5 0 0 1 .5-.5Zm-3 0a.5.5 0 0 1 .5.5v.5a.5.5 0 0 1-1 0v-.5a.5.5 0 0 1 .5-.5ZM12.5 16a.5.5 0 0 1-.5-.5v-1.5h-7V15.5a.5.5 0 0 1-1 0v-2a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5Z"/>
        </svg>
    </button>

    {{-- Chat Window --}}
    <div class="ai-chat-window hidden absolute bottom-16 {{ $position === 'bottom-left' ? 'left-0' : 'right-0' }} w-full bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col" style="height: 420px;">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-green-600 to-green-700 text-white p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.829l.645-1.936zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.734 1.734 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.734 1.734 0 0 0 3.794 1.148z"/>
                    </svg>
                </div>
                <div>
                    <h6 class="font-semibold text-sm">{{ $resolvedTitle }}</h6>
                    <p class="text-xs text-white/80">{{ __('Online') }}</p>
                </div>
            </div>
            <button type="button" class="ai-chat-close w-8 h-8 rounded-full hover:bg-white/20 flex items-center justify-center transition-colors" aria-label="{{ __('Close chat') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Messages --}}
        <div class="ai-chat-messages flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50 dark:bg-gray-900">
            <div class="ai-message flex gap-2">
                <div class="w-8 h-8 rounded-full bg-green-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">AI</div>
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl rounded-tl-none p-3 shadow-sm text-sm text-gray-700 dark:text-gray-200 max-w-[80%]">
                    {{ $resolvedGreeting }}
                </div>
            </div>
        </div>

        {{-- Input --}}
        <form class="ai-chat-form p-3 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 flex gap-2">
            <input type="text" name="message" class="ai-chat-input flex-1 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                   placeholder="{{ $resolvedPlaceholder }}" autocomplete="off" required>
            <button type="submit" class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-600 to-green-700 text-white flex items-center justify-center hover:shadow-lg active:scale-95 transition-all" aria-label="{{ __('Send message') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 1.59 2.498A.5.5 0 0 0 7 12.5v-3.768l6.546-2.969a.5.5 0 0 0 .063-.768l-6.545-2.97v-3.77l5.48 8.605Z"/>
                </svg>
            </button>
        </form>
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
                div.className = `ai-message flex gap-2 ${isBot ? '' : 'justify-end'}`;
                div.innerHTML = isBot
                    ? `<div class="w-8 h-8 rounded-full bg-green-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">AI</div>
                       <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl rounded-tl-none p-3 shadow-sm text-sm text-gray-700 dark:text-gray-200 max-w-[80%]">${escapeHtml(text)}</div>`
                    : `<div class="bg-gradient-to-br from-green-600 to-green-700 text-white rounded-2xl rounded-tr-none p-3 shadow-sm text-sm max-w-[80%]">${escapeHtml(text)}</div>`;
                messages.appendChild(div);
                scrollToBottom();
            }

            function appendTyping() {
                const id = 'typing-' + Date.now();
                const div = document.createElement('div');
                div.id = id;
                div.className = 'ai-message flex gap-2';
                div.innerHTML = `<div class="w-8 h-8 rounded-full bg-green-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">AI</div>
                                 <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl rounded-tl-none p-3 shadow-sm flex items-center gap-1 w-16">
                                     <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0s;"></span>
                                     <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: .15s;"></span>
                                     <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: .3s;"></span>
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
                windowEl.classList.remove('hidden');
                toggle.classList.add('hidden');
                input.focus();
            });

            close.addEventListener('click', () => {
                windowEl.classList.add('hidden');
                toggle.classList.remove('hidden');
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

                    if (!response.ok || result.success === false) {
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

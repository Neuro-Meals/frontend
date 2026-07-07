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
$resolvedPlaceholder = $placeholder ?? __('Ask here...');
$wrapperId = 'ai-chat-' . uniqid();
$positionClass = $position === 'bottom-left' ? 'left-4' : 'right-4';
$logoUrl = asset('whitelogo.png');
@endphp

<style>
    @keyframes ai-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }
    @keyframes ai-pulse-ring {
        0% { transform: scale(0.8); opacity: 0.5; }
        100% { transform: scale(1.4); opacity: 0; }
    }
    .ai-chat-float { animation: ai-float 3s ease-in-out infinite; }
    .ai-chat-pulse::before {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        background: rgba(110, 122, 37, 0.4);
        animation: ai-pulse-ring 2s ease-out infinite;
        z-index: -1;
    }
</style>

<div id="{{ $wrapperId }}" class="ai-chat-widget fixed {{ $positionClass }} bottom-4 z-50 w-full max-w-sm font-sans" data-endpoint="{{ $resolvedEndpoint }}" data-context="{{ $context }}">
    {{-- Toggle Button --}}
    <button type="button" class="ai-chat-toggle relative w-14 h-14 rounded-full bg-gradient-to-br from-[#173327] to-[#6E7A25] text-white shadow-2xl shadow-[#6E7A25]/40 flex items-center justify-center hover:scale-110 active:scale-95 transition-all duration-300 ml-auto ai-chat-float ai-chat-pulse"
            aria-label="{{ __('Open AI chat') }}">
        <img src="{{ $logoUrl }}" alt="Nutrio AI" class="w-9 h-9 object-contain">
    </button>

    {{-- Chat Window --}}
    <div class="ai-chat-window hidden absolute bottom-16 {{ $position === 'bottom-left' ? 'left-0' : 'right-0' }} w-full bg-white dark:bg-gray-900 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col origin-bottom-right" style="height: 460px;">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white p-4 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur flex items-center justify-center border border-white/20">
                    <img src="{{ $logoUrl }}" alt="Nutrio AI" class="w-7 h-7 object-contain">
                </div>
                <div>
                    <h6 class="font-bold text-sm">{{ $resolvedTitle }}</h6>
                    <div class="flex items-center gap-1.5 text-xs text-white/80">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        {{ __('Online') }}
                    </div>
                </div>
            </div>
            <button type="button" class="ai-chat-close w-8 h-8 rounded-full hover:bg-white/20 flex items-center justify-center transition-colors" aria-label="{{ __('Close chat') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Messages --}}
        <div class="ai-chat-messages flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-gray-900/50">
            <div class="ai-message flex gap-2.5">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center flex-shrink-0 border border-white/10 shadow-sm">
                    <img src="{{ $logoUrl }}" alt="AI" class="w-5 h-5 object-contain">
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl rounded-tl-none p-3 shadow-sm text-sm text-gray-700 dark:text-gray-200 max-w-[82%]">
                    {{ $resolvedGreeting }}
                </div>
            </div>
        </div>

        {{-- Input --}}
        <form class="ai-chat-form p-3 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700 flex gap-2 shrink-0">
            <div class="flex-1 relative">
                <input type="text" name="message" class="ai-chat-input w-full pl-4 pr-10 py-3 rounded-2xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-sm text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#6E7A25]/50 focus:border-[#6E7A25] transition-all"
                       placeholder="{{ $resolvedPlaceholder }}" autocomplete="off" required>
            </div>
            <button type="submit" class="w-11 h-11 rounded-2xl bg-gradient-to-br from-[#173327] to-[#6E7A25] text-white flex items-center justify-center shadow-lg shadow-[#6E7A25]/25 hover:shadow-xl hover:scale-105 active:scale-95 transition-all shrink-0" aria-label="{{ __('Send message') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
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
            const logoUrl = '{{ $logoUrl }}';

            function scrollToBottom() {
                messages.scrollTop = messages.scrollHeight;
            }

            function botAvatar() {
                return `<div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center flex-shrink-0 border border-white/10 shadow-sm">
                            <img src="${logoUrl}" alt="AI" class="w-5 h-5 object-contain">
                        </div>`;
            }

            function appendMessage(text, sender) {
                const isBot = sender === 'bot';
                const div = document.createElement('div');
                div.className = `ai-message flex gap-2.5 ${isBot ? '' : 'justify-end'}`;
                div.innerHTML = isBot
                    ? `${botAvatar()}
                       <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl rounded-tl-none p-3 shadow-sm text-sm text-gray-700 dark:text-gray-200 max-w-[82%]">${escapeHtml(text)}</div>`
                    : `<div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] text-white rounded-2xl rounded-tr-none p-3 shadow-sm text-sm max-w-[82%]">${escapeHtml(text)}</div>`;
                messages.appendChild(div);
                scrollToBottom();
            }

            function appendTyping() {
                const id = 'typing-' + Date.now();
                const div = document.createElement('div');
                div.id = id;
                div.className = 'ai-message flex gap-2.5';
                div.innerHTML = `${botAvatar()}
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
                windowEl.classList.add('animate-fade-in-up');
                toggle.classList.add('hidden');
                input.focus();
            });

            close.addEventListener('click', () => {
                windowEl.classList.add('hidden');
                windowEl.classList.remove('animate-fade-in-up');
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

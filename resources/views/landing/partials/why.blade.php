<section class="py-20 lg:py-28 bg-gray-50 dark:bg-gray-950 transition-colors duration-300 relative overflow-hidden">
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-light/30 to-transparent"></div>
    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-light/30 to-transparent"></div>

    {{-- Decorative background circles --}}
    <div class="absolute top-1/4 -left-32 w-64 h-64 rounded-full bg-brand-light/5 blur-3xl"></div>
    <div class="absolute bottom-1/4 -right-32 w-64 h-64 rounded-full bg-brand-light/5 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center max-w-3xl mx-auto mb-16 scroll-reveal">
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-light/10 text-brand-light text-xs font-bold uppercase tracking-wider mb-4">{{ __('Why Choose Us') }}</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">{{ __('Why Choose Nutrio Meals?') }}</h2>
            <p class="text-gray-600 dark:text-gray-300 text-lg">{{ __('We combine nutrition science with chef-crafted meals to help you reach your fitness goals.') }}</p>
        </div>

        @php
            $cards = [
                [
                    'img' => '236.jpg',
                    'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                    'title' => __('Personalized Nutrition'),
                    'text' => __('Every meal is crafted based on your fitness goals, dietary preferences, and body composition. Our system calculates your exact needs and builds a plan just for you.'),
                    'stage' => '01',
                    'is_fi' => false,
                ],
                [
                    'img' => '2147782465.jpg',
                    'icon' => 'M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z',
                    'title' => __('Macro Tracking'),
                    'text' => __('Track calories, proteins, carbs, and fats with precision. Our dashboard gives you real-time insights into your daily nutrition intake and progress.'),
                    'stage' => '02',
                    'is_fi' => false,
                ],
                [
                    'img' => '2149722284.jpg',
                    'icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0',
                    'title' => __('Fresh Daily Delivery'),
                    'text' => __('Meals prepared fresh every morning and delivered to your doorstep on schedule. Never worry about meal prep again — we handle everything.'),
                    'stage' => '03',
                    'is_fi' => false,
                ],
                [
                    'img' => '236.jpg',
                    'icon' => 'fi fi-sr-diamond',
                    'title' => __('Premium Quality'),
                    'text' => __('Chef-prepared meals using only the finest ingredients. Locally sourced produce, premium proteins, and no artificial additives ever.'),
                    'stage' => '04',
                    'is_fi' => true,
                ],
            ];
        @endphp

        {{-- Stages layout - alternating left/right --}}
        <div class="space-y-8 lg:space-y-12">
            @foreach ($cards as $index => $card)
                <div class="why-card scroll-reveal relative" style="transition-delay: {{ $index * 150 }}ms;">
                    <div class="flex flex-col {{ $index % 2 === 0 ? 'lg:flex-row' : 'lg:flex-row-reverse' }} items-center gap-6 lg:gap-12">
                        {{-- Image --}}
                        <div class="w-full lg:w-1/2 relative group">
                            <div class="relative rounded-3xl overflow-hidden shadow-xl {{ $index % 2 === 0 ? 'lg:rounded-r-[4rem]' : 'lg:rounded-l-[4rem]' }}">
                                <img src="{{ asset('images/' . $card['img']) }}" alt="{{ $card['title'] }}" class="w-full h-64 sm:h-80 lg:h-96 object-cover transition-transform duration-700 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                                {{-- Stage number badge --}}
                                <div class="absolute top-4 {{ $index % 2 === 0 ? 'right-4' : 'left-4' }} w-14 h-14 rounded-2xl bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm flex items-center justify-center shadow-lg">
                                    <span class="text-2xl font-extrabold bg-gradient-to-br from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ $card['stage'] }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="w-full lg:w-1/2 {{ $index % 2 === 0 ? 'lg:pl-4' : 'lg:pr-4 lg:text-right' }}">
                            {{-- Icon --}}
                            <div class="why-icon-wrap w-20 h-20 rounded-full bg-gradient-to-br from-[#173327] to-[#6E7A25] text-white flex items-center justify-center shadow-xl mb-5 {{ $index % 2 === 1 ? 'lg:ml-auto' : '' }}">
                                <div class="why-icon-inner w-16 h-16 rounded-full bg-white/10 flex items-center justify-center">
                                    @if ($card['is_fi'])
                                        <i class="{{ $card['icon'] }} text-3xl text-white"></i>
                                    @else
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $card['icon'] }}"></path></svg>
                                    @endif
                                </div>
                            </div>

                            {{-- Title --}}
                            <h3 class="text-2xl lg:text-3xl font-extrabold text-gray-900 dark:text-white mb-3">{{ $card['title'] }}</h3>

                            {{-- Text --}}
                            <p class="text-gray-600 dark:text-gray-400 text-base lg:text-lg leading-relaxed">{{ $card['text'] }}</p>

                            {{-- Learn more link --}}
                            <a href="#plans" class="inline-flex items-center gap-2 mt-5 text-brand-light font-bold text-sm hover:gap-3 transition-all {{ $index % 2 === 1 ? 'lg:flex-row-reverse' : '' }}">
                                {{ __('Learn more') }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>

                    {{-- Connecting line between stages --}}
                    @if (!$loop->last)
                        <div class="hidden lg:flex justify-center mt-8 lg:mt-12">
                            <div class="w-px h-12 bg-gradient-to-b from-brand-light/30 to-brand-light/10"></div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    @keyframes iconRoll {
        0% { transform: rotate(0deg) scale(1); }
        25% { transform: rotate(180deg) scale(1.15); }
        50% { transform: rotate(360deg) scale(1); }
        75% { transform: rotate(540deg) scale(1.15); }
        100% { transform: rotate(720deg) scale(1); }
    }
    @keyframes iconPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(110,122,37, 0.4); }
        50% { box-shadow: 0 0 0 12px rgba(110,122,37, 0); }
    }
    .why-card.is-visible .why-icon-wrap {
        animation: iconRoll 1.5s ease-in-out, iconPulse 2s ease-in-out 1.5s;
    }
    .why-card.is-visible .why-icon-inner {
        animation: iconRoll 1.5s ease-in-out reverse;
    }
    .why-icon-wrap {
        transition: transform 0.3s ease;
    }
    .why-icon-wrap:hover {
        transform: scale(1.1);
    }
</style>

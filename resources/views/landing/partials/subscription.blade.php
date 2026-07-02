<section class="py-20 lg:py-28 bg-white dark:bg-gray-900 transition-colors duration-300 relative overflow-hidden">
    {{-- Decorative glows --}}
    <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full bg-brand-light/5 blur-3xl"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 rounded-full bg-[#173327]/5 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        {{-- Header --}}
        <div class="text-center max-w-3xl mx-auto mb-14 scroll-reveal">
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-light/10 text-brand-light text-xs font-bold uppercase tracking-wider mb-4">{{ __('Flexibility') }}</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">{{ __('Flexible Subscription') }}</h2>
            <p class="text-gray-600 dark:text-gray-300 text-lg">{{ __('You are in control. Pause, skip, upgrade, or cancel anytime.') }}</p>
        </div>

        {{-- Feature cards --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-5">
            @php
                $features = [
                    ['title' => __('Pause Anytime'), 'desc' => __('Going on a trip? Pause your plan with one click.'), 'icon' => 'M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['title' => __('Skip Meals'), 'desc' => __('Not feeling hungry? Skip a day, no charges.'), 'icon' => 'M13 5l7 7-7 7M5 5l7 7-7 7'],
                    ['title' => __('Upgrade Plan'), 'desc' => __('Need more meals? Upgrade your plan instantly.'), 'icon' => 'M3 17l6-6 4 4 8-8M14 7h7v7'],
                    ['title' => __('Cancel Anytime'), 'desc' => __('No lock-in contracts. Cancel whenever you want.'), 'icon' => 'M6 18L18 6M6 6l12 12'],
                    ['title' => __('Family Plans'), 'desc' => __('Add family members and share your meals.'), 'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a3 3 0 10-3-3 3 3 0 003 3z'],
                ];
            @endphp

            @foreach ($features as $index => $feature)
                <div class="sub-card scroll-reveal scroll-reveal-delay-{{ ($index % 5) + 1 }} group relative p-6 rounded-2xl bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 border border-gray-100 dark:border-gray-700 hover:border-brand-light/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    {{-- Hover gradient glow --}}
                    <div class="absolute inset-0 bg-gradient-to-br from-brand-light/0 to-brand-light/0 group-hover:from-brand-light/5 group-hover:to-transparent transition-all duration-500"></div>

                    <div class="relative">
                        {{-- Icon --}}
                        <div class="sub-icon-wrap w-14 h-14 mx-auto rounded-full bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center shadow-lg mb-4 group-hover:scale-110 transition-transform duration-300">
                            <div class="sub-icon-inner w-11 h-11 rounded-full bg-white/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"></path></svg>
                            </div>
                        </div>
                        {{-- Text --}}
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-2 text-center">{{ $feature['title'] }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- CTA bar --}}
        <div class="mt-12 scroll-reveal text-center">
            <div class="inline-flex flex-col sm:flex-row items-center gap-4 px-8 py-5 rounded-2xl bg-gradient-to-r from-[#173327] to-[#6E7A25] shadow-xl">
                <p class="text-white font-bold text-sm sm:text-base">{{ __('Ready to start your flexible meal plan?') }}</p>
                <a href="#plans" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-[#173327] text-sm font-bold hover:bg-gray-100 hover:-translate-y-0.5 transition-all">
                    {{ __('Get Started') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    @keyframes subIconPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(110,122,37, 0.3); }
        50% { box-shadow: 0 0 0 10px rgba(110,122,37, 0); }
    }
    .sub-card.is-visible .sub-icon-wrap {
        animation: subIconPulse 2s ease-in-out;
    }
    .sub-card.is-visible .sub-icon-inner {
        animation: subIconRoll 1s ease-in-out;
    }
    @keyframes subIconRoll {
        0% { transform: rotate(0deg) scale(0.8); }
        50% { transform: rotate(180deg) scale(1.1); }
        100% { transform: rotate(360deg) scale(1); }
    }
</style>

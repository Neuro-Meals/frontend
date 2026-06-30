<section class="py-20 bg-gray-50 dark:bg-gray-800/50 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 scroll-reveal">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-4">Why Choose Nutrio Meals?</h2>
            <p class="text-gray-600 dark:text-gray-300">We combine nutrition science with chef-crafted meals to help you reach your fitness goals.</p>
        </div>

        <div class="max-w-3xl mx-auto">
            @php
                $steps = [
                    ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'title' => 'Personalized Nutrition', 'text' => 'Meals based on your fitness goals'],
                    ['icon' => 'M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z', 'title' => 'Macro Tracking', 'text' => 'Track calories, proteins and nutrients'],
                    ['icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0', 'title' => 'Fresh Daily Delivery', 'text' => 'Healthy meals delivered on schedule'],
                    ['icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z', 'title' => 'Premium Quality', 'text' => 'Chef-prepared fitness meals'],
                ];
            @endphp

            @foreach ($steps as $index => $step)
                <div class="scroll-reveal scroll-reveal-delay-{{ ($index % 4) + 1 }} relative group flex gap-6 mb-8 last:mb-0 p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 hover:border-brand-light dark:hover:border-brand-light hover:shadow-lg hover:bg-brand-light/5 dark:hover:bg-brand-light/10 transition-all duration-300">
                    {{-- Connector line --}}
                    @if (!$loop->last)
                        <div class="absolute left-[3.25rem] top-20 bottom-[-2rem] w-0.5 bg-gray-200 dark:bg-gray-700 group-hover:bg-brand-light/30 transition-colors"></div>
                    @endif

                    {{-- Step icon --}}
                    <div class="relative z-10 flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-light to-brand-dark text-white flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $step['icon'] }}"/></svg>
                    </div>

                    {{-- Step content --}}
                    <div class="flex-1 pt-1">
                        <div class="flex items-center gap-3 mb-1">
                            <span class="text-xs font-bold text-brand-light uppercase tracking-wider">Step 0{{ $index + 1 }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-brand-light transition-colors duration-300 mb-1">{{ $step['title'] }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm group-hover:text-gray-700 dark:group-hover:text-gray-300 transition-colors">{{ $step['text'] }}</p>
                    </div>

                    {{-- Arrow --}}
                    <div class="hidden sm:flex items-center self-center opacity-0 group-hover:opacity-100 transform translate-x-[-10px] group-hover:translate-x-0 transition-all duration-300">
                        <svg class="w-6 h-6 text-brand-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

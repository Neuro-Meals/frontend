<section id="features" class="relative py-20 lg:py-28 bg-gradient-to-b from-[#F6F3E9] to-white dark:from-gray-900 dark:to-gray-800 transition-colors duration-300 overflow-hidden">
    {{-- Decorative blobs --}}
    <div class="absolute top-20 -left-20 w-72 h-72 bg-[#949B50]/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 -right-20 w-96 h-96 bg-[#6E7A25]/10 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        {{-- Header --}}
        <div class="text-center mb-16 scroll-reveal">
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-light/10 text-brand-light text-xs font-bold uppercase tracking-wider mb-4">{{ __('Why Choose Us') }}</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight mb-4">
                {{ __('Everything you need to reach your health goals') }}
            </h2>
            <div class="w-24 h-1 bg-gradient-to-r from-brand-light to-brand-dark rounded-full mx-auto"></div>
        </div>

        {{-- Feature Cards --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">

            {{-- Card 1: Chef-Crafted Meals --}}
            <div class="scroll-reveal group relative bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                <div class="absolute inset-0 bg-gradient-to-br from-brand-light/5 to-brand-dark/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-light to-brand-dark flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ __('Chef-Crafted Meals') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ __('Restaurant-quality recipes designed by expert chefs and nutritionists for optimal taste and health.') }}</p>
                </div>
            </div>

            {{-- Card 2: Flexible Subscriptions --}}
            <div class="scroll-reveal scroll-reveal-delay-1 group relative bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                <div class="absolute inset-0 bg-gradient-to-br from-brand-light/5 to-brand-dark/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-light to-brand-dark flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ __('Flexible Subscriptions') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ __('Pause, skip, or change your plan anytime. No commitments, no hassle.') }}</p>
                </div>
            </div>

            {{-- Card 3: Real-Time Tracking --}}
            <div class="scroll-reveal scroll-reveal-delay-2 group relative bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                <div class="absolute inset-0 bg-gradient-to-br from-brand-light/5 to-brand-dark/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-light to-brand-dark flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ __('Real-Time Tracking') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ __('Track your deliveries live from kitchen to doorstep. Know exactly when your meals arrive.') }}</p>
                </div>
            </div>

            {{-- Card 4: Premium Groceries --}}
            <div class="scroll-reveal group relative bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                <div class="absolute inset-0 bg-gradient-to-br from-brand-light/5 to-brand-dark/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-light to-brand-dark flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ __('Premium Groceries') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ __('We source only the freshest, highest-quality ingredients from trusted local suppliers.') }}</p>
                </div>
            </div>

            {{-- Card 5: Smart Portion Control --}}
            <div class="scroll-reveal scroll-reveal-delay-1 group relative bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                <div class="absolute inset-0 bg-gradient-to-br from-brand-light/5 to-brand-dark/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-light to-brand-dark flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ __('Smart Portion Control') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ __('AI-powered calorie and macro tracking tailored to your body and fitness goals.') }}</p>
                </div>
            </div>

            {{-- Card 6: Eco-Friendly Packaging --}}
            <div class="scroll-reveal scroll-reveal-delay-2 group relative bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                <div class="absolute inset-0 bg-gradient-to-br from-brand-light/5 to-brand-dark/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-light to-brand-dark flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ __('Eco-Friendly Packaging') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ __('100% biodegradable and recyclable packaging that keeps your meals fresh and the planet clean.') }}</p>
                </div>
            </div>

        </div>

        {{-- Bottom CTA --}}
        <div class="text-center mt-16 scroll-reveal">
            <div class="inline-flex items-center gap-4 px-8 py-4 rounded-2xl bg-white dark:bg-gray-800 shadow-lg border border-gray-100 dark:border-gray-700">
                <div class="flex -space-x-2 rtl:space-x-reverse">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-light to-brand-dark border-2 border-white dark:border-gray-800 flex items-center justify-center text-white font-bold text-xs">A</div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#949B50] to-[#6E7A25] border-2 border-white dark:border-gray-800 flex items-center justify-center text-white font-bold text-xs">M</div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#173327] to-[#6E7A25] border-2 border-white dark:border-gray-800 flex items-center justify-center text-white font-bold text-xs">S</div>
                    <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 border-2 border-white dark:border-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-300 font-bold text-xs">+5k</div>
                </div>
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Community of Champions') }}</p>
            </div>
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400 max-w-xl mx-auto">{{ __('Join thousands of athletes and health enthusiasts achieving their goals with Nutrio Meals.') }}</p>
        </div>
    </div>
</section>

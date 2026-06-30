<section class="py-20 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="scroll-reveal">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-4">Smart Nutrition, Real Results</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-8">Monitor your daily intake, track macros, and stay on top of your fitness goals — all from one beautiful dashboard built for your health journey.</p>

                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach ([['Daily calories', 'See your exact calorie intake'], ['Macro progress', 'Protein, carbs & fat balance'], ['BMI tracking', 'Monitor body composition'], ['Fitness goals', 'Set and crush targets']] as $item)
                        <div class="flex items-start gap-3 p-4 rounded-xl bg-gray-50 dark:bg-gray-800">
                            <div class="w-8 h-8 rounded-lg bg-brand-light/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-brand-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-sm">{{ $item[0] }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item[1] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="scroll-reveal scroll-reveal-delay-1">
                <div class="p-6 rounded-2xl bg-gray-900 dark:bg-gray-800 shadow-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-gray-400 text-xs">Daily Calories</p>
                            <p class="text-2xl font-bold text-white">1,840 / 2,200</p>
                        </div>
                        <div class="w-16 h-16 rounded-full border-4 border-brand-light flex items-center justify-center text-white font-bold text-sm">84%</div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-xs text-gray-400 mb-1"><span>Protein</span><span>142g / 180g</span></div>
                            <div class="h-2 rounded-full bg-gray-700"><div class="h-2 rounded-full bg-brand-light" style="width: 78%"></div></div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs text-gray-400 mb-1"><span>Carbs</span><span>195g / 250g</span></div>
                            <div class="h-2 rounded-full bg-gray-700"><div class="h-2 rounded-full bg-blue-500" style="width: 62%"></div></div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs text-gray-400 mb-1"><span>Fat</span><span>58g / 75g</span></div>
                            <div class="h-2 rounded-full bg-gray-700"><div class="h-2 rounded-full bg-yellow-500" style="width: 55%"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            {{-- Text content --}}
            <div class="scroll-reveal">
                <span class="inline-block px-4 py-1.5 rounded-full bg-[#6E7A25]/10 text-[#6E7A25] text-sm font-semibold mb-4">{{ __('Personal Dashboard') }}</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-4 leading-tight">{{ __('Smart Nutrition, Real Results') }}</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-8 text-base sm:text-lg leading-relaxed">{{ __('Monitor your daily intake, track macros, and stay on top of your fitness goals — all from one beautiful dashboard built for your health journey.') }}</p>

                <div class="space-y-4">
                    @foreach ([[__('Daily calories'), __('See your exact calorie intake at a glance')], [__('Macro progress'), __('Protein, carbs & fat balance in real time')], [__('BMI tracking'), __('Monitor body composition changes weekly')], [__('Fitness goals'), __('Set targets and crush them every month')]] as $item)
                        <div class="flex items-start gap-4 group">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-base">{{ $item[0] }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item[1] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <a href="#" class="mt-8 inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-xl shadow-lg shadow-[#6E7A25]/20 hover:shadow-xl hover:shadow-[#6E7A25]/30 hover:-translate-y-0.5 transition-all duration-300">
                    {{ __('Explore Dashboard') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>

            {{-- Images grid --}}
            <div class="scroll-reveal scroll-reveal-delay-1">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-4">
                        <div class="rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow duration-300">
                            <img src="{{ asset('images/meals/protein/chicken/IMG_3966.JPG') }}" alt="{{ __('Chicken') }}" loading="lazy" class="w-full h-48 object-cover hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow duration-300">
                            <img src="{{ asset('images/meals/protein/seafood/IMG_4065.JPG') }}" alt="{{ __('Seafood') }}" loading="lazy" class="w-full h-32 object-cover hover:scale-105 transition-transform duration-500">
                        </div>
                    </div>
                    <div class="space-y-4 pt-8">
                        <div class="rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow duration-300">
                            <img src="{{ asset('images/meals/protein/meat/IMG_4044.JPG') }}" alt="{{ __('Meat') }}" loading="lazy" class="w-full h-32 object-cover hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow duration-300">
                            <img src="{{ asset('images/meals/protein/chicken/IMG_4005.JPG') }}" alt="{{ __('Chicken') }}" loading="lazy" class="w-full h-48 object-cover hover:scale-105 transition-transform duration-500">
                        </div>
                    </div>
                </div>
                {{-- Floating badge --}}
                <div class="mt-6 flex items-center gap-4 p-4 rounded-2xl bg-white dark:bg-gray-800 shadow-xl border border-gray-100 dark:border-gray-700">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8v8M21 3l-9 9-4-4-6 6"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 dark:text-white text-sm">{{ __('Real-time tracking') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Updated with every meal delivery') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

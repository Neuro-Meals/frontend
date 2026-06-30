<section class="py-20 lg:py-28 bg-gray-50 dark:bg-gray-950 transition-colors duration-300 relative overflow-hidden">
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-light/30 to-transparent"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            {{-- Copy --}}
            <div class="scroll-reveal max-w-xl">
                <span class="inline-block px-4 py-1.5 rounded-full bg-brand-light/10 text-brand-light text-xs font-bold uppercase tracking-wider mb-4">Mobile App</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight mb-5">
                    Your nutrition companion, right in your pocket.
                </h2>
                <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed mb-8">
                    Track meals, manage subscriptions, and stay on top of your health goals with the Nutrio Meals app. Designed for Saudi Arabia's active lifestyle.
                </p>

                <div class="space-y-3 mb-8">
                    @foreach (['Manage subscription plans', 'Track meals & macros daily', 'Real-time delivery status', 'Health calculator on the go'] as $feature)
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-brand-light/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-brand-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="text-gray-700 dark:text-gray-200 font-medium">{{ $feature }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="flex flex-wrap gap-4">
                    <a href="#" class="inline-flex items-center gap-2.5 bg-[#0f1115] text-white px-5 py-2.5 rounded-xl hover:bg-[#1f2228] transition-colors">
                        <svg class="w-6 h-6 flex-shrink-0" viewBox="0 0 384 512" fill="currentColor"><path d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z"/></svg>
                        <span class="flex flex-col leading-tight">
                            <span class="text-[10px] font-normal text-gray-400">Download on the</span>
                            <span class="text-sm font-bold">App Store</span>
                        </span>
                    </a>
                    <a href="#" class="inline-flex items-center gap-2.5 bg-[#0f1115] text-white px-5 py-2.5 rounded-xl hover:bg-[#1f2228] transition-colors">
                        <svg class="w-6 h-6 flex-shrink-0" viewBox="0 0 512 512" fill="currentColor"><path d="M325.3 234.3L104.6 13.6c-5.6-5.6-14.4-6.5-21-2L256 224l69.3 10.3zM47.5 18.9C40.8 25.6 36 36.1 36 49.3v413.4c0 13.2 4.8 23.7 11.5 30.4L256 288 47.5 18.9zm322.7 124.5L327.5 159 256 224l71.5 65 92.7-53.5c14-8.1 14-28.4 0-36.5l-92.7-53.6zM104.6 498.4c6.6 4.5 15.4 3.6 21-2L325.3 277.7 256 224 104.6 498.4z"/></svg>
                        <span class="flex flex-col leading-tight">
                            <span class="text-[10px] font-normal text-gray-400">Get it on</span>
                            <span class="text-sm font-bold">Google Play</span>
                        </span>
                    </a>
                </div>
            </div>

            {{-- Phone Mockup --}}
            <div class="scroll-reveal scroll-reveal-delay-1 flex justify-center">
                <div class="relative" style="width:280px; height:572px;">
                    {{-- Phone body --}}
                    <div class="absolute inset-0 rounded-[46px] bg-[#0f1115] p-[14px] shadow-2xl" style="box-shadow: 0 30px 60px rgba(0,0,0,0.18);">
                        {{-- Screen --}}
                        <div class="w-full h-full rounded-[34px] overflow-hidden relative bg-white">
                            {{-- Notch --}}
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[120px] h-[24px] bg-[#0f1115] rounded-b-[16px] z-10"></div>

                            {{-- Coming Soon text --}}
                            <div class="absolute inset-0 flex flex-col items-center justify-center px-6">
                                <p class="text-gray-400 text-xs font-medium uppercase tracking-[3px] mb-2">Nutrio Meals</p>
                                <p class="text-gray-900 text-2xl font-extrabold tracking-tight mb-4">Coming Soon</p>
                                <div class="flex justify-center gap-1.5 mb-8">
                                    <span class="w-2 h-2 rounded-full bg-[#259B00] animate-pulse"></span>
                                    <span class="w-2 h-2 rounded-full bg-[#259B00]/60 animate-pulse" style="animation-delay: 0.2s;"></span>
                                    <span class="w-2 h-2 rounded-full bg-[#259B00]/30 animate-pulse" style="animation-delay: 0.4s;"></span>
                                </div>
                            </div>

                            {{-- Logo at bottom --}}
                            <div class="absolute bottom-6 left-0 right-0 flex justify-center">
                                <img src="{{ asset('nitro FULL 3.png') }}" alt="Nutrio Meals" class="h-12 w-auto object-contain opacity-80">
                            </div>
                        </div>
                    </div>

                    {{-- Side buttons --}}
                    <div class="absolute -left-[2px] top-[120px] w-[3px] h-[36px] bg-[#0f1115] rounded-[2px]"></div>
                    <div class="absolute -left-[2px] top-[166px] w-[3px] h-[36px] bg-[#0f1115] rounded-[2px]"></div>
                    <div class="absolute -right-[2px] top-[150px] w-[3px] h-[60px] bg-[#0f1115] rounded-[2px]"></div>
                </div>
            </div>
        </div>
    </div>
</section>

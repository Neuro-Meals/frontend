<section class="py-20 lg:py-28 bg-white dark:bg-gray-900 transition-colors duration-300 relative overflow-hidden">
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-light/30 to-transparent"></div>

    {{-- Decorative --}}
    <div class="absolute top-1/3 -right-32 w-72 h-72 rounded-full bg-brand-light/5 blur-3xl"></div>
    <div class="absolute bottom-1/4 -left-32 w-72 h-72 rounded-full bg-brand-light/5 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            {{-- Copy --}}
            <div class="scroll-reveal order-1 lg:order-2">
                <span class="inline-block px-4 py-1.5 rounded-full bg-brand-light/10 text-brand-light text-xs font-bold uppercase tracking-wider mb-4">{{ __('Delivery') }}</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white mb-5">{{ __('Delivered Fresh To Your Door') }}</h2>
                <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed mb-8">{{ __('Reliable delivery across Saudi Arabia with real-time tracking and driver updates. Your meals arrive fresh, on time, every time.') }}</p>

                <div class="grid sm:grid-cols-2 gap-4 mb-8">
                    @foreach ([__('Delivery scheduling'), __('Multiple addresses'), __('Real-time tracking'), __('Driver updates')] as $feature)
                        <div class="flex items-center gap-3 p-4 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:border-brand-light/30 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-brand-light/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-brand-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $feature }}</p>
                        </div>
                    @endforeach
                </div>

                <a href="#plans" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-sm font-bold hover:shadow-lg hover:shadow-brand-light/30 hover:-translate-y-0.5 transition-all">
                    {{ __('Start Your Plan') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            {{-- Receipt-style tracking card --}}
            <div class="scroll-reveal scroll-reveal-delay-1 order-2 lg:order-1">
                <div class="relative max-w-md mx-auto">
                    {{-- Receipt shadow --}}
                    <div class="absolute inset-0 bg-gradient-to-br from-brand-light/10 to-brand-dark/10 rounded-2xl rotate-2 scale-[1.02] blur-sm"></div>

                    {{-- Receipt body --}}
                    <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        {{-- Receipt header with zigzag bottom --}}
                        <div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] px-6 py-5 relative">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-white/15 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-white font-bold text-sm">Nutrio Meals</p>
                                        <p class="text-white/70 text-xs">Order #NT-2849</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-white/70 text-xs">{{ __('Status') }}</p>
                                    <p class="text-white font-bold text-xs flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                                        {{ __('En Route') }}
                                    </p>
                                </div>
                            </div>
                            {{-- Zigzag edge --}}
                            <div class="absolute -bottom-2 left-0 right-0 flex justify-around">
                                @for ($i = 0; $i < 20; $i++)
                                    <div class="w-3 h-3 bg-white dark:bg-gray-800 rounded-full"></div>
                                @endfor
                            </div>
                        </div>

                        {{-- Receipt content --}}
                        <div class="px-6 pt-8 pb-6">
                            {{-- Date & customer --}}
                            <div class="flex justify-between items-center mb-5 pb-4 border-b border-dashed border-gray-200 dark:border-gray-600">
                                <div>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ __('Delivery Date') }}</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">Mon, Jun 30</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ __('Customer') }}</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">Ahmed A.</p>
                                </div>
                            </div>

                            {{-- Order items --}}
                            <div class="space-y-3 mb-5">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-brand-light/10 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-brand-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"/></svg>
                                        </div>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Grilled Chicken Bowl') }}</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">1x</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-brand-light/10 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-brand-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"/></svg>
                                        </div>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Protein Smoothie') }}</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">2x</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-brand-light/10 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-brand-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"/></svg>
                                        </div>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Quinoa Salad') }}</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">1x</span>
                                </div>
                            </div>

                            {{-- Total --}}
                            <div class="flex justify-between items-center pt-4 border-t border-dashed border-gray-200 dark:border-gray-600 mb-5">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ __('Total') }}</span>
                                <span class="text-lg font-extrabold text-[#6E7A25]">149 SAR</span>
                            </div>

                            {{-- Tracking timeline --}}
                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4">
                                <p class="text-xs font-bold text-gray-900 dark:text-white mb-4 uppercase tracking-wider">{{ __('Delivery Tracking') }}</p>

                                {{-- Progress line --}}
                                <div class="relative">
                                    <div class="absolute left-[7px] top-2 bottom-2 w-0.5 bg-gray-200 dark:bg-gray-700"></div>
                                    <div class="absolute left-[7px] top-2 w-0.5 bg-gradient-to-b from-[#6E7A25] to-[#173327]" style="height: 65%;"></div>

                                    <div class="space-y-4 relative">
                                        <div class="flex items-center gap-3">
                                            <div class="w-4 h-4 rounded-full bg-[#6E7A25] border-2 border-white dark:border-gray-800 shadow z-10 flex-shrink-0"></div>
                                            <div class="flex-1 flex justify-between items-center">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Meal Prepared') }}</span>
                                                <span class="text-xs text-gray-400 dark:text-gray-500">12:30 PM</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-4 h-4 rounded-full bg-[#6E7A25] border-2 border-white dark:border-gray-800 shadow z-10 flex-shrink-0"></div>
                                            <div class="flex-1 flex justify-between items-center">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Out for Delivery') }}</span>
                                                <span class="text-xs text-gray-400 dark:text-gray-500">1:15 PM</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-4 h-4 rounded-full bg-[#6E7A25] border-2 border-white dark:border-gray-800 shadow z-10 flex-shrink-0 animate-pulse"></div>
                                            <div class="flex-1 flex justify-between items-center">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Driver 5 mins away') }}</span>
                                                <span class="text-xs text-[#6E7A25] font-bold">{{ __('Now') }}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-4 h-4 rounded-full bg-gray-200 dark:bg-gray-700 border-2 border-white dark:border-gray-800 z-10 flex-shrink-0"></div>
                                            <div class="flex-1 flex justify-between items-center">
                                                <span class="text-sm text-gray-400 dark:text-gray-500">{{ __('Delivered') }}</span>
                                                <span class="text-xs text-gray-400 dark:text-gray-500">Est. 1:45 PM</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Footer note --}}
                            <div class="mt-5 pt-4 border-t border-dashed border-gray-200 dark:border-gray-600 text-center">
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ __('Thank you for choosing Nutrio Meals!') }}</p>
                                <div class="flex justify-center gap-1 mt-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-light/30"></span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-light/40"></span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-light/50"></span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-light/60"></span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-light/70"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Bottom zigzag --}}
                        <div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] py-2 relative">
                            <div class="absolute -top-2 left-0 right-0 flex justify-around">
                                @for ($i = 0; $i < 20; $i++)
                                    <div class="w-3 h-3 bg-white dark:bg-gray-800 rounded-full"></div>
                                @endfor
                            </div>
                            <p class="text-center text-white/80 text-xs font-medium tracking-wider">FRESH • HEALTHY • ON TIME</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-gray-50 dark:bg-gray-800/50 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="scroll-reveal order-2 lg:order-1">
                <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 shadow-xl border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-full bg-brand-light/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-brand-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Driver is 5 mins away</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Order #NT-2849</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="w-3 h-3 rounded-full bg-brand-light mt-1.5"></div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">Meal prepared</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">12:30 PM</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div class="w-3 h-3 rounded-full bg-brand-light mt-1.5"></div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">Out for delivery</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">1:15 PM</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div class="w-3 h-3 rounded-full bg-gray-300 dark:bg-gray-600 mt-1.5"></div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">Delivered</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Estimated 1:45 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="scroll-reveal scroll-reveal-delay-1 order-1 lg:order-2">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-4">Delivered Fresh To Your Door</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-8">Reliable delivery across Saudi Arabia with real-time tracking and driver updates.</p>

                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach (['Delivery scheduling', 'Multiple addresses', 'Delivery tracking', 'Driver updates'] as $feature)
                        <div class="flex items-center gap-3 p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700">
                            <svg class="w-5 h-5 text-brand-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $feature }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

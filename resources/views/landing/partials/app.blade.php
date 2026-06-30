<section class="py-20 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="scroll-reveal">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-4">Manage Your Meals Anywhere</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-8">Download the Nutrio Meals app to manage subscriptions, track deliveries, and view your nutrition on the go.</p>

                <div class="space-y-4">
                    @foreach (['Manage subscription', 'Track meals & macros', 'View delivery status'] as $feature)
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-brand-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <p class="text-gray-700 dark:text-gray-200 font-medium">{{ $feature }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex flex-wrap gap-4">
                    <button class="px-6 py-3 rounded-xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold text-sm flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.05-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/></svg>
                        App Store
                    </button>
                    <button class="px-6 py-3 rounded-xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold text-sm flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M3.609 1.814L13.792 12 3.61 22.186a.996.996 0 01-.61-.92V2.734a1 1 0 01.609-.92zm10.89 10.893l2.302 2.302-10.937 6.333 8.635-8.635zm3.199-3.198l2.807 1.626a1 1 0 010 1.73l-2.808 1.626L15.206 12l2.492-2.491zM5.864 2.658L16.8 8.99l-2.302 2.302-8.634-8.634z"/></svg>
                        Google Play
                    </button>
                </div>
            </div>

            <div class="scroll-reveal scroll-reveal-delay-1">
                <div class="mx-auto max-w-sm p-4 rounded-[2.5rem] bg-gray-900 dark:bg-gray-800 border-8 border-gray-900 dark:border-gray-700 shadow-2xl">
                    <div class="h-96 rounded-[2rem] bg-gray-800 dark:bg-gray-700 overflow-hidden relative">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <img src="{{ asset('nitro FULL 3.png') }}" alt="Nutrio Meals App" class="h-24 w-auto object-contain opacity-80">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

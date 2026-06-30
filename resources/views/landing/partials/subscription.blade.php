<section class="py-20 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 scroll-reveal">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-4">Flexible Subscription</h2>
            <p class="text-gray-600 dark:text-gray-300">You are in control. Pause, skip, upgrade, or cancel anytime.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-6">
            @foreach (['Pause Anytime', 'Skip Meals', 'Upgrade Plan', 'Cancel Anytime', 'Family Plans'] as $index => $feature)
                <div class="scroll-reveal scroll-reveal-delay-{{ ($index % 5) + 1 }} p-6 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-center hover:border-brand-light dark:hover:border-brand-light transition-colors">
                    <div class="w-12 h-12 mx-auto rounded-full bg-brand-light/10 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-brand-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm">{{ $feature }}</h3>
                </div>
            @endforeach
        </div>
    </div>
</section>

<header class="fixed top-0 left-0 right-0 z-50 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-24">
            {{-- Logo --}}
            <a href="#" class="flex items-center gap-3">
                <img src="{{ asset('nitro FULL 3.png') }}" alt="{{ config('app.name', 'Nutrio Meals') }}" class="h-16 w-auto object-contain">
                <span class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ config('app.name', 'Nutrio Meals') }}</span>
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden md:flex items-center gap-8">
                <a href="#" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-brand-light dark:hover:text-brand-light transition-colors">Home</a>
                <a href="#plans" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-brand-light dark:hover:text-brand-light transition-colors">Plans</a>
                <a href="#how-it-works" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-brand-light dark:hover:text-brand-light transition-colors">How It Works</a>
                <a href="#gallery" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-brand-light dark:hover:text-brand-light transition-colors">Meals</a>
                <a href="#calculator" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-brand-light dark:hover:text-brand-light transition-colors">Calculator</a>
            </nav>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                <button id="theme-toggle" class="p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" aria-label="Toggle dark mode">
                    <svg id="theme-icon-sun" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg id="theme-icon-moon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-brand-light to-brand-dark hover:from-brand-dark hover:to-brand-light rounded-lg shadow-md hover:shadow-lg transition-all">Get Started</a>
                @endif
            </div>
        </div>
    </div>
</header>

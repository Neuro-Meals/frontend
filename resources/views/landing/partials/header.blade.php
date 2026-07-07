<header class="fixed top-3 left-0 right-0 z-50 px-4" x-data="{ mobileOpen: false }">
    <div class="max-w-7xl mx-auto bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl border border-gray-200/60 dark:border-gray-700/60 rounded-2xl shadow-lg dark:shadow-gray-900/30 transition-colors duration-300">
        <div class="flex items-center justify-between px-5 py-3">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center">
                <img src="{{ asset('whitelogo.png') }}" alt="Nutrio Meals" class="h-16 w-auto object-contain dark:hidden">
                <img src="{{ asset('blackmodelogo.png') }}" alt="Nutrio Meals" class="h-16 w-auto object-contain hidden dark:block">
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden md:flex items-center gap-7">
                <a href="#" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-brand-light dark:hover:text-brand-light transition-colors">{{ __('Home') }}</a>
                <a href="#plans" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-brand-light dark:hover:text-brand-light transition-colors">{{ __('Plans') }}</a>
                <a href="#how-it-works" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-brand-light dark:hover:text-brand-light transition-colors">{{ __('How It Works') }}</a>
                <a href="#gallery" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-brand-light dark:hover:text-brand-light transition-colors">{{ __('Meals') }}</a>
                <a href="#calculator" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-brand-light dark:hover:text-brand-light transition-colors">{{ __('Calculator') }}</a>
            </nav>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                {{-- Language Switcher --}}
                @include('partials.language_switcher', ['isDark' => false])
                <button id="theme-toggle" class="p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" aria-label="Toggle dark mode">
                    <svg id="theme-icon-sun" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg id="theme-icon-moon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
                @php $authApi = app(\App\Services\Api\AuthApiService::class); @endphp
                @if ($authApi->check())
                    <a href="{{ $authApi->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="hidden sm:inline-flex items-center gap-1.5 px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] hover:from-[#025C5F] hover:to-[#1E8A00] rounded-xl shadow-md shadow-brand-light/20 hover:shadow-lg hover:shadow-brand-light/30 hover:-translate-y-0.5 transition-all duration-300">
                        {{ __('Go to Dashboard') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 rounded-xl border border-emerald-200 dark:border-emerald-700 transition-all duration-300">
                            {{ __('Logout') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                @elseif (Route::has('login'))
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-1.5 px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] hover:from-[#025C5F] hover:to-[#1E8A00] rounded-xl shadow-md shadow-brand-light/20 hover:shadow-lg hover:shadow-brand-light/30 hover:-translate-y-0.5 transition-all duration-300">
                        {{ __('Get Started') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                @endif

                {{-- Mobile Menu Toggle --}}
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" aria-label="Toggle menu" :aria-expanded="mobileOpen">
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Nav Menu --}}
        <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="md:hidden border-t border-gray-200/60 dark:border-gray-700/60" x-cloak>
            <nav class="flex flex-col px-5 py-4 gap-3">
                <a href="#" @click="mobileOpen = false" class="text-base font-semibold text-gray-600 dark:text-gray-300 hover:text-brand-light dark:hover:text-brand-light transition-colors">{{ __('Home') }}</a>
                <a href="#plans" @click="mobileOpen = false" class="text-base font-semibold text-gray-600 dark:text-gray-300 hover:text-brand-light dark:hover:text-brand-light transition-colors">{{ __('Plans') }}</a>
                <a href="#how-it-works" @click="mobileOpen = false" class="text-base font-semibold text-gray-600 dark:text-gray-300 hover:text-brand-light dark:hover:text-brand-light transition-colors">{{ __('How It Works') }}</a>
                <a href="#gallery" @click="mobileOpen = false" class="text-base font-semibold text-gray-600 dark:text-gray-300 hover:text-brand-light dark:hover:text-brand-light transition-colors">{{ __('Meals') }}</a>
                <a href="#calculator" @click="mobileOpen = false" class="text-base font-semibold text-gray-600 dark:text-gray-300 hover:text-brand-light dark:hover:text-brand-light transition-colors">{{ __('Calculator') }}</a>
                @if ($authApi->check())
                    <a href="{{ $authApi->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" @click="mobileOpen = false" class="mt-2 inline-flex items-center justify-center gap-1.5 px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] hover:from-[#025C5F] hover:to-[#1E8A00] rounded-xl shadow-md transition-all duration-300">
                        {{ __('Go to Dashboard') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" @click="mobileOpen = false" class="block w-full">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-6 py-2.5 text-sm font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl border border-emerald-200 dark:border-emerald-700 transition-all duration-300">
                            {{ __('Logout') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                @elseif (Route::has('login'))
                    <a href="{{ route('login') }}" @click="mobileOpen = false" class="mt-2 inline-flex items-center justify-center gap-1.5 px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] hover:from-[#025C5F] hover:to-[#1E8A00] rounded-xl shadow-md transition-all duration-300">
                        {{ __('Get Started') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                @endif
            </nav>
        </div>
    </div>
</header>

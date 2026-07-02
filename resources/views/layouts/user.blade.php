<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="{{ app()->getLocale() === 'ar' ? 'rtl' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Dashboard') . ' - ' . __('Nutrio Meals'))</title>
    <link rel="icon" type="image/png" href="{{ asset('whitelogo.png') }}">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#F6F3E9', 100: '#e8e4d0', 200: '#d1cb9f', 300: '#babd7a',
                            400: '#a3a85f', 500: '#949B50', 600: '#6E7A25', 700: '#173327',
                            800: '#122620', 900: '#0d1916'
                        },
                        accent: {
                            50: '#f5f4ec', 100: '#e8e6d0', 200: '#d1cc9f', 300: '#babd7a',
                            400: '#a3a85f', 500: '#949B50', 600: '#7d8442', 700: '#6E7A25',
                            800: '#5a631d', 900: '#474d18'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade { animation: fadeIn 0.3s ease-out both; }
        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover { background: rgba(255,255,255,0.06); }
        .sidebar-link.active { background: rgba(110,122,37,0.15); color: #fff; border-left: 3px solid #6E7A25; }
        .sidebar-submenu { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
        .sidebar-submenu.open { max-height: 500px; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #122620; }
        ::-webkit-scrollbar-thumb { background: #6E7A25; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #949B50; }
        .card-sm { transition: all 0.2s cubic-bezier(0.4,0,0.2,1); }
        .card-sm:hover { transform: translateY(-2px); box-shadow: 0 8px 30px -8px rgba(0,0,0,0.1); }
        .skeleton { background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%); background-size: 200% 100%; animation: skeleton-shimmer 1.5s infinite; }
        @keyframes skeleton-shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
        html.rtl body { direction: rtl; }
        html.rtl .sidebar-link { text-align: right; }
        html.rtl aside { left: auto; right: 0; }
        html.rtl .lg\:ml-64 { margin-left: 0; margin-right: 16rem; }
        html.rtl .translate-x-full { transform: translateX(-100%); }
        html.rtl .-translate-x-full { transform: translateX(100%); }
        html.rtl .lg\:translate-x-0 { transform: translateX(0); }
        html.rtl body { font-family: 'Cairo', sans-serif; }
        html.rtl .font-['Nunito',sans-serif] { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="font-['Nunito',sans-serif] antialiased bg-gray-50 text-slate-800">

    @include('partials.loading')

    {{-- Mobile Overlay --}}
    <div id="mobileOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <aside id="userSidebar" class="fixed top-0 left-0 rtl:left-auto rtl:right-0 z-50 w-64 h-screen bg-brand-700 transform -translate-x-full rtl:translate-x-full rtl:-translate-x-full lg:translate-x-0 rtl:lg:translate-x-0 transition-transform duration-300 flex flex-col">
        {{-- Brand --}}
        <div class="h-16 flex items-center px-6 border-b border-brand-800/50 flex-shrink-0">
            <img src="{{ asset('blackmodelogo.png') }}" alt="Nutrio Meals" class="h-9 w-auto">
        </div>

        {{-- Menu --}}
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

            {{-- Dashboard --}}
            <div class="sidebar-group">
                <a href="{{ route('user.dashboard') }}" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </div>

            {{-- Subscriptions --}}
            <div class="sidebar-group">
                <a href="{{ route('user.subscriptions') }}" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('user.subscriptions*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>{{ __('Subscriptions') }}</span>
                </a>
            </div>

            {{-- My Meals --}}
            <div class="sidebar-group">
                <a href="{{ route('user.meals') }}" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('user.meals*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>{{ __('Meals') }}</span>
                </a>
            </div>

            {{-- Nutrition Tracker --}}
            <div class="sidebar-group">
                <a href="{{ route('user.nutrition') }}" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('user.nutrition*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>{{ __('Nutrition') }}</span>
                </a>
            </div>

            {{-- Orders --}}
            <div class="sidebar-group">
                <a href="{{ route('user.orders') }}" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('user.orders*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>{{ __('Orders') }}</span>
                </a>
            </div>

            {{-- Delivery --}}
            <div class="sidebar-group">
                <a href="{{ route('user.delivery') }}" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('user.delivery*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    <span>{{ __('Deliveries') }}</span>
                </a>
            </div>

            {{-- Notifications --}}
            <div class="sidebar-group">
                <a href="{{ route('user.notifications') }}" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('user.notifications*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span>{{ __('Notifications') }}</span>
                </a>
            </div>

            {{-- Settings --}}
            <div class="sidebar-group">
                <a href="{{ route('user.settings') }}" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('user.settings*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>{{ __('Settings') }}</span>
                </a>
            </div>

        </div>

        {{-- Bottom User --}}
        <div class="p-4 border-t border-brand-800/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-accent-400 to-accent-600 flex items-center justify-center text-white font-bold text-xs">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-brand-300/60">{{ __('Member') }}</p>
                </div>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('user-logout').submit();" class="text-brand-300/60 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </a>
                <form id="user-logout" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="lg:ml-64 rtl:lg:mr-64 rtl:lg:ml-0 min-h-screen flex flex-col">

        {{-- Header --}}
        <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-6 sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-lg font-bold text-gray-800">@yield('page_title', __('Dashboard'))</h1>
            </div>
            <div class="flex items-center gap-4">
                {{-- Search --}}
                <div class="hidden md:flex items-center bg-gray-50 rounded-lg px-3 py-1.5 border border-gray-100">
                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" placeholder="{{ __('Search...') }}" class="bg-transparent text-sm outline-none w-48 text-gray-600 placeholder-gray-400">
                </div>
                {{-- Language Switcher --}}
                @include('partials.language_switcher', ['isDark' => false])
                {{-- Notifications --}}
                <button class="relative p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 p-6 animate-fade">
            @yield('content')
        </main>

    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('userSidebar');
            const overlay = document.getElementById('mobileOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        function toggleMenu(id) {
            const menu = document.getElementById(id);
            const arrow = document.getElementById('arrow-' + id.replace('menu-', ''));
            menu.classList.toggle('open');
            if (arrow) arrow.classList.toggle('rotate-180');
        }
    </script>
    @stack('scripts')
</body>
</html>

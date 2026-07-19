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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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
        .nav-item { transition: all 0.2s ease; }
        .nav-item:hover { background: rgba(255,255,255,0.06); color: #fff; }
        .nav-item.active { background: rgba(110,122,37,0.15); color: #fff; border-left: 3px solid #6E7A25; }
        .nav-item.active svg { color: #949B50; }
        .kpi-card { transition: all 0.3s cubic-bezier(0.4,0,0.2,1); }
        .kpi-card:hover { transform: translateY(-3px); box-shadow: 0 12px 30px -8px rgba(0,0,0,0.15); }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #122620; }
        ::-webkit-scrollbar-thumb { background: #6E7A25; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #949B50; }
        .card-sm { transition: all 0.2s cubic-bezier(0.4,0,0.2,1); }
        .card-sm:hover { transform: translateY(-2px); box-shadow: 0 8px 30px -8px rgba(0,0,0,0.1); }
        @media print {
            aside, header, .no-print { display: none !important; }
            main { padding: 0 !important; }
            .lg\:ml-64 { margin-left: 0 !important; }
            body { background: white !important; }
            .bg-white { box-shadow: none !important; border: 1px solid #e5e5e5 !important; }
            .kpi-card:hover, .card-sm:hover { transform: none !important; box-shadow: none !important; }
            .shadow-sm, .shadow-lg { box-shadow: none !important; }
            .bg-gradient-to-br, .bg-gradient-to-r { background: #f5f5f5 !important; color: #333 !important; }
            .bg-gradient-to-br .text-white, .bg-gradient-to-r .text-white { color: #333 !important; }
            .bg-gradient-to-br .text-white\/50, .bg-gradient-to-br .text-white\/60 { color: #666 !important; }
            .text-transparent { color: #333 !important; background: none !important; }
            .rounded-xl { border-radius: 4px !important; }
            .rounded-lg { border-radius: 3px !important; }
            .animate-fade { animation: none !important; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
            .print\:block { display: block !important; }
            @page { margin: 1.5cm; size: A4; }
        }
        html.rtl body { direction: rtl; }
        html.rtl .nav-item { text-align: right; }
        html.rtl .nav-item svg { margin-left: 0; margin-right: 0; }
        html.rtl aside { left: auto; right: 0; }
        html.rtl .lg\:ml-64 { margin-left: 0; margin-right: 16rem; }
        html.rtl .translate-x-full { transform: translateX(-100%); }
        html.rtl .-translate-x-full { transform: translateX(100%); }
        html.rtl .lg\:translate-x-0 { transform: translateX(0); }
        html.rtl body { font-family: 'Cairo', sans-serif; }
        html.rtl .font-['Nunito',sans-serif] { font-family: 'Cairo', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-['Nunito',sans-serif] antialiased bg-gradient-to-br from-[#F6F3E9] via-gray-50 to-[#949B50]/5 text-slate-800 min-h-screen">

    @include('partials.loading')

    {{-- Mobile Overlay --}}
    <div id="mobileOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <aside id="adminSidebar" class="fixed top-0 left-0 rtl:left-auto rtl:right-0 z-50 w-64 h-screen bg-brand-700 transform -translate-x-full rtl:translate-x-full rtl:-translate-x-full lg:translate-x-0 rtl:lg:translate-x-0 transition-transform duration-300 flex flex-col">
        {{-- Brand --}}
        <div class="h-16 flex items-center px-6 border-b border-brand-800/50 flex-shrink-0">
            <img src="{{ asset('blackmodelogo.png') }}" alt="Nutrio Meals" class="h-9 w-auto">
            <span class="ml-2 px-2 py-0.5 rounded-full bg-accent-500/20 text-accent-400 text-[10px] font-bold">ADMIN</span>
        </div>

        {{-- Menu --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

            <a href="{{ route('admin.dashboard') }}" class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span>{{ __('Dashboard') }}</span>
            </a>

            <a href="{{ route('admin.customers') }}" class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>{{ __('Customers') }}</span>
            </a>

            <a href="{{ route('admin.subscriptions') }}" class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('admin.subscriptions*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>{{ __('Subscriptions') }}</span>
            </a>

            <a href="{{ route('admin.plans') }}" class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('admin.plans*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <span>{{ __('Plans') }}</span>
            </a>

            <a href="{{ route('admin.meals') }}" class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('admin.meals*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>{{ __('Meals & Nutrition') }}</span>
            </a>

            <a href="{{ route('admin.orders') }}" class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span>{{ __('Orders') }}</span>
            </a>

            <a href="{{ route('admin.deliveries') }}" class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('admin.deliveries*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                <span>{{ __('Deliveries') }}</span>
            </a>

            <a href="{{ route('admin.drivers') }}" class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('admin.drivers*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>{{ __('Drivers') }}</span>
            </a>

            <a href="{{ route('admin.chefs') }}" class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('admin.chefs*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h18M3 12h18M3 19h18M7 5v14M17 5v14"/></svg>
                <span>{{ __('Chefs') }}</span>
            </a>

            <a href="{{ route('admin.live') }}" class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('admin.live') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12c0-4.97 4.03-9 9-9s9 4.03 9 9-4.03 9-9 9-9-4.03-9-9z M7.5 12c0-2.485 2.015-4.5 4.5-4.5s4.5 2.015 4.5 4.5-2.015 4.5-4.5 4.5-4.5-2.015-4.5-4.5z M12 12 m-1.5 0 a1.5 1.5 0 1 0 3 0 a1.5 1.5 0 1 0 -3 0"/></svg>
                <span>{{ __('Live') }}</span>
                <span class="ml-auto px-1.5 py-0.5 rounded-full bg-gradient-to-r from-orange-500 to-red-500 text-white text-[8px] font-bold tracking-wider flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                    LIVE
                </span>
            </a>

            <a href="{{ route('admin.payments') }}" class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span>{{ __('Payments') }}</span>
            </a>

            <a href="{{ route('admin.notifications') }}" class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('admin.notifications*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <span>{{ __('Notifications') }}</span>
                <span class="ml-auto w-2 h-2 rounded-full bg-gradient-to-r from-[#6E7A25] to-accent-400 animate-pulse shadow-sm shadow-accent-400/50"></span>
            </a>

            <a href="{{ route('admin.reports.dashboard') }}" class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>{{ __('Reports') }}</span>
            </a>

            <a href="{{ route('admin.settings') }}" class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-brand-100 text-sm font-medium {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>{{ __('Settings') }}</span>
            </a>

        </nav>

        {{-- Bottom User --}}
        @php $apiUser = session('api_user', []); $userName = trim(($apiUser['first_name'] ?? '') . ' ' . ($apiUser['last_name'] ?? '')); @endphp
        <div class="p-4 border-t border-brand-800/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-accent-400 to-accent-600 flex items-center justify-center text-white font-bold text-xs">
                    {{ strtoupper(substr($userName ?: 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ $userName ?: 'Admin User' }}</p>
                    <p class="text-xs text-brand-300/60">{{ __(ucfirst($apiUser['role'] ?? 'Admin')) }}</p>
                </div>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('admin-logout').submit();" class="text-brand-300/60 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </a>
                <form id="admin-logout" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
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
                {{-- Live Delivery --}}
                <a href="{{ route('admin.live') }}" class="relative p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors group" title="{{ __('Live Deliveries') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-orange-500 rounded-full flex items-center justify-center text-[8px] font-bold text-white animate-pulse">!</span>
                    <span class="absolute inset-0 rounded-lg ring-2 ring-orange-400/0 group-hover:ring-orange-400/30 transition-all"></span>
                </a>
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
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('mobileOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>

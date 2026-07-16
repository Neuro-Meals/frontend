<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#173327">
    <title>@yield('title', __('Chef') . ' - ' . __('Nutrio Meals'))</title>
    <link rel="icon" type="image/png" href="{{ asset('whitelogo.png') }}">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 50: '#F6F3E9', 100: '#e8e4d0', 200: '#d1cb9f', 300: '#babd7a', 400: '#a3a85f', 500: '#949B50', 600: '#6E7A25', 700: '#173327', 800: '#122620', 900: '#0d1916' },
                        chef: { 50: '#FFF7ED', 100: '#FFEDD5', 200: '#FED7AA', 300: '#FDBA74', 400: '#FB923C', 500: '#F97316', 600: '#EA580C', 700: '#C2410C', 800: '#9A3412', 900: '#7C2D12' }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes slideUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes pulse-soft { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
        .animate-slide-up { animation: slideUp 0.35s ease-out both; }
        .animate-delay-1 { animation-delay: 0.05s; }
        .animate-delay-2 { animation-delay: 0.1s; }
        .animate-delay-3 { animation-delay: 0.15s; }
        .animate-delay-4 { animation-delay: 0.2s; }
        .pulse-dot { animation: pulse-soft 2s infinite; }
        .bottom-nav-item { transition: all 0.2s ease; }
        .bottom-nav-item.active { color: #C2410C; }
        .bottom-nav-item.active svg { stroke: #C2410C; }
        .status-badge { transition: all 0.3s ease; }
        .btn-action { transition: transform 0.1s ease, box-shadow 0.2s ease; }
        .btn-action:active { transform: scale(0.96); }
        .meal-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .meal-card:active { transform: scale(0.98); }
        .timeframe-tab { transition: all 0.25s ease; }
        html, body { height: 100%; }
        .safe-area-pb { padding-bottom: env(safe-area-inset-bottom, 0px); }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased" style="font-family: 'Nunito', sans-serif;">
    <div class="min-h-full flex flex-col max-w-md mx-auto bg-white shadow-2xl">
        @yield('content')

        <!-- Bottom Navigation -->
        <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 safe-area-pb z-40">
            <div class="max-w-md mx-auto grid grid-cols-2 h-16">
                <a href="{{ route('chef.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('chef.dashboard') ? 'active' : 'text-gray-400' }} flex flex-col items-center justify-center gap-0.5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="text-[10px] font-bold">{{ __('Kitchen') }}</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="flex flex-col items-center justify-center">
                    @csrf
                    <button type="submit" class="bottom-nav-item text-gray-400 flex flex-col items-center justify-center gap-0.5 w-full h-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span class="text-[10px] font-bold">{{ __('Logout') }}</span>
                    </button>
                </form>
            </div>
        </nav>
        <div class="h-16"></div>
    </div>
    <script>
        function confirmMealAction(url, status, opts = {}) {
            const {
                title = 'Are you sure?',
                text = '',
                confirmText = 'Confirm',
                icon = 'question',
                confirmColor = '#C2410C',
            } = opts;

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#d1d5db',
                confirmButtonText: confirmText,
                cancelButtonText: '{{ __('Cancel') }}',
                reverseButtons: true,
                customClass: { popup: 'rounded-2xl' },
            }).then((result) => {
                if (!result.isConfirmed) return;

                const body = new URLSearchParams({ status });

                Swal.fire({
                    title: '{{ __('Updating...') }}',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading(),
                });

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body,
                })
                    .then((res) => res.json())
                    .then((data) => {
                        if (data.success) {
                            Swal.fire({
                                title: '{{ __('Success!') }}',
                                text: data.message || '{{ __('Status updated.') }}',
                                icon: 'success',
                                confirmButtonColor: '#C2410C',
                                customClass: { popup: 'rounded-2xl' },
                                timer: 1400,
                                showConfirmButton: false,
                            }).then(() => window.location.reload());
                        } else {
                            Swal.fire({
                                title: '{{ __('Oops!') }}',
                                text: data.message || '{{ __('Something went wrong.') }}',
                                icon: 'error',
                                confirmButtonColor: '#C2410C',
                                customClass: { popup: 'rounded-2xl' },
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            title: '{{ __('Network Error') }}',
                            text: '{{ __('Please check your connection and try again.') }}',
                            icon: 'error',
                            confirmButtonColor: '#C2410C',
                            customClass: { popup: 'rounded-2xl' },
                        });
                    });
            });
        }
    </script>
    @stack('scripts')
</body>
</html>

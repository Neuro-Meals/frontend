<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#173327">
    <title>@yield('title', __('Driver') . ' - ' . __('Nutrio Meals'))</title>
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
                        brand: { 50: '#F6F3E9', 100: '#e8e4d0', 200: '#d1cb9f', 300: '#babd7a', 400: '#a3a85f', 500: '#949B50', 600: '#6E7A25', 700: '#173327', 800: '#122620', 900: '#0d1916' }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes slideUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes pulse-soft { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
        @keyframes popIn { from { opacity: 0; transform: scale(0.92); } to { opacity: 1; transform: scale(1); } }
        @keyframes floatY { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
        .animate-slide-up { animation: slideUp 0.35s ease-out both; }
        .animate-pop-in { animation: popIn 0.35s cubic-bezier(.34,1.56,.64,1) both; }
        .animate-float { animation: floatY 2.4s ease-in-out infinite; }
        .animate-delay-1 { animation-delay: 0.05s; }
        .animate-delay-2 { animation-delay: 0.1s; }
        .animate-delay-3 { animation-delay: 0.15s; }
        .pulse-dot { animation: pulse-soft 2s infinite; }
        .bottom-nav-item { transition: all 0.2s ease; }
        .bottom-nav-item.active { color: #6E7A25; }
        .bottom-nav-item.active svg { stroke: #6E7A25; }
        .status-badge { transition: all 0.3s ease; }
        .btn-action { transition: transform 0.1s ease, box-shadow 0.2s ease; }
        .btn-action:active { transform: scale(0.96); }
        html, body { height: 100%; }
        .safe-area-pb { padding-bottom: env(safe-area-inset-bottom, 0px); }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased" style="font-family: 'Nunito', sans-serif;">
    <div class="min-h-full flex flex-col max-w-md mx-auto bg-white shadow-2xl">
        @yield('content')

        <!-- Bottom Navigation -->
        <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 safe-area-pb z-40">
            <div class="max-w-md mx-auto grid grid-cols-3 h-16">
                <a href="{{ route('driver.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('driver.dashboard') ? 'active' : 'text-gray-400' }} flex flex-col items-center justify-center gap-0.5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span class="text-[10px] font-bold">{{ __('Home') }}</span>
                </a>
                <a href="{{ route('driver.deliveries') }}" class="bottom-nav-item {{ request()->routeIs('driver.deliveries') ? 'active' : 'text-gray-400' }} flex flex-col items-center justify-center gap-0.5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 001 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                    <span class="text-[10px] font-bold">{{ __('Deliveries') }}</span>
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
        function confirmDeliveryAction(url, status, opts = {}) {
            const {
                title = 'Are you sure?',
                text = '',
                confirmText = 'Confirm',
                icon = 'question',
                confirmColor = '#173327',
                reason = null,
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
                if (reason) body.append('reason', reason);

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
                                confirmButtonColor: '#173327',
                                customClass: { popup: 'rounded-2xl' },
                                timer: 1400,
                                showConfirmButton: false,
                            }).then(() => window.location.reload());
                        } else {
                            Swal.fire({
                                title: '{{ __('Oops!') }}',
                                text: data.message || '{{ __('Something went wrong.') }}',
                                icon: 'error',
                                confirmButtonColor: '#173327',
                                customClass: { popup: 'rounded-2xl' },
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            title: '{{ __('Network Error') }}',
                            text: '{{ __('Please check your connection and try again.') }}',
                            icon: 'error',
                            confirmButtonColor: '#173327',
                            customClass: { popup: 'rounded-2xl' },
                        });
                    });
            });
        }

        function confirmFailDelivery(url) {
            Swal.fire({
                title: '{{ __('Mark Delivery as Failed') }}',
                input: 'textarea',
                inputPlaceholder: '{{ __('Please tell us why the delivery could not be completed...') }}',
                inputAttributes: { 'aria-label': 'Reason' },
                showCancelButton: true,
                confirmButtonText: '{{ __('Submit') }}',
                cancelButtonText: '{{ __('Cancel') }}',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#d1d5db',
                reverseButtons: true,
                customClass: { popup: 'rounded-2xl' },
                inputValidator: (value) => {
                    if (!value) return '{{ __('Please provide a reason.') }}';
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    confirmDeliveryAction(url, 'failed', {
                        title: '{{ __('Confirm Failed Delivery?') }}',
                        text: '{{ __('This will mark the delivery as failed with your reason.') }}',
                        confirmText: '{{ __('Yes, mark as failed') }}',
                        icon: 'warning',
                        confirmColor: '#dc2626',
                        reason: result.value,
                    });
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>

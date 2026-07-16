<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.6">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#173327">
    <title>@yield('title', __('Kitchen') . ' - Nutrio Meals')</title>
    <link rel="icon" type="image/png" href="{{ asset('whitelogo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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
                    },
                    fontFamily: { cairo: ['Cairo', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        @keyframes slideUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes popIn { from { opacity: 0; transform: scale(0.92); } to { opacity: 1; transform: scale(1); } }
        @keyframes pulse-soft { 0%, 100% { opacity: 1; } 50% { opacity: 0.65; } }
        @keyframes floatY { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
        .animate-slide-up { animation: slideUp 0.4s ease-out both; }
        .animate-fade-in { animation: fadeIn 0.3s ease-out both; }
        .animate-pop-in { animation: popIn 0.35s cubic-bezier(.34,1.56,.64,1) both; }
        .animate-float { animation: floatY 2.4s ease-in-out infinite; }
        .animate-delay-1 { animation-delay: 0.05s; }
        .animate-delay-2 { animation-delay: 0.1s; }
        .animate-delay-3 { animation-delay: 0.15s; }
        .animate-delay-4 { animation-delay: 0.2s; }
        .pulse-dot { animation: pulse-soft 2s infinite; }
        .btn-action { transition: transform 0.12s ease, box-shadow 0.2s ease, background-color 0.2s ease; }
        .btn-action:active { transform: scale(0.97); }
        .tab-pill { transition: all 0.2s ease; }
        .progress-fill { transition: width 0.6s cubic-bezier(.4,0,.2,1); }
        .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-hover:active { transform: scale(0.98); }
        html, body { height: 100%; }
        body { font-family: 'Cairo', sans-serif; }
        ::-webkit-scrollbar { height: 6px; width: 6px; }
        ::-webkit-scrollbar-thumb { background: #d1cb9f; border-radius: 9999px; }
        [x-cloak] { display: none !important; }
        .bg-diamond {
            background-image:
                linear-gradient(135deg, rgba(23,51,39,0.05) 25%, transparent 25%),
                linear-gradient(225deg, rgba(23,51,39,0.05) 25%, transparent 25%);
            background-size: 24px 24px;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-brand-50 text-gray-800 antialiased">
    <div class="min-h-full max-w-3xl mx-auto md:my-4 md:rounded-[2rem] md:shadow-2xl md:overflow-hidden bg-brand-50 relative pb-20">
        @yield('content')
    </div>

    {{-- Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 right-0 z-40 md:absolute md:bottom-0 md:left-0 md:right-0">
        <div class="max-w-3xl mx-auto bg-white border-t border-gray-100 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] flex items-center justify-around px-2 py-2 safe-area-bottom">
            <a href="{{ route('chef.dashboard') }}" class="flex flex-col items-center gap-0.5 px-5 py-1.5 rounded-xl transition-all @if(request()->routeIs('chef.dashboard')) bg-brand-50 text-brand-700 @else text-gray-400 @endif">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-[10px] font-bold">{{ __('Shift') }}</span>
            </a>
            <a href="{{ route('chef.schedule') }}" class="flex flex-col items-center gap-0.5 px-5 py-1.5 rounded-xl transition-all @if(request()->routeIs('chef.schedule')) bg-brand-50 text-brand-700 @else text-gray-400 @endif">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-[10px] font-bold">{{ __('Schedule') }}</span>
            </a>
        </div>
    </nav>

    <script>
        // Shared confirm+fetch helper for chef actions (start preparing / mark ready).
        const chefI18n = {
            confirm: @json(__('Confirm?')),
            confirmBtn: @json(__('Confirm')),
            cancel: @json(__('Cancel')),
            actionFailed: @json(__('Action Failed')),
            somethingWrong: @json(__('Something went wrong.')),
            connectionError: @json(__('Connection Error')),
            checkConnection: @json(__('Please check your connection and try again.')),
        };

        function chefAction(url, opts = {}) {
            const {
                title = chefI18n.confirm,
                text = '',
                confirmText = chefI18n.confirmBtn,
                icon = 'question',
                confirmColor = '#173327',
            } = opts;

            return new Promise((resolve) => {
                Swal.fire({
                    title, text, icon,
                    showCancelButton: true,
                    confirmButtonColor: confirmColor,
                    cancelButtonColor: '#d1d5db',
                    confirmButtonText: confirmText,
                    cancelButtonText: chefI18n.cancel,
                    reverseButtons: true,
                    customClass: { popup: 'rounded-2xl' },
                }).then((result) => {
                    if (!result.isConfirmed) { resolve(false); return; }

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                    })
                        .then((res) => res.json())
                        .then((data) => {
                            if (data.success) {
                                resolve(true);
                            } else {
                                Swal.fire({
                                    title: chefI18n.actionFailed,
                                    text: data.message || chefI18n.somethingWrong,
                                    icon: 'error',
                                    confirmButtonColor: '#173327',
                                    customClass: { popup: 'rounded-2xl' },
                                });
                                resolve(false);
                            }
                        })
                        .catch(() => {
                            Swal.fire({
                                title: chefI18n.connectionError,
                                text: chefI18n.checkConnection,
                                icon: 'error',
                                confirmButtonColor: '#173327',
                                customClass: { popup: 'rounded-2xl' },
                            });
                            resolve(false);
                        });
                });
            });
        }
    </script>
    @stack('scripts')
</body>
</html>

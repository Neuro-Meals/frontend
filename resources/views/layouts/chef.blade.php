<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.6">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#173327">
    <title>@yield('title', 'المطبخ - Nutrio Meals')</title>
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
    <div class="min-h-full max-w-3xl mx-auto md:my-4 md:rounded-[2rem] md:shadow-2xl md:overflow-hidden bg-brand-50 relative">
        @yield('content')
    </div>

    <script>
        // Shared confirm+fetch helper for chef actions (start preparing / mark ready).
        function chefAction(url, opts = {}) {
            const {
                title = 'تأكيد؟',
                text = '',
                confirmText = 'تأكيد',
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
                    cancelButtonText: 'إلغاء',
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
                                    title: 'تعذر التنفيذ',
                                    text: data.message || 'حدث خطأ ما.',
                                    icon: 'error',
                                    confirmButtonColor: '#173327',
                                    customClass: { popup: 'rounded-2xl' },
                                });
                                resolve(false);
                            }
                        })
                        .catch(() => {
                            Swal.fire({
                                title: 'خطأ في الاتصال',
                                text: 'يرجى التحقق من الاتصال والمحاولة مرة أخرى.',
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

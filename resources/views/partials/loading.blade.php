{{-- Global Loading Overlay --}}
<div id="globalLoader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-gradient-to-br from-[#F6F3E9] via-white to-[#949B50]/10 transition-opacity duration-500">
    <div class="flex flex-col items-center gap-6">
        {{-- Logo with elegant glow --}}
        <div class="loader-logo-wrap relative">
            <div class="absolute inset-0 rounded-2xl bg-[#6E7A25]/30 blur-2xl animate-ping-slow"></div>
            <div class="absolute -inset-4 rounded-3xl bg-gradient-to-br from-[#6E7A25]/20 to-[#173327]/10 blur-xl"></div>
            <img src="{{ asset('blackmodelogo.png') }}" alt="Nutrio Meals" class="relative h-24 w-auto object-contain animate-logo-blink drop-shadow-lg">
        </div>

        {{-- Elegant dual-ring spinner --}}
        <div class="relative w-12 h-12">
            <div class="absolute inset-0 rounded-full border-[3px] border-gray-100"></div>
            <div class="absolute inset-0 rounded-full border-[3px] border-transparent border-t-[#6E7A25] animate-spin-fast"></div>
            <div class="absolute inset-2 rounded-full border-[2px] border-transparent border-b-[#173327] animate-spin-reverse"></div>
        </div>

        {{-- Loading text with dots --}}
        <div class="text-center space-y-1">
            <p class="text-xs font-bold text-[#173327] tracking-widest uppercase animate-text-fade">{{ __('Nutrio Meals') }}</p>
            <p class="text-[10px] font-medium text-gray-400 animate-text-fade">{{ __('Preparing your experience') }}</p>
        </div>
    </div>
</div>

<style>
    @keyframes logoBlink {
        0%, 100% { opacity: 1; transform: scale(1) translateY(0); }
        50% { opacity: 0.85; transform: scale(0.97) translateY(-2px); }
    }
    @keyframes pingSlow {
        0% { transform: scale(1); opacity: 0.5; }
        75%, 100% { transform: scale(2); opacity: 0; }
    }
    @keyframes spinFast {
        to { transform: rotate(360deg); }
    }
    @keyframes spinReverse {
        to { transform: rotate(-360deg); }
    }
    @keyframes textFade {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 1; }
    }
    .animate-logo-blink { animation: logoBlink 1.6s ease-in-out infinite; }
    .animate-ping-slow { animation: pingSlow 2.5s cubic-bezier(0, 0, 0.2, 1) infinite; }
    .animate-spin-fast { animation: spinFast 0.8s linear infinite; }
    .animate-spin-reverse { animation: spinReverse 1.2s linear infinite; }
    .animate-text-fade { animation: textFade 1.6s ease-in-out infinite; }

    #globalLoader.loader-hidden {
        opacity: 0;
        pointer-events: none;
    }
</style>

<script>
    (function() {
        var MIN_DISPLAY_MS = 2500;
        var startTime = Date.now();

        function hideLoader() {
            var elapsed = Date.now() - startTime;
            var remaining = MIN_DISPLAY_MS - elapsed;

            if (remaining > 0) {
                setTimeout(hideLoader, remaining);
                return;
            }

            var loader = document.getElementById('globalLoader');
            if (loader) {
                loader.classList.add('loader-hidden');
                setTimeout(function() { loader.remove(); }, 600);
            }
        }

        if (document.readyState === 'complete') {
            hideLoader();
        } else {
            window.addEventListener('load', hideLoader);
        }

        window.showLoader = function() {
            var loader = document.getElementById('globalLoader');
            if (loader) {
                loader.classList.remove('loader-hidden');
            }
        };

        window.hideLoader = hideLoader;
    })();
</script>

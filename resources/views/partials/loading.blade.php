{{-- Global Loading Overlay --}}
<div id="globalLoader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white transition-opacity duration-500">
    <div class="flex flex-col items-center gap-6">
        {{-- Logo with pulse-blink animation --}}
        <div class="loader-logo-wrap relative">
            <div class="absolute inset-0 rounded-2xl bg-[#6E7A25]/20 blur-xl animate-ping-slow"></div>
            <img src="{{ asset('whitelogo.png') }}" alt="Nutrio Meals" class="relative h-20 w-auto object-contain animate-logo-blink">
        </div>

        {{-- Spinner ring --}}
        <div class="relative w-10 h-10">
            <div class="absolute inset-0 rounded-full border-2 border-gray-100"></div>
            <div class="absolute inset-0 rounded-full border-2 border-transparent border-t-[#6E7A25] animate-spin-fast"></div>
        </div>

        {{-- Loading text --}}
        <p class="text-xs font-semibold text-gray-400 tracking-widest uppercase animate-text-fade">Loading...</p>
    </div>
</div>

<style>
    @keyframes logoBlink {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(0.95); }
    }
    @keyframes pingSlow {
        0% { transform: scale(1); opacity: 0.4; }
        75%, 100% { transform: scale(1.8); opacity: 0; }
    }
    @keyframes spinFast {
        to { transform: rotate(360deg); }
    }
    @keyframes textFade {
        0%, 100% { opacity: 0.4; }
        50% { opacity: 1; }
    }
    .animate-logo-blink { animation: logoBlink 1.4s ease-in-out infinite; }
    .animate-ping-slow { animation: pingSlow 2s cubic-bezier(0, 0, 0.2, 1) infinite; }
    .animate-spin-fast { animation: spinFast 0.7s linear infinite; }
    .animate-text-fade { animation: textFade 1.4s ease-in-out infinite; }

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

<section class="py-20 lg:py-28 relative overflow-hidden transition-colors duration-300">
    {{-- Animated gradient background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-[#033133] via-[#0a4a3a] to-[#259B00]"></div>

    {{-- Decorative circles --}}
    <div class="absolute top-0 right-0 w-96 h-96 rounded-full bg-white/5 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 rounded-full bg-white/5 blur-3xl"></div>

    {{-- Grid pattern overlay --}}
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"1\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"1\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center scroll-reveal">
            {{-- Badge --}}
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-sm text-white text-xs font-bold uppercase tracking-wider mb-6 border border-white/20">
                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                Get Started Today
            </span>

            {{-- Heading --}}
            <h2 class="text-3xl sm:text-4xl lg:text-6xl font-extrabold text-white mb-5 leading-tight">Start Your Healthy Journey Today</h2>
            <p class="text-lg sm:text-xl text-white/80 mb-10 max-w-2xl mx-auto leading-relaxed">Join hundreds of people in Saudi Arabia eating smarter, training harder, and living healthier with Nutrio Meals.</p>

            {{-- Buttons --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12">
                <a href="#plans" class="inline-flex items-center gap-2 px-8 py-3.5 text-sm font-bold text-[#033133] bg-white hover:bg-gray-100 rounded-xl shadow-xl hover:-translate-y-0.5 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Get Started
                </a>
                <a href="#calculator" class="inline-flex items-center gap-2 px-8 py-3.5 text-sm font-bold text-white bg-white/10 backdrop-blur-sm border border-white/30 hover:bg-white/20 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Calculate Nutrition
                </a>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-4 sm:gap-8 max-w-2xl mx-auto pt-8 border-t border-white/20">
                <div class="text-center">
                    <p class="text-3xl sm:text-4xl font-extrabold text-white">500+</p>
                    <p class="text-xs sm:text-sm text-white/60 mt-1 uppercase tracking-wider">Happy Customers</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl sm:text-4xl font-extrabold text-white">12K+</p>
                    <p class="text-xs sm:text-sm text-white/60 mt-1 uppercase tracking-wider">Meals Delivered</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl sm:text-4xl font-extrabold text-white">4.9</p>
                    <p class="text-xs sm:text-sm text-white/60 mt-1 uppercase tracking-wider">Average Rating</p>
                </div>
            </div>
        </div>
    </div>
</section>

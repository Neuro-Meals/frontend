{{-- Shared page hero --}}
<section class="bg-gradient-to-br from-[#173327] via-[#0a4a3a] to-[#6E7A25] py-16 lg:py-24 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 rounded-full bg-white/5 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 rounded-full bg-white/5 blur-3xl"></div>
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="max-w-3xl">
            <nav class="flex items-center gap-2 text-white/60 text-sm mb-4">
                <a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-white">{{ $title }}</span>
            </nav>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white mb-4 leading-tight">{{ $title }}</h1>
            <p class="text-lg text-white/80 leading-relaxed">{{ $description ?? '' }}</p>
        </div>
    </div>
</section>

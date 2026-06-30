@extends('layouts.landing')

@section('title', $title . ' - Nutrio Meals')

@section('content')
@include('landing.partials.header')

{{-- Page Hero --}}
<section class="bg-gradient-to-br from-[#033133] via-[#0a4a3a] to-[#259B00] py-16 lg:py-24 relative overflow-hidden">
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
            <p class="text-lg text-white/80 leading-relaxed">{{ $description }}</p>
        </div>
    </div>
</section>

{{-- Dynamic Sections --}}
@foreach($sections as $index => $section)
    @php $isEven = $index % 2 === 0; @endphp

    @if($section['type'] === 'text')
    {{-- Text Section --}}
    <section class="py-12 lg:py-16 {{ $isEven ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} transition-colors duration-300">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ $section['heading'] }}</h2>
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-base sm:text-lg">{{ $section['body'] }}</p>
        </div>
    </section>

    @elseif($section['type'] === 'cards')
    {{-- Cards Section --}}
    <section class="py-12 lg:py-16 {{ $isEven ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} transition-colors duration-300">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">{{ $section['heading'] }}</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($section['cards'] as $card)
                <div class="bg-white dark:bg-gray-700 rounded-xl border border-gray-100 dark:border-gray-600 p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#033133] to-[#259B00] flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $card['title'] }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-300 leading-relaxed">{{ $card['text'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    @elseif($section['type'] === 'steps')
    {{-- Steps Section --}}
    <section class="py-12 lg:py-16 {{ $isEven ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} transition-colors duration-300">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">{{ $section['heading'] }}</h2>
            <div class="space-y-6">
                @foreach($section['steps'] as $i => $step)
                <div class="flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-[#033133] to-[#259B00] flex items-center justify-center text-white font-bold text-lg">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-1 pt-1">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ $step['title'] }}</h3>
                        <p class="text-gray-500 dark:text-gray-300 leading-relaxed">{{ $step['text'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    @elseif($section['type'] === 'faq')
    {{-- FAQ Section --}}
    <section class="py-12 lg:py-16 {{ $isEven ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} transition-colors duration-300">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">{{ $section['heading'] }}</h2>
            <div class="space-y-3" x-data="{ open: null }">
                @foreach($section['items'] as $i => $item)
                <div class="bg-white dark:bg-gray-700 rounded-xl border border-gray-100 dark:border-gray-600 overflow-hidden">
                    <button onclick="toggleFaq({{ $i }})" class="w-full flex items-center justify-between px-5 py-4 text-left" id="faq-btn-{{ $i }}">
                        <span class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white">{{ $item['q'] }}</span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform flex-shrink-0 ml-4" id="faq-icon-{{ $i }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="faq-content-{{ $i }}" class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                        <p class="px-5 pb-4 text-sm text-gray-500 dark:text-gray-300 leading-relaxed">{{ $item['a'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    @elseif($section['type'] === 'contact')
    {{-- Contact Form Section --}}
    <section class="py-12 lg:py-16 {{ $isEven ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} transition-colors duration-300">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">{{ $section['heading'] }}</h2>
            <form action="#" method="POST" class="bg-white dark:bg-gray-700 rounded-xl border border-gray-100 dark:border-gray-600 p-6 sm:p-8 space-y-5">
                @csrf
                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Full Name</label>
                        <input type="text" name="name" required class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#259B00] focus:ring-[#259B00] outline-none transition-colors" placeholder="Your name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Email Address</label>
                        <input type="email" name="email" required class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#259B00] focus:ring-[#259B00] outline-none transition-colors" placeholder="you@example.com">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Subject</label>
                    <input type="text" name="subject" required class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#259B00] focus:ring-[#259B00] outline-none transition-colors" placeholder="How can we help?">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Message</label>
                    <textarea name="message" rows="5" required class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#259B00] focus:ring-[#259B00] outline-none transition-colors resize-none" placeholder="Tell us more..."></textarea>
                </div>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-[#033133] to-[#259B00] rounded-lg hover:shadow-lg hover:shadow-[#259B00]/30 focus:ring-4 focus:ring-[#259B00]/20 transition-all">
                    Send Message
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </form>
        </div>
    </section>

    @elseif($section['type'] === 'cta')
    {{-- CTA Section --}}
    <section class="py-12 lg:py-16 {{ $isEven ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} transition-colors duration-300">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-[#033133] via-[#0a4a3a] to-[#259B00] rounded-2xl p-8 sm:p-12 text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-white/5 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full bg-white/5 blur-3xl"></div>
                <div class="relative">
                    <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">{{ $section['heading'] }}</h2>
                    <a href="{{ url($section['button_link']) }}" class="inline-flex items-center gap-2 px-8 py-3.5 text-base font-bold text-[#033133] bg-white hover:bg-gray-100 rounded-xl shadow-xl hover:-translate-y-0.5 transition-all">
                        {{ $section['button_text'] }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif
@endforeach

@include('landing.partials.footer')

<script>
function toggleFaq(id) {
    const content = document.getElementById('faq-content-' + id);
    const icon = document.getElementById('faq-icon-' + id);
    const isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';

    // Close all
    document.querySelectorAll('.faq-content').forEach(c => c.style.maxHeight = '0px');
    document.querySelectorAll('[id^="faq-icon-"]').forEach(i => i.classList.remove('rotate-180'));

    // Open clicked
    if (!isOpen) {
        content.style.maxHeight = content.scrollHeight + 'px';
        icon.classList.add('rotate-180');
    }
}
</script>
@endsection

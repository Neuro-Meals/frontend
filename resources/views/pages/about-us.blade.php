@extends('layouts.landing')

@section('title', 'About Us - Nutrio Meals')

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => 'About Us', 'description' => 'Learn about Nutrio Meals — our mission, our story, and our commitment to healthy eating in Saudi Arabia.'])

{{-- Our Story --}}
<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">Our Story</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-base sm:text-lg">
            Nutrio Meals was born from a simple idea: eating healthy should be easy, delicious, and accessible to everyone in Saudi Arabia. We started as a small kitchen with big dreams — to transform how people think about meal subscriptions. Today, we deliver thousands of fresh, nutritionist-approved meals across Riyadh and beyond, helping our customers achieve their fitness and health goals without sacrificing taste or convenience.
        </p>
    </div>
</section>

{{-- What Drives Us --}}
<section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">What Drives Us</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Our Mission', 'text' => 'To make healthy eating effortless and enjoyable for everyone, every single day.'],
                ['icon' => 'M5 13l4 4L19 7', 'title' => 'Quality First', 'text' => 'Every meal is prepared with premium ingredients and approved by certified nutritionists.'],
                ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Customer Focused', 'text' => 'Our subscribers are at the heart of everything we do. Your goals are our goals.'],
                ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Trust & Safety', 'text' => 'We maintain the highest food safety standards in every kitchen and delivery.'],
            ] as $card)
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

{{-- Our Commitment --}}
<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">Our Commitment</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-base sm:text-lg">
            We believe that nutrition is the foundation of a healthy lifestyle. That is why every meal we create is carefully balanced — the right calories, the right macros, and the right flavors. Whether you are building muscle, losing weight, or simply eating better, we have a plan designed for you. Our team of chefs and nutritionists work together to ensure that healthy never means boring.
        </p>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-[#033133] via-[#0a4a3a] to-[#259B00] rounded-2xl p-8 sm:p-12 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-white/5 blur-3xl"></div>
            <div class="relative">
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Ready to start your journey?</h2>
                <a href="{{ url('/#plans') }}" class="inline-flex items-center gap-2 px-8 py-3.5 text-base font-bold text-[#033133] bg-white hover:bg-gray-100 rounded-xl shadow-xl hover:-translate-y-0.5 transition-all">
                    Explore Meal Plans
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

@include('landing.partials.footer')
@endsection

@extends('layouts.landing')

@section('title', 'Food Safety - Nutrio Meals')

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => 'Food Safety', 'description' => 'Our commitment to the highest food safety standards.'])

<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">Our Commitment to Safety</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-base sm:text-lg">
            Food safety is our top priority. Our kitchen follows HACCP (Hazard Analysis Critical Control Point) standards and is regularly inspected by the Saudi Food and Drug Authority (SFDA).
        </p>
    </div>
</section>

<section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">Our Safety Standards</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Certified Kitchen', 'text' => 'Our facilities are SFDA certified and HACCP compliant.'],
                ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857', 'title' => 'Trained Staff', 'text' => 'All chefs and kitchen staff are certified in food safety handling.'],
                ['icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4', 'title' => 'Quality Ingredients', 'text' => 'We source from approved suppliers with full traceability.'],
                ['icon' => 'M21 13.255A23.931 23.931 0 0112 15', 'title' => 'Temperature Control', 'text' => 'Meals are kept at safe temperatures from kitchen to delivery.'],
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

<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">Allergen Information</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-base sm:text-lg">
            All meals are labeled with allergen information. If you have specific allergies or dietary restrictions, please note them in your account settings. Our team takes every precaution to prevent cross-contamination.
        </p>
    </div>
</section>

<section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">Reporting Concerns</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-base sm:text-lg">
            If you have any food safety concerns, please contact us immediately at safety@nutriomeals.com. We take all reports seriously and investigate promptly.
        </p>
    </div>
</section>

@include('landing.partials.footer')
@endsection

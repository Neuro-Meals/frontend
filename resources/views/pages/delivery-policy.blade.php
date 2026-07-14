@extends('layouts.landing')

@section('title', __('Delivery Policy') . ' - ' . __('Nutrio Meals'))

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => __('Delivery Policy'), 'description' => __('Our policy for delivery addresses, timing, and exceptions.')])

@php
    $sections = [
        [
            'heading' => __('Delivery Guidelines'),
            'points' => [
                __('Customers should provide a complete and accurate delivery address.'),
                __('Delivery times are estimates and may vary due to traffic, weather, or operational conditions.'),
                __('If delivery cannot be completed because of incorrect customer information or customer unavailability, NutrioMeals will work with the customer to arrange a suitable solution.'),
            ],
        ],
    ];
@endphp

@foreach($sections as $section)
<section class="py-12 {{ $loop->iteration % 2 === 1 ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ $section['heading'] }}</h2>
        <ul class="space-y-3">
            @foreach($section['points'] as $point)
            <li class="flex items-start gap-3 text-gray-600 dark:text-gray-300 leading-relaxed">
                <svg class="w-5 h-5 text-[#6E7A25] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ $point }}</span>
            </li>
            @endforeach
        </ul>
    </div>
</section>
@endforeach

{{-- Contact section --}}
<section class="py-12 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Contact') }}</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ __('For questions regarding these policies, please contact NutrioMeals Customer Support through the official website or application.') }}</p>
    </div>
</section>

@include('landing.partials.footer')
@endsection

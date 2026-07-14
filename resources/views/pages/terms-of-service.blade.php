@extends('layouts.landing')

@section('title', __('Terms & Conditions') . ' - ' . __('Nutrio Meals'))

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => __('Terms & Conditions'), 'description' => __('The terms and conditions for using Nutrio Meals services.')])

@php
    $sections = [
        [
            'heading' => __('Acceptance of Terms'),
            'points' => [
                __('NutrioMeals provides healthy meal subscription services through its website and mobile application.'),
                __('By creating an account or purchasing a subscription, you agree to these Terms & Conditions.'),
                __('Customers are responsible for providing accurate personal, delivery, and payment information.'),
                __('Subscription plans begin only after successful payment confirmation.'),
                __('Meal availability may vary based on operational requirements.'),
                __('NutrioMeals may update menus, ingredients, or delivery schedules when necessary while maintaining equivalent quality.'),
                __('Customers must notify NutrioMeals of allergies or dietary restrictions before ordering.'),
                __('Misuse of the platform, fraudulent activity, or violation of these terms may result in account suspension.'),
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

@include('landing.partials.footer')
@endsection

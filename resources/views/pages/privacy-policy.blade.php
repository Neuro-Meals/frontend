@extends('layouts.landing')

@section('title', __('Privacy Policy') . ' - ' . __('Nutrio Meals'))

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => __('Privacy Policy'), 'description' => __('How NutrioMeals collects, uses, and protects your personal information.')])

@php
    $sections = [
        [
            'heading' => __('Information We Collect'),
            'points' => [
                __('NutrioMeals collects only the information necessary to provide its services, including name, email, phone number, delivery address, and payment information.'),
                __('Payment card information is processed securely by certified payment providers such as Tap Payments and is not stored by NutrioMeals.'),
                __('Personal information is used only for account management, order fulfillment, customer support, and service improvement.'),
                __('NutrioMeals does not sell customer personal information to third parties.'),
                __('Customer information may be shared only with trusted service providers such as payment processors and delivery partners when required to complete the service.'),
                __('Reasonable administrative and technical safeguards are implemented to protect customer data.'),
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

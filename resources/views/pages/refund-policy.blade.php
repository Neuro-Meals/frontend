@extends('layouts.landing')

@section('title', __('Return & Refund Policy') . ' - ' . __('Nutrio Meals'))

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => __('Return & Refund Policy'), 'description' => __('Our policy for returns, refunds, pauses, and cancellations.')])

@php
    $sections = [
        [
            'heading' => __('Returns & Refunds'),
            'points' => [
                __('Because NutrioMeals delivers freshly prepared food, delivered meals cannot normally be returned.'),
                __('Refunds may be approved when NutrioMeals is unable to fulfill an order, charges a customer incorrectly, or delivers an incorrect or significantly damaged order.'),
                __('Approved refunds will be returned using the original payment method whenever possible.'),
                __('Refund processing time depends on the customer\'s payment provider or bank.'),
            ],
        ],
        [
            'heading' => __('Pause & Resume'),
            'points' => [
                __('If a customer wishes to temporarily stop receiving meals, they may use the Pause Subscription feature instead of requesting a refund.'),
                __('Paused subscriptions preserve the customer\'s remaining subscription days. When the customer resumes the subscription, the remaining days continue from where they stopped.'),
            ],
        ],
        [
            'heading' => __('Plan Changes & Cancellations'),
            'points' => [
                __('Customers may request to upgrade or downgrade their subscription plan. Any price difference will be calculated according to the applicable plan and payment policy.'),
                __('Cancellation requests submitted before service activation may be eligible for review. Requests after meals have already been prepared or delivered may not qualify for a refund.'),
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

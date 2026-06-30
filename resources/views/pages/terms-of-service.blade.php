@extends('layouts.landing')

@section('title', 'Terms of Service - Nutrio Meals')

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => 'Terms of Service', 'description' => 'The terms and conditions for using Nutrio Meals services.'])

@php
    $sections = [
        ['heading' => 'Acceptance of Terms', 'body' => 'By accessing and using Nutrio Meals services, you accept and agree to be bound by these Terms of Service. If you do not agree, please do not use our services.'],
        ['heading' => 'Subscriptions', 'body' => 'Subscriptions are billed in advance on a weekly, monthly, or annual basis depending on your selected plan. You can pause, modify, or cancel your subscription at any time through your dashboard. Cancellations take effect at the end of the current billing cycle.'],
        ['heading' => 'Delivery', 'body' => 'We strive to deliver all orders on time. However, we are not liable for delays caused by circumstances beyond our control. If you are not satisfied with your delivery, contact us within 24 hours for a resolution.'],
        ['heading' => 'Food Quality', 'body' => 'All meals are prepared fresh daily. We maintain strict food safety standards. If you receive a meal that does not meet our quality standards, contact us within 24 hours for a refund or replacement.'],
        ['heading' => 'Limitation of Liability', 'body' => 'Nutrio Meals is not liable for any indirect, incidental, or consequential damages arising from the use of our services. Our total liability shall not exceed the amount you have paid for the service in question.'],
    ];
@endphp

@foreach($sections as $section)
<section class="py-12 {{ $loop->iteration % 2 === 1 ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $section['heading'] }}</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $section['body'] }}</p>
    </div>
</section>
@endforeach

@include('landing.partials.footer')
@endsection

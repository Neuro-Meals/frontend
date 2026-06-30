@extends('layouts.landing')

@section('title', 'Refund Policy - Nutrio Meals')

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => 'Refund Policy', 'description' => 'Our policy for refunds and replacements.'])

@php
    $sections = [
        ['heading' => 'Satisfaction Guarantee', 'body' => 'We stand behind the quality of our meals. If you are not satisfied with your order, contact us within 24 hours of delivery and we will arrange a refund or replacement.'],
        ['heading' => 'Subscription Refunds', 'body' => 'If you cancel your subscription, you will not be charged for the next billing cycle. Refunds for the current cycle are issued on a pro-rata basis for any undelivered meals. Processing time is 5-10 business days.'],
        ['heading' => 'Non-Refundable Cases', 'body' => 'Refunds are not issued for: meals that have been consumed, orders not reported within 24 hours, or cancellations made after delivery has been completed.'],
        ['heading' => 'How to Request a Refund', 'body' => 'To request a refund, contact our support team at support@nutriomeals.com or via WhatsApp at +966 50 123 4567. Include your order number and reason for the refund request.'],
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

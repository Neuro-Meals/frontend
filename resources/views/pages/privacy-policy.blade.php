@extends('layouts.landing')

@section('title', 'Privacy Policy - Nutrio Meals')

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => 'Privacy Policy', 'description' => 'How Nutrio Meals collects, uses, and protects your personal information.'])

@php
    $sections = [
        ['heading' => 'Introduction', 'body' => 'At Nutrio Meals, we take your privacy seriously. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our website and services. Please read this policy carefully.'],
        ['heading' => 'Information We Collect', 'body' => 'We collect information you provide directly to us, such as your name, email address, phone number, delivery address, and payment information when you create an account or place an order. We also automatically collect certain information about your device and usage patterns.'],
        ['heading' => 'How We Use Your Information', 'body' => 'We use your information to process orders, deliver meals, manage subscriptions, send notifications, improve our services, and communicate with you about promotions and updates. We do not sell your personal information to third parties.'],
        ['heading' => 'Data Security', 'body' => 'We implement industry-standard security measures to protect your personal information, including encryption, secure servers, and access controls. However, no method of transmission over the internet is 100% secure.'],
        ['heading' => 'Your Rights', 'body' => 'You have the right to access, update, or delete your personal information. You can also opt out of marketing communications at any time. To exercise these rights, contact us at privacy@nutriomeals.com.'],
        ['heading' => 'Contact Us', 'body' => 'If you have questions about this Privacy Policy, please contact us at privacy@nutriomeals.com or +966 50 123 4567.'],
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

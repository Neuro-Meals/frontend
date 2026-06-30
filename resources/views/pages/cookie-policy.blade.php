@extends('layouts.landing')

@section('title', 'Cookie Policy - Nutrio Meals')

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => 'Cookie Policy', 'description' => 'How Nutrio Meals uses cookies and similar technologies.'])

@php
    $sections = [
        ['heading' => 'What Are Cookies?', 'body' => 'Cookies are small text files stored on your device when you visit a website. They help us remember your preferences, keep you logged in, and understand how you use our services.'],
        ['heading' => 'Types of Cookies We Use', 'body' => 'Essential cookies: Required for the website to function properly. Preference cookies: Remember your settings and preferences. Analytics cookies: Help us understand how visitors use our site. Marketing cookies: Used to show you relevant advertisements.'],
        ['heading' => 'Managing Cookies', 'body' => 'You can control and delete cookies through your browser settings. Disabling essential cookies may affect website functionality. You can also opt out of analytics and marketing cookies at any time.'],
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

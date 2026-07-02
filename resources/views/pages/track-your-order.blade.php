@extends('layouts.landing')

@section('title', 'Track Your Order - Nutrio Meals')

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => 'Track Your Order', 'description' => 'Follow your meal delivery in real-time from our kitchen to your doorstep.'])

<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">Real-Time Tracking</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-base sm:text-lg">
            Once your order is dispatched, you will receive a tracking link via SMS and email. Click the link to see your delivery status in real-time, including estimated arrival time and driver contact information.
        </p>
    </div>
</section>

<section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">How Tracking Works</h2>
        <div class="space-y-6">
            @foreach([
                ['title' => 'Order Confirmed', 'text' => 'You receive an SMS confirmation once your order is placed.'],
                ['title' => 'Meal Preparation', 'text' => 'Our chefs prepare your meals fresh daily. You will be notified when preparation begins.'],
                ['title' => 'Out for Delivery', 'text' => 'Receive a live tracking link when your order is on the way.'],
                ['title' => 'Delivered', 'text' => 'Get a notification the moment your meals arrive at your door.'],
            ] as $i => $step)
            <div class="flex items-start gap-4 sm:gap-6">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center text-white font-bold text-lg">
                    {{ $i + 1 }}
                </div>
                <div class="flex-1 pt-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ $step['title'] }}</h3>
                    <p class="text-gray-500 dark:text-gray-300 leading-relaxed">{{ $step['text'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">Delivery Areas</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-base sm:text-lg">
            We currently deliver across Riyadh, Jeddah, and Dammam. New areas are being added regularly. If you are unsure whether we deliver to your location, please contact our support team.
        </p>
    </div>
</section>

<section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-[#173327] via-[#0a4a3a] to-[#6E7A25] rounded-2xl p-8 sm:p-12 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-white/5 blur-3xl"></div>
            <div class="relative">
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Need help with your order?</h2>
                <a href="{{ route('page.show', 'contact-support') }}" class="inline-flex items-center gap-2 px-8 py-3.5 text-base font-bold text-[#173327] bg-white hover:bg-gray-100 rounded-xl shadow-xl hover:-translate-y-0.5 transition-all">
                    Contact Support
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

@include('landing.partials.footer')
@endsection

@extends('layouts.landing')

@section('title', 'Help Center - Nutrio Meals')

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => 'Help Center', 'description' => 'Find guides, tutorials, and answers to help you get the most out of Nutrio Meals.'])

<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">Browse by Category</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2', 'title' => 'Getting Started', 'text' => 'New to Nutrio Meals? Learn how to set up your account and place your first order.'],
                ['icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9', 'title' => 'Managing Subscriptions', 'text' => 'Learn how to pause, skip, upgrade, or cancel your subscription.'],
                ['icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4', 'title' => 'Delivery & Tracking', 'text' => 'Everything about delivery times, areas, and tracking your orders.'],
                ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2', 'title' => 'Payments & Billing', 'text' => 'Manage payment methods, view invoices, and understand billing cycles.'],
                ['icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01', 'title' => 'Nutrition & Meal Plans', 'text' => 'Understand macros, calories, and choosing the right plan for your goals.'],
                ['icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0', 'title' => 'Account Settings', 'text' => 'Update your profile, change your password, and manage preferences.'],
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

<section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">Still Need Help?</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-base sm:text-lg">
            Our support team is available Sunday through Thursday, 9 AM to 9 PM. Reach out via contact form, WhatsApp, or phone, and we will get back to you as soon as possible.
        </p>
    </div>
</section>

<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-[#033133] via-[#0a4a3a] to-[#259B00] rounded-2xl p-8 sm:p-12 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-white/5 blur-3xl"></div>
            <div class="relative">
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Cannot find what you are looking for?</h2>
                <a href="{{ route('page.show', 'contact-support') }}" class="inline-flex items-center gap-2 px-8 py-3.5 text-base font-bold text-[#033133] bg-white hover:bg-gray-100 rounded-xl shadow-xl hover:-translate-y-0.5 transition-all">
                    Contact Support
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

@include('landing.partials.footer')
@endsection

@extends('layouts.landing')

@section('title', 'Order History - Nutrio Meals')

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => 'Order History', 'description' => 'View all your past orders, reorder favorites, and download invoices.'])

<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">Your Orders</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-base sm:text-lg">
            Sign in to view your complete order history. You can filter by date, plan type, or status. Reorder your favorite meals with a single click, or download invoices for your records.
        </p>
    </div>
</section>

<section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">Order Management</h2>
        <div class="grid sm:grid-cols-3 gap-6">
            @foreach([
                ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2', 'title' => 'View Past Orders', 'text' => 'See every order you have placed with full details.'],
                ['icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9', 'title' => 'Reorder Easily', 'text' => 'Reorder your favorite meal plans with one click.'],
                ['icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4', 'title' => 'Download Invoices', 'text' => 'Get PDF invoices for all your orders.'],
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

<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-[#033133] via-[#0a4a3a] to-[#259B00] rounded-2xl p-8 sm:p-12 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-white/5 blur-3xl"></div>
            <div class="relative">
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Sign in to view your orders</h2>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-3.5 text-base font-bold text-[#033133] bg-white hover:bg-gray-100 rounded-xl shadow-xl hover:-translate-y-0.5 transition-all">
                    Sign In
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

@include('landing.partials.footer')
@endsection

@extends('layouts.landing')

@section('title', 'Refer a Friend - Nutrio Meals')

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => 'Refer a Friend', 'description' => 'Give your friends a discount and earn reward points for yourself.'])

<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">Share the Health</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-base sm:text-lg">
            Love Nutrio Meals? Share it with your friends! When they sign up using your referral link, they get SAR 50 off their first order, and you earn 500 reward points. Everybody wins.
        </p>
    </div>
</section>

<section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">How It Works</h2>
        <div class="space-y-6">
            @foreach([
                ['title' => 'Get Your Link', 'text' => 'Sign in and copy your unique referral link from your dashboard.'],
                ['title' => 'Share With Friends', 'text' => 'Send your link via WhatsApp, social media, or email.'],
                ['title' => 'They Get SAR 50 Off', 'text' => 'Your friend gets SAR 50 discount on their first subscription order.'],
                ['title' => 'You Get 500 Points', 'text' => 'Earn 500 reward points for each successful referral.'],
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
        <div class="bg-gradient-to-br from-[#173327] via-[#0a4a3a] to-[#6E7A25] rounded-2xl p-8 sm:p-12 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-white/5 blur-3xl"></div>
            <div class="relative">
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Start referring friends today</h2>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-3.5 text-base font-bold text-[#173327] bg-white hover:bg-gray-100 rounded-xl shadow-xl hover:-translate-y-0.5 transition-all">
                    Get Your Referral Link
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

@include('landing.partials.footer')
@endsection

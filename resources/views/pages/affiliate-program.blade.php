@extends('layouts.landing')

@section('title', 'Affiliate Program - Nutrio Meals')

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => 'Affiliate Program', 'description' => 'Earn commission by referring customers to Nutrio Meals.'])

<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">Earn While You Share</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-base sm:text-lg">
            Join our affiliate program and earn commission for every new subscriber you refer. Whether you are a fitness influencer, blogger, or just love our meals — you can earn by sharing Nutrio Meals with your network.
        </p>
    </div>
</section>

<section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">How It Works</h2>
        <div class="space-y-6">
            @foreach([
                ['title' => 'Sign Up', 'text' => 'Register for our affiliate program and get your unique referral link.'],
                ['title' => 'Share', 'text' => 'Share your link on social media, your blog, or with friends and family.'],
                ['title' => 'Earn', 'text' => 'Get commission for every new paying subscriber that signs up through your link.'],
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
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">Program Benefits</h2>
        <div class="grid sm:grid-cols-3 gap-6">
            @foreach([
                ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2', 'title' => 'Competitive Commission', 'text' => 'Earn up to 15% commission on every subscription you refer.'],
                ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Transparent Tracking', 'text' => 'Real-time dashboard to track your referrals and earnings.'],
                ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Fast Payouts', 'text' => 'Monthly payouts via bank transfer or STC Pay.'],
            ] as $card)
            <div class="bg-white dark:bg-gray-700 rounded-xl border border-gray-100 dark:border-gray-600 p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center mb-4">
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
        <div class="bg-gradient-to-br from-[#173327] via-[#0a4a3a] to-[#6E7A25] rounded-2xl p-8 sm:p-12 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-white/5 blur-3xl"></div>
            <div class="relative">
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Ready to start earning?</h2>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-3.5 text-base font-bold text-[#173327] bg-white hover:bg-gray-100 rounded-xl shadow-xl hover:-translate-y-0.5 transition-all">
                    Sign Up Now
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

@include('landing.partials.footer')
@endsection

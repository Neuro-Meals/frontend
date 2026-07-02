@extends('layouts.landing')

@section('title', 'Partner With Us - Nutrio Meals')

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => 'Partner With Us', 'description' => 'Join forces with Nutrio Meals and grow your business with Saudi Arabia\'s leading meal subscription service.'])

<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">Why Partner With Nutrio Meals?</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-base sm:text-lg">
            We are always looking for strategic partners who share our vision of a healthier Saudi Arabia. Whether you are a gym, fitness center, corporate office, or health brand, we offer partnership programs tailored to your needs.
        </p>
    </div>
</section>

<section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">Partnership Opportunities</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2', 'title' => 'Gym & Fitness Partners', 'text' => 'Offer Nutrio Meals to your members with exclusive discounts.'],
                ['icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'title' => 'Corporate Wellness', 'text' => 'Provide healthy meal plans as part of your employee wellness program.'],
                ['icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13l3-3m0 0l-3-3m3 3H8', 'title' => 'Health Brands', 'text' => 'Co-brand and cross-promote with Nutrio Meals campaigns.'],
                ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Influencer Program', 'text' => 'Promote Nutrio Meals and earn commission on referrals.'],
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

<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">Get in Touch</h2>
        <form action="#" method="POST" class="bg-white dark:bg-gray-700 rounded-xl border border-gray-100 dark:border-gray-600 p-6 sm:p-8 space-y-5">
            @csrf
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Full Name</label>
                    <input type="text" name="name" required class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#6E7A25] focus:ring-[#6E7A25] outline-none transition-colors" placeholder="Your name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Company</label>
                    <input type="text" name="company" required class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#6E7A25] focus:ring-[#6E7A25] outline-none transition-colors" placeholder="Company name">
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Email</label>
                    <input type="email" name="email" required class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#6E7A25] focus:ring-[#6E7A25] outline-none transition-colors" placeholder="you@company.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Phone</label>
                    <input type="tel" name="phone" class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#6E7A25] focus:ring-[#6E7A25] outline-none transition-colors" placeholder="+966...">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Partnership Type</label>
                <select name="type" class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#6E7A25] focus:ring-[#6E7A25] outline-none transition-colors">
                    <option>Gym & Fitness Partners</option>
                    <option>Corporate Wellness</option>
                    <option>Health Brands</option>
                    <option>Influencer Program</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Message</label>
                <textarea name="message" rows="4" class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#6E7A25] focus:ring-[#6E7A25] outline-none transition-colors resize-none" placeholder="Tell us about your partnership idea..."></textarea>
            </div>
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-lg hover:shadow-lg hover:shadow-[#6E7A25]/30 focus:ring-4 focus:ring-[#6E7A25]/20 transition-all">
                Submit Partnership Request
            </button>
        </form>
    </div>
</section>

@include('landing.partials.footer')
@endsection

@extends('layouts.landing')

@section('title', 'Collaboration - Nutrio Meals')

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => 'Collaboration Opportunities', 'description' => 'Let us create something amazing together — events, content, and more.'])

<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">Let Us Collaborate</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-base sm:text-lg">
            We love working with like-minded brands and creators. From co-branded meal launches to fitness events and content collaborations, we are open to creative ideas that promote healthy living.
        </p>
    </div>
</section>

<section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">Collaboration Types</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15', 'title' => 'Co-Branded Meals', 'text' => 'Create a signature meal with our chefs and your brand.'],
                ['icon' => 'M21 13.255A23.931 23.931 0 0112 15', 'title' => 'Events & Pop-Ups', 'text' => 'Host healthy eating events, workshops, or pop-up kitchens together.'],
                ['icon' => 'M15 10l4.207-4.207a1 1 0 011.414 0L22 7', 'title' => 'Content Creation', 'text' => 'Collaborate on recipes, videos, blogs, and social media content.'],
                ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857', 'title' => 'Charity Initiatives', 'text' => 'Partner on community health and food donation programs.'],
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
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">Tell Us Your Idea</h2>
        <form action="#" method="POST" class="bg-white dark:bg-gray-700 rounded-xl border border-gray-100 dark:border-gray-600 p-6 sm:p-8 space-y-5">
            @csrf
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Name</label>
                    <input type="text" name="name" required class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#6E7A25] focus:ring-[#6E7A25] outline-none transition-colors" placeholder="Your name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Brand / Organization</label>
                    <input type="text" name="brand" class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#6E7A25] focus:ring-[#6E7A25] outline-none transition-colors" placeholder="Brand name">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Email</label>
                <input type="email" name="email" required class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#6E7A25] focus:ring-[#6E7A25] outline-none transition-colors" placeholder="you@brand.com">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Collaboration Type</label>
                <select name="type" class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#6E7A25] focus:ring-[#6E7A25] outline-none transition-colors">
                    <option>Co-Branded Meals</option>
                    <option>Events & Pop-Ups</option>
                    <option>Content Creation</option>
                    <option>Charity Initiatives</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Your Idea</label>
                <textarea name="message" rows="4" class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#6E7A25] focus:ring-[#6E7A25] outline-none transition-colors resize-none" placeholder="Tell us about your collaboration idea..."></textarea>
            </div>
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-lg hover:shadow-lg hover:shadow-[#6E7A25]/30 focus:ring-4 focus:ring-[#6E7A25]/20 transition-all">
                Submit Your Idea
            </button>
        </form>
    </div>
</section>

@include('landing.partials.footer')
@endsection

@extends('layouts.landing')

@section('title', 'Sponsorship - Nutrio Meals')

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => 'Sponsorship Requests', 'description' => 'Request Nutrio Meals sponsorship for your event, team, or cause.'])

<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">Sponsorship Program</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-base sm:text-lg">
            We sponsor fitness events, sports teams, health conferences, and community initiatives that align with our mission of promoting healthy living in Saudi Arabia. If you have an event or cause that could benefit from Nutrio Meals sponsorship, we would love to hear from you.
        </p>
    </div>
</section>

<section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">What We Sponsor</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Sports Events', 'text' => 'Marathons, tournaments, and athletic competitions.'],
                ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857', 'title' => 'Fitness Challenges', 'text' => 'Gym competitions, CrossFit events, and fitness bootcamps.'],
                ['icon' => 'M21 13.255A23.931 23.931 0 0112 15', 'title' => 'Health Conferences', 'text' => 'Medical, nutrition, and wellness conferences and seminars.'],
                ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Community Causes', 'text' => 'Charity runs, food drives, and community health programs.'],
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
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">Submit Your Sponsorship Request</h2>
        <form action="#" method="POST" class="bg-white dark:bg-gray-700 rounded-xl border border-gray-100 dark:border-gray-600 p-6 sm:p-8 space-y-5">
            @csrf
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Organization Name</label>
                    <input type="text" name="organization" required class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#259B00] focus:ring-[#259B00] outline-none transition-colors" placeholder="Organization name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Contact Person</label>
                    <input type="text" name="contact" required class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#259B00] focus:ring-[#259B00] outline-none transition-colors" placeholder="Your name">
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Email</label>
                    <input type="email" name="email" required class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#259B00] focus:ring-[#259B00] outline-none transition-colors" placeholder="you@org.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Event Date</label>
                    <input type="date" name="event_date" class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#259B00] focus:ring-[#259B00] outline-none transition-colors">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Event Type</label>
                <select name="event_type" class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#259B00] focus:ring-[#259B00] outline-none transition-colors">
                    <option>Sports Events</option>
                    <option>Fitness Challenges</option>
                    <option>Health Conferences</option>
                    <option>Community Causes</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Tell us about your event</label>
                <textarea name="message" rows="4" class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#259B00] focus:ring-[#259B00] outline-none transition-colors resize-none" placeholder="Describe your event, audience size, and sponsorship needs..."></textarea>
            </div>
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-[#033133] to-[#259B00] rounded-lg hover:shadow-lg hover:shadow-[#259B00]/30 focus:ring-4 focus:ring-[#259B00]/20 transition-all">
                Submit Sponsorship Request
            </button>
        </form>
    </div>
</section>

@include('landing.partials.footer')
@endsection

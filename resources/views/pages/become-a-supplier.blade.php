@extends('layouts.landing')

@section('title', 'Become a Supplier - Nutrio Meals')

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => 'Become a Supplier', 'description' => 'Supply quality ingredients to Nutrio Meals and be part of our growing network.'])

<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">Quality Starts With Ingredients</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-base sm:text-lg">
            We source the freshest, highest-quality ingredients from trusted suppliers across Saudi Arabia. If you are a farmer, distributor, or food producer committed to quality and sustainability, we want to work with you.
        </p>
    </div>
</section>

<section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">What We Look For</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Quality Standards', 'text' => 'All suppliers must meet our strict quality and food safety standards.'],
                ['icon' => 'M5 13l4 4L19 7', 'title' => 'Fresh Produce', 'text' => 'Vegetables, fruits, herbs, and organic produce delivered fresh.'],
                ['icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4', 'title' => 'Protein Sources', 'text' => 'Chicken, beef, fish, and plant-based protein suppliers.'],
                ['icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9', 'title' => 'Local Sourcing', 'text' => 'We prioritize locally-sourced ingredients to support the Saudi economy.'],
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
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">Apply to Become a Supplier</h2>
        <form action="#" method="POST" class="bg-white dark:bg-gray-700 rounded-xl border border-gray-100 dark:border-gray-600 p-6 sm:p-8 space-y-5">
            @csrf
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Business Name</label>
                    <input type="text" name="business" required class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#6E7A25] focus:ring-[#6E7A25] outline-none transition-colors" placeholder="Your business name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Contact Person</label>
                    <input type="text" name="contact" required class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#6E7A25] focus:ring-[#6E7A25] outline-none transition-colors" placeholder="Your name">
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Email</label>
                    <input type="email" name="email" required class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#6E7A25] focus:ring-[#6E7A25] outline-none transition-colors" placeholder="you@business.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Phone</label>
                    <input type="tel" name="phone" required class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#6E7A25] focus:ring-[#6E7A25] outline-none transition-colors" placeholder="+966...">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Product Type</label>
                <select name="product_type" class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#6E7A25] focus:ring-[#6E7A25] outline-none transition-colors">
                    <option>Fresh Produce</option>
                    <option>Protein Sources</option>
                    <option>Dairy & Eggs</option>
                    <option>Grains & Spices</option>
                    <option>Other</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Tell us about your products</label>
                <textarea name="message" rows="4" class="w-full rounded-lg border border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white p-3 text-sm focus:border-[#6E7A25] focus:ring-[#6E7A25] outline-none transition-colors resize-none" placeholder="Describe your products, capacity, and certifications..."></textarea>
            </div>
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-lg hover:shadow-lg hover:shadow-[#6E7A25]/30 focus:ring-4 focus:ring-[#6E7A25]/20 transition-all">
                Submit Application
            </button>
        </form>
    </div>
</section>

@include('landing.partials.footer')
@endsection

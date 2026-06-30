@extends('layouts.landing')

@section('title', 'FAQs - Nutrio Meals')

@section('content')
@include('landing.partials.header')
@include('pages.partials.hero', ['title' => 'Frequently Asked Questions', 'description' => 'Find answers to the most common questions about Nutrio Meals.'])

@php
    $faqGroups = [
        ['heading' => 'General Questions', 'items' => [
            ['q' => 'How does Nutrio Meals work?', 'a' => 'Choose a meal plan that fits your goals, select your preferred meals from our weekly menu, and we deliver fresh meals to your door daily or weekly depending on your plan.'],
            ['q' => 'What areas do you deliver to?', 'a' => 'We currently deliver to Riyadh, Jeddah, and Dammam. We are expanding to more cities soon — follow us on social media for updates.'],
            ['q' => 'Can I customize my meals?', 'a' => 'Yes! You can customize your meals each week from our menu. We offer options for different dietary preferences including keto, vegetarian, and high-protein.'],
            ['q' => 'How fresh are the meals?', 'a' => 'All meals are prepared fresh daily by our chefs and delivered in insulated packaging to maintain temperature and quality.'],
        ]],
        ['heading' => 'Subscription & Billing', 'items' => [
            ['q' => 'Can I pause my subscription?', 'a' => 'Absolutely. You can pause your subscription anytime from your dashboard. No fees, no questions asked. Resume whenever you are ready.'],
            ['q' => 'How do I cancel?', 'a' => 'You can cancel your subscription from your account dashboard with a single click. There are no cancellation fees or contracts.'],
            ['q' => 'Do you offer refunds?', 'a' => 'If you are not satisfied with your meals, contact us within 24 hours of delivery for a refund or replacement. See our Refund Policy for details.'],
            ['q' => 'Can I switch plans?', 'a' => 'Yes, you can upgrade or downgrade your plan at any time. Changes take effect from your next billing cycle.'],
        ]],
        ['heading' => 'Delivery & Packaging', 'items' => [
            ['q' => 'What time are meals delivered?', 'a' => 'Meals are delivered between 6 AM and 10 AM daily. You will receive a notification when your delivery is on the way.'],
            ['q' => 'Do I need to be home for delivery?', 'a' => 'No, our insulated packaging keeps meals fresh for up to 4 hours. Just let us know if you have a preferred drop-off spot.'],
            ['q' => 'Is the packaging eco-friendly?', 'a' => 'Yes, we use recyclable and biodegradable packaging materials. We are committed to reducing our environmental impact.'],
        ]],
    ];
@endphp

@foreach($faqGroups as $group)
<section class="py-12 lg:py-16 {{ $loop->iteration % 2 === 1 ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} transition-colors duration-300">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">{{ $group['heading'] }}</h2>
        <div class="space-y-3">
            @foreach($group['items'] as $i => $item)
            <div class="bg-white dark:bg-gray-700 rounded-xl border border-gray-100 dark:border-gray-600 overflow-hidden">
                <button onclick="toggleFaq({{ $loop->parent->iteration }}_{{ $i }})" class="w-full flex items-center justify-between px-5 py-4 text-left">
                    <span class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white">{{ $item['q'] }}</span>
                    <svg class="w-5 h-5 text-gray-400 transition-transform flex-shrink-0 ml-4" id="faq-icon-{{ $loop->parent->iteration }}_{{ $i }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="faq-content-{{ $loop->parent->iteration }}_{{ $i }}" class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                    <p class="px-5 pb-4 text-sm text-gray-500 dark:text-gray-300 leading-relaxed">{{ $item['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endforeach

@include('landing.partials.footer')

<script>
function toggleFaq(id) {
    const content = document.getElementById('faq-content-' + id);
    const icon = document.getElementById('faq-icon-' + id);
    const isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';
    document.querySelectorAll('.faq-content').forEach(c => c.style.maxHeight = '0px');
    document.querySelectorAll('[id^="faq-icon-"]').forEach(i => i.classList.remove('rotate-180'));
    if (!isOpen) {
        content.style.maxHeight = content.scrollHeight + 'px';
        icon.classList.add('rotate-180');
    }
}
</script>
@endsection

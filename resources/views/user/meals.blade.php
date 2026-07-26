@extends('layouts.user')

@section('title', __('My Meals') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('My Meals'))

@section('content')
@php
    $weekMeals = $weekMeals ?? [];
    $todayMeals = $todayMeals ?? [];
    $todayMealsByCategory = $todayMealsByCategory ?? [];
    $hasActiveSubscription = $hasActiveSubscription ?? false;
    $activeSubscription = $activeSubscription ?? null;

    $stats = $stats ?? [
        'mealsConsumed' => 0,
        'totalPlan' => 0,
        'planProgress' => 0,
        'todayCalories' => 0,
        'calorieTarget' => 0,
        'todayProtein' => 0,
        'proteinTarget' => 0,
        'todayCarbs' => 0,
        'carbsTarget' => 0,
        'todayFat' => 0,
        'fatTarget' => 0,
        'remaining' => 0,
        'daysRemaining' => 0,
        'planRenewal' => '-',
        'avgCalories' => 0,
    ];

    $todayDate = now()->toDateString();
    $todayIndex = collect($weekMeals)->search(
        fn ($day) => ($day['date'] ?? null) === $todayDate
    );

    if ($todayIndex === false) {
        $todayIndex = 0;
    }

    $firstDate = collect($weekMeals)->pluck('date')->filter()->first();
    $lastDate = collect($weekMeals)->pluck('date')->filter()->last();

    $weekRange = '';

    if ($firstDate && $lastDate) {
        $weekRange = \Carbon\Carbon::parse($firstDate)->format('M d')
            . ' - '
            . \Carbon\Carbon::parse($lastDate)->format('M d, Y');
    }

    $totalWeekMeals = collect($weekMeals)->sum('mealCount');
    $totalWeekCalories = collect($weekMeals)->sum('calories');
@endphp

@if(session('error'))
<div class="mb-5 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
    {{ session('error') }}
</div>
@endif

@if($hasActiveSubscription)
<div class="mb-6 rounded-2xl bg-gradient-to-r from-[#173327] to-[#6E7A25] p-5 text-white shadow-lg">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-white/60">
                {{ __('Current plan') }}
            </p>
            <h2 class="mt-1 text-xl font-extrabold">
                {{ $activeSubscription['plan_name'] ?? __('Active Meal Plan') }}
            </h2>
            <p class="mt-1 text-xs text-white/70">
                {{ $stats['remaining'] }} {{ __('meals remaining') }}
                ·
                {{ $stats['daysRemaining'] }} {{ __('days remaining') }}
            </p>
        </div>

        <div class="flex flex-wrap gap-2 text-xs">
            <span class="rounded-full bg-white/15 px-3 py-1.5 font-semibold">
                {{ Str::title($activeSubscription['status'] ?? 'active') }}
            </span>
            <span class="rounded-full bg-white/15 px-3 py-1.5 font-semibold">
                {{ __('Ends') }} {{ $stats['planRenewal'] }}
            </span>
        </div>
    </div>
</div>
@else
<div class="mb-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
    <h2 class="text-base font-bold text-gray-900">
        {{ __('No active subscription') }}
    </h2>
    <p class="mt-1 text-sm text-gray-500">
        {{ __('Subscribe to a meal plan to see your scheduled meals.') }}
    </p>
    <a href="{{ route('user.subscriptions') }}"
       class="mt-4 inline-flex rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] px-4 py-2 text-xs font-bold text-white">
        {{ __('View Plans') }}
    </a>
</div>
@endif

<div class="mb-6 grid grid-cols-2 gap-3 lg:grid-cols-5">
    <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
        <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">
            {{ __('Delivered meals') }}
        </p>
        <p class="mt-2 text-2xl font-extrabold text-gray-900">
            {{ $stats['mealsConsumed'] }}
            <span class="text-sm text-gray-400">/{{ $stats['totalPlan'] }}</span>
        </p>
        <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-gray-100">
            <div class="h-full rounded-full bg-[#6E7A25]"
                 style="width: {{ $stats['planProgress'] }}%"></div>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
        <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">
            {{ __('Meals remaining') }}
        </p>
        <p class="mt-2 text-2xl font-extrabold text-gray-900">
            {{ $stats['remaining'] }}
        </p>
        <p class="mt-1 text-xs text-gray-400">
            {{ __('of') }} {{ $stats['totalPlan'] }}
        </p>
    </div>

    <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
        <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">
            {{ __('Today calories') }}
        </p>
        <p class="mt-2 text-2xl font-extrabold text-gray-900">
            {{ number_format($stats['todayCalories']) }}
        </p>
        <p class="mt-1 text-xs text-gray-400">
            {{ __('Target') }} {{ number_format($stats['calorieTarget']) }} kcal
        </p>
    </div>

    <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
        <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">
            {{ __('Today protein') }}
        </p>
        <p class="mt-2 text-2xl font-extrabold text-gray-900">
            {{ number_format($stats['todayProtein']) }}g
        </p>
        <p class="mt-1 text-xs text-gray-400">
            {{ __('Target') }} {{ number_format($stats['proteinTarget']) }}g
        </p>
    </div>

    <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
        <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">
            {{ __('Weekly average') }}
        </p>
        <p class="mt-2 text-2xl font-extrabold text-gray-900">
            {{ number_format($stats['avgCalories']) }}
        </p>
        <p class="mt-1 text-xs text-gray-400">{{ __('kcal per day') }}</p>
    </div>
</div>

<div class="mb-6">
    <div class="mb-3 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-extrabold text-gray-900">
                {{ __("Today's meals") }}
            </h2>
            <p class="text-xs text-gray-500">
                {{ now()->format('l, F j, Y') }}
            </p>
        </div>

        @if(!empty($todayMeals))
        <span class="rounded-full bg-[#6E7A25]/10 px-3 py-1 text-xs font-bold text-[#173327]">
            {{ count($todayMeals) }} {{ __('meal(s)') }}
        </span>
        @endif
    </div>

    @if(!empty($todayMealsByCategory))
        <div class="space-y-4">
            @foreach($todayMealsByCategory as $category)
            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/70 px-4 py-3">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">
                            {{ $category['name'] }}
                        </h3>
                        <p class="text-[10px] text-gray-400">
                            {{ count($category['meals']) }} {{ __('meal(s)') }}
                        </p>
                    </div>
                </div>

                <div class="grid gap-3 p-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($category['meals'] as $meal)
                    <div class="rounded-xl border border-gray-100 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h4 class="font-bold text-gray-900">
                                    {{ $meal['name'] }}
                                </h4>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ number_format($meal['calories']) }} kcal
                                    · P {{ number_format($meal['protein']) }}g
                                    · C {{ number_format($meal['carbs']) }}g
                                    · F {{ number_format($meal['fat']) }}g
                                </p>
                            </div>

                            @php
                                $status = $meal['delivery_status']
                                    ?? $meal['order_status']
                                    ?? 'scheduled';

                                $statusClasses = match($status) {
                                    'delivered' => 'bg-green-50 text-green-700',
                                    'out_for_delivery' => 'bg-blue-50 text-blue-700',
                                    'ready_for_delivery', 'ready_for_pickup' => 'bg-amber-50 text-amber-700',
                                    'preparing' => 'bg-orange-50 text-orange-700',
                                    'cancelled', 'failed' => 'bg-red-50 text-red-700',
                                    default => 'bg-gray-100 text-gray-600',
                                };
                            @endphp

                            <span class="whitespace-nowrap rounded-full px-2.5 py-1 text-[10px] font-bold {{ $statusClasses }}">
                                {{ Str::headline($status) }}
                            </span>
                        </div>

                        @if($meal['is_skipped'])
                        <div class="mt-3 rounded-lg bg-amber-50 px-3 py-2 text-xs text-amber-700">
                            {{ __('Skipped') }}
                            @if($meal['skip_reason'])
                                — {{ $meal['skip_reason'] }}
                            @endif
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-gray-200 bg-white p-10 text-center">
            <h3 class="text-sm font-bold text-gray-900">
                {{ __('No meals scheduled for today') }}
            </h3>
            <p class="mt-1 text-xs text-gray-500">
                {{ __('Choose another day below to view your meal schedule.') }}
            </p>
        </div>
    @endif
</div>

<div x-data="{ selectedDay: {{ $todayIndex }} }" class="mb-6">
    <div class="mb-4 rounded-2xl bg-gradient-to-r from-[#173327] to-[#6E7A25] p-5 text-white shadow-lg">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-extrabold">{{ __('Weekly schedule') }}</h2>
                <p class="text-xs text-white/60">{{ $weekRange }}</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('user.meals', ['week' => 'prev']) }}"
                   class="rounded-lg bg-white/15 px-3 py-2 text-sm font-bold">
                    ←
                </a>

                <div class="text-center">
                    <p class="text-xl font-extrabold">{{ $totalWeekMeals }}</p>
                    <p class="text-[9px] text-white/60">
                        {{ number_format($totalWeekCalories) }} kcal
                    </p>
                </div>

                <a href="{{ route('user.meals', ['week' => 'next']) }}"
                   class="rounded-lg bg-white/15 px-3 py-2 text-sm font-bold">
                    →
                </a>
            </div>
        </div>
    </div>

    <div class="mb-4 grid grid-cols-2 gap-2 sm:grid-cols-4 lg:grid-cols-7">
        @foreach($weekMeals as $index => $day)
        @php
            $isToday = ($day['date'] ?? null) === $todayDate;
        @endphp

        <button type="button"
                @click="selectedDay = {{ $index }}"
                class="rounded-xl border p-3 text-left transition"
                :class="selectedDay === {{ $index }}
                    ? 'border-[#6E7A25] bg-[#173327] text-white shadow-md'
                    : 'border-gray-100 bg-white text-gray-900 hover:border-[#6E7A25]/40'">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wide">
                    {{ $day['day'] }}
                </span>

                @if($isToday)
                <span class="rounded-full bg-[#6E7A25] px-1.5 py-0.5 text-[8px] font-bold text-white">
                    {{ __('Today') }}
                </span>
                @endif
            </div>

            <p class="mt-2 text-xl font-extrabold">
                {{ \Carbon\Carbon::parse($day['date'])->format('d') }}
            </p>
            <p class="mt-1 text-xs opacity-70">
                {{ $day['mealCount'] }} {{ __('meal(s)') }}
            </p>
        </button>
        @endforeach
    </div>

    @foreach($weekMeals as $index => $day)
    <div x-show="selectedDay === {{ $index }}"
         x-cloak
         class="rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
            <div>
                <h3 class="font-extrabold text-gray-900">
                    {{ \Carbon\Carbon::parse($day['date'])->format('l') }}
                </h3>
                <p class="text-xs text-gray-500">
                    {{ \Carbon\Carbon::parse($day['date'])->format('F j, Y') }}
                </p>
            </div>

            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-bold text-gray-600">
                {{ $day['mealCount'] }} {{ __('meal(s)') }}
            </span>
        </div>

        <div class="p-5">
            @if(!empty($day['categories']))
                <div class="space-y-5">
                    @foreach($day['categories'] as $category)
                    <div>
                        <h4 class="mb-2 text-xs font-extrabold uppercase tracking-wide text-[#6E7A25]">
                            {{ $category['name'] }}
                        </h4>

                        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                            @foreach($category['meals'] as $meal)
                            @php
                                $status = $meal['delivery_status']
                                    ?? $meal['order_status']
                                    ?? 'scheduled';
                            @endphp

                            <div class="rounded-xl border border-gray-100 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-bold text-gray-900">
                                            {{ $meal['name'] }}
                                        </p>
                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ number_format($meal['calories']) }} kcal
                                        </p>
                                    </div>

                                    <span class="rounded-full bg-gray-100 px-2 py-1 text-[9px] font-bold text-gray-600">
                                        {{ Str::headline($status) }}
                                    </span>
                                </div>

                                <div class="mt-3 flex flex-wrap gap-2 text-[10px] font-semibold">
                                    <span class="rounded-md bg-[#6E7A25]/10 px-2 py-1 text-[#6E7A25]">
                                        P {{ number_format($meal['protein']) }}g
                                    </span>
                                    <span class="rounded-md bg-[#025C5F]/10 px-2 py-1 text-[#025C5F]">
                                        C {{ number_format($meal['carbs']) }}g
                                    </span>
                                    <span class="rounded-md bg-[#949B50]/10 px-2 py-1 text-[#949B50]">
                                        F {{ number_format($meal['fat']) }}g
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="py-10 text-center">
                    <p class="text-sm font-bold text-gray-900">
                        {{ __('No meals assigned for this day') }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        {{ __('Your nutrition team has not assigned meals for this date yet.') }}
                    </p>
                </div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection
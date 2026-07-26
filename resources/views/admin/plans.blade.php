@extends('layouts.admin')

@section('title', __('Plans') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Meal Plans'))

@section('content')
@php
    /*
     * New plan architecture:
     *
     * Plan
     *  - package identity and pricing
     *  - duration_days
     *  - meals_per_day
     *  - total_meals (derived when missing)
     *  - active/inactive state
     *
     * Plan Menu
     *  - configured separately through the Menu action
     *
     * Subscription
     *  - links a customer to a plan
     *
     * Orders / Meal Assignments
     *  - generated later from the active subscription schedule
     */
    $plans = is_array($plans ?? null) ? $plans : [];
    $stats = is_array($stats ?? null) ? $stats : [];

    $safeStats = [
        'total' => (int) ($stats['total'] ?? count($plans)),
        'active' => (int) ($stats['active'] ?? collect($plans)->where('is_active', true)->count()),
        'inactive' => (int) ($stats['inactive'] ?? collect($plans)->where('is_active', false)->count()),
        'totalSubscribers' => (int) ($stats['totalSubscribers'] ?? $stats['total_subscribers'] ?? 0),
        'activeSubscribers' => (int) ($stats['activeSubscribers'] ?? $stats['active_subscribers'] ?? 0),
        'avgRevenue' => (float) ($stats['avgRevenue'] ?? $stats['avg_price'] ?? 0),
        'totalMeals' => (int) ($stats['totalMeals'] ?? $stats['total_meals'] ?? 0),
    ];
@endphp

<div x-data="plansPage()" x-cloak>
    {{-- Toast notifications --}}
    <div class="fixed right-5 top-5 z-[80] space-y-2">
        <template x-for="toast in toasts" :key="toast.id">
            <div
                x-show="toast.show"
                x-transition
                class="flex min-w-[280px] items-center gap-2 rounded-xl px-4 py-3 text-sm text-white shadow-lg"
                :class="toast.type === 'success' ? 'bg-emerald-600' : 'bg-red-600'"
            >
                <span x-text="toast.message"></span>
            </div>
        </template>
    </div>

    {{-- Architecture info --}}
    <div class="mb-6 rounded-2xl border border-[#6E7A25]/10 bg-gradient-to-r from-[#173327]/5 to-[#6E7A25]/5 p-5">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-widest text-[#6E7A25]">
                    {{ __('Plan architecture') }}
                </p>
                <h2 class="mt-1 text-lg font-extrabold text-gray-900">
                    {{ __('Package → Menu → Subscription → Meal Schedule') }}
                </h2>
                <p class="mt-1 max-w-3xl text-xs leading-relaxed text-gray-500">
                    {{ __('A plan defines price, duration and meals per day. Configure the actual daily menu separately, then customers subscribe and receive scheduled orders automatically.') }}
                </p>
            </div>

            <button
                type="button"
                @click="openModal()"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#173327] to-[#6E7A25] px-5 py-3 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5 hover:shadow-lg"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('Create Plan') }}
            </button>
        </div>
    </div>

    {{-- KPI cards --}}
    <div class="mb-6 grid grid-cols-2 gap-4 xl:grid-cols-4">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#173327] to-[#6E7A25] p-5 text-white shadow-lg">
            <div class="absolute -right-8 -top-8 h-20 w-20 rounded-full bg-white/10"></div>
            <p class="text-xs font-semibold text-white/60">{{ __('Plans') }}</p>
            <p class="mt-2 text-3xl font-extrabold">{{ $safeStats['total'] }}</p>
            <p class="mt-1 text-xs text-white/60">
                {{ $safeStats['active'] }} {{ __('active') }}
                ·
                {{ $safeStats['inactive'] }} {{ __('inactive') }}
            </p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold text-gray-400">{{ __('Subscribers') }}</p>
            <p class="mt-2 text-3xl font-extrabold text-gray-900">
                {{ number_format($safeStats['totalSubscribers']) }}
            </p>
            <p class="mt-1 text-xs text-emerald-600">
                {{ number_format($safeStats['activeSubscribers']) }} {{ __('active subscriptions') }}
            </p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold text-gray-400">{{ __('Configured meals') }}</p>
            <p class="mt-2 text-3xl font-extrabold text-[#025C5F]">
                {{ number_format($safeStats['totalMeals']) }}
            </p>
            <p class="mt-1 text-xs text-gray-400">
                {{ __('Across plan menus') }}
            </p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold text-gray-400">{{ __('Average plan price') }}</p>
            <p class="mt-2 text-3xl font-extrabold text-[#6E7A25]">
                SAR {{ number_format($safeStats['avgRevenue'], 2) }}
            </p>
            <p class="mt-1 text-xs text-gray-400">
                {{ __('Per subscription package') }}
            </p>
        </div>
    </div>

    {{-- Search and filters --}}
    <div class="mb-6 flex flex-col gap-3 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm lg:flex-row lg:items-center">
        <div class="flex flex-1 items-center rounded-xl bg-gray-50 px-4 py-3">
            <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14Z"/>
            </svg>
            <input
                type="text"
                x-model="search"
                placeholder="{{ __('Search by plan name...') }}"
                class="w-full bg-transparent text-sm text-gray-700 outline-none placeholder:text-gray-400"
            >
        </div>

        <select
            x-model="statusFilter"
            class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 text-sm text-gray-700 outline-none"
        >
            <option value="all">{{ __('All statuses') }}</option>
            <option value="active">{{ __('Active') }}</option>
            <option value="inactive">{{ __('Inactive') }}</option>
        </select>

        <select
            x-model="durationFilter"
            class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 text-sm text-gray-700 outline-none"
        >
            <option value="all">{{ __('All durations') }}</option>
            <option value="short">{{ __('Up to 14 days') }}</option>
            <option value="monthly">{{ __('15–31 days') }}</option>
            <option value="long">{{ __('More than 31 days') }}</option>
        </select>
    </div>

    @if(session('status'))
    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('status') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        {{ session('error') }}
    </div>
    @endif

    {{-- Plans grid --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
        <template x-for="plan in filteredPlans" :key="plan.id">
            <article class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="relative overflow-hidden border-b border-gray-100 bg-gradient-to-br from-[#173327]/5 to-[#6E7A25]/10 p-5">
                    <div class="absolute -right-10 -top-10 h-24 w-24 rounded-full bg-[#6E7A25]/10"></div>

                    <div class="relative flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-[#6E7A25]">
                                {{ __('Subscription package') }}
                            </p>
                            <h3 class="mt-1 truncate text-lg font-extrabold text-gray-900" x-text="plan.name"></h3>
                            <p
                                class="mt-1 line-clamp-2 min-h-[2.5rem] text-xs leading-relaxed text-gray-500"
                                x-text="plan.description || '{{ __('No description provided.') }}'"
                            ></p>
                        </div>

                        <span
                            class="inline-flex flex-shrink-0 rounded-full px-2.5 py-1 text-[10px] font-bold"
                            :class="plan.is_active
                                ? 'bg-emerald-50 text-emerald-700'
                                : 'bg-gray-100 text-gray-500'"
                            x-text="plan.is_active ? '{{ __('Active') }}' : '{{ __('Inactive') }}'"
                        ></span>
                    </div>
                </div>

                <div class="p-5">
                    <div class="mb-5 flex items-end justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">
                                {{ __('Price') }}
                            </p>
                            <p class="mt-1 text-2xl font-extrabold text-gray-900">
                                <span x-text="plan.currency"></span>
                                <span x-text="formatNumber(plan.price)"></span>
                            </p>
                        </div>

                        <p class="text-right text-xs text-gray-400">
                            <span class="font-bold text-gray-700" x-text="plan.duration_days"></span>
                            {{ __('days') }}
                        </p>
                    </div>

                    <div class="mb-5 grid grid-cols-3 gap-2">
                        <div class="rounded-xl bg-gray-50 p-3 text-center">
                            <p class="text-lg font-extrabold text-gray-900" x-text="plan.meals_per_day"></p>
                            <p class="mt-1 text-[9px] font-bold uppercase tracking-wide text-gray-400">
                                {{ __('Meals/day') }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-gray-50 p-3 text-center">
                            <p class="text-lg font-extrabold text-gray-900" x-text="plan.total_meals"></p>
                            <p class="mt-1 text-[9px] font-bold uppercase tracking-wide text-gray-400">
                                {{ __('Total meals') }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-gray-50 p-3 text-center">
                            <p class="text-lg font-extrabold text-gray-900" x-text="plan.subscribers_count"></p>
                            <p class="mt-1 text-[9px] font-bold uppercase tracking-wide text-gray-400">
                                {{ __('Subscribers') }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-5 rounded-xl border border-[#6E7A25]/10 bg-[#6E7A25]/5 p-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wide text-[#6E7A25]">
                                    {{ __('Plan menu') }}
                                </p>
                                <p class="mt-1 text-xs text-gray-600">
                                    <span class="font-bold" x-text="plan.menu_days_count"></span>
                                    {{ __('configured days') }}
                                    ·
                                    <span class="font-bold" x-text="plan.menu_items_count"></span>
                                    {{ __('meal slots') }}
                                </p>
                            </div>

                            <span
                                class="rounded-full px-2 py-1 text-[9px] font-bold"
                                :class="plan.menu_configured
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : 'bg-amber-100 text-amber-700'"
                                x-text="plan.menu_configured
                                    ? '{{ __('Configured') }}'
                                    : '{{ __('Needs setup') }}'"
                            ></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <a
                            :href="'{{ url('admin/plans') }}/' + plan.id + '/menu'"
                            class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] px-3 py-2.5 text-xs font-bold text-white"
                        >
                            {{ __('Configure Menu') }}
                        </a>

                        <button
                            type="button"
                            @click="viewPlan(plan)"
                            class="rounded-lg bg-gray-100 px-3 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-200"
                        >
                            {{ __('View Details') }}
                        </button>

                        <button
                            type="button"
                            @click="editPlan(plan)"
                            class="rounded-lg bg-[#025C5F]/10 px-3 py-2.5 text-xs font-bold text-[#025C5F]"
                        >
                            {{ __('Edit Package') }}
                        </button>

                        <button
                            type="button"
                            @click="confirmDelete(plan)"
                            class="rounded-lg bg-red-50 px-3 py-2.5 text-xs font-bold text-red-600"
                        >
                            {{ __('Delete') }}
                        </button>
                    </div>
                </div>
            </article>
        </template>
    </div>

    <div x-show="filteredPlans.length === 0" class="py-16 text-center">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100">
            <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.5L19 9.5V19a2 2 0 0 1-2 2Z"/>
            </svg>
        </div>
        <p class="mt-4 text-sm font-bold text-gray-700">{{ __('No plans found') }}</p>
    </div>

    {{-- Create/Edit modal --}}
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[70] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="closeModal()"></div>

        <div class="relative max-h-[92vh] w-full max-w-2xl overflow-y-auto rounded-3xl bg-white shadow-2xl">
            <div class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-100 bg-white p-6">
                <div>
                    <h3
                        class="text-xl font-extrabold text-gray-900"
                        x-text="editing
                            ? '{{ __('Edit Plan Package') }}'
                            : '{{ __('Create Plan Package') }}'"
                    ></h3>
                    <p class="mt-1 text-xs text-gray-500">
                        {{ __('The daily menu will be configured separately after saving the package.') }}
                    </p>
                </div>

                <button type="button" @click="closeModal()" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100">
                    ✕
                </button>
            </div>

            <form class="space-y-5 p-6" @submit.prevent="submitPlan">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-gray-700">
                            {{ __('Plan name (English)') }} *
                        </label>
                        <input
                            type="text"
                            x-model="form.name_en"
                            required
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-[#6E7A25]"
                        >
                        <p x-show="errors.name_en" x-text="firstError('name_en')" class="mt-1 text-xs text-red-600"></p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-gray-700">
                            {{ __('Plan name (Arabic)') }}
                        </label>
                        <input
                            type="text"
                            x-model="form.name_ar"
                            dir="rtl"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-[#6E7A25]"
                        >
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-gray-700">
                            {{ __('Description (English)') }}
                        </label>
                        <textarea
                            x-model="form.description_en"
                            rows="3"
                            class="w-full resize-none rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-[#6E7A25]"
                        ></textarea>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-gray-700">
                            {{ __('Description (Arabic)') }}
                        </label>
                        <textarea
                            x-model="form.description_ar"
                            rows="3"
                            dir="rtl"
                            class="w-full resize-none rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-[#6E7A25]"
                        ></textarea>
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-3">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-gray-700">
                            {{ __('Price (SAR)') }} *
                        </label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            x-model="form.price"
                            required
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-[#6E7A25]"
                        >
                        <p x-show="errors.price" x-text="firstError('price')" class="mt-1 text-xs text-red-600"></p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-gray-700">
                            {{ __('Duration (days)') }} *
                        </label>
                        <input
                            type="number"
                            min="1"
                            x-model.number="form.duration_days"
                            required
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-[#6E7A25]"
                        >
                        <p x-show="errors.duration_days" x-text="firstError('duration_days')" class="mt-1 text-xs text-red-600"></p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-gray-700">
                            {{ __('Meals per day') }} *
                        </label>
                        <input
                            type="number"
                            min="1"
                            x-model.number="form.meals_per_day"
                            required
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-[#6E7A25]"
                        >
                        <p x-show="errors.meals_per_day" x-text="firstError('meals_per_day')" class="mt-1 text-xs text-red-600"></p>
                    </div>
                </div>

                <div class="rounded-xl border border-[#6E7A25]/10 bg-[#6E7A25]/5 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-700">{{ __('Calculated total meals') }}</p>
                            <p class="mt-1 text-[10px] text-gray-500">
                                {{ __('Duration × meals per day') }}
                            </p>
                        </div>
                        <p class="text-2xl font-extrabold text-[#173327]" x-text="calculatedTotalMeals"></p>
                    </div>
                </div>

                <label class="flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 p-4">
                    <input
                        type="checkbox"
                        x-model="form.is_active"
                        class="h-4 w-4 rounded border-gray-300 text-[#6E7A25]"
                    >
                    <span>
                        <span class="block text-sm font-bold text-gray-700">{{ __('Active plan') }}</span>
                        <span class="block text-xs text-gray-400">
                            {{ __('Only active plans are available for new subscriptions.') }}
                        </span>
                    </span>
                </label>

                <div x-show="errors.general" x-text="firstError('general')" class="rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700"></div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <button type="button" @click="closeModal()" class="rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-bold text-gray-600">
                        {{ __('Cancel') }}
                    </button>

                    <button
                        type="submit"
                        :disabled="submitting"
                        class="rounded-xl bg-gradient-to-r from-[#173327] to-[#6E7A25] px-6 py-2.5 text-sm font-bold text-white disabled:opacity-60"
                    >
                        <span
                            x-text="submitting
                                ? '{{ __('Saving...') }}'
                                : (editing
                                    ? '{{ __('Update Plan') }}'
                                    : '{{ __('Create Plan') }}')"
                        ></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Details panel --}}
    <div x-show="selected" x-cloak class="fixed inset-0 z-[70] flex justify-end">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="selected = null"></div>

        <aside class="relative h-full w-full max-w-md overflow-y-auto bg-white shadow-2xl">
            <div class="sticky top-0 bg-gradient-to-r from-[#173327] to-[#6E7A25] p-6 text-white">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs text-white/60">{{ __('Plan details') }}</p>
                        <h3 class="mt-1 text-xl font-extrabold" x-text="selected?.name"></h3>
                    </div>
                    <button type="button" @click="selected = null" class="rounded-lg bg-white/10 px-3 py-2">✕</button>
                </div>
            </div>

            <div class="space-y-5 p-6">
                <div class="rounded-xl bg-gray-50 p-4">
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between gap-3">
                            <dt class="text-gray-500">{{ __('Price') }}</dt>
                            <dd class="font-bold text-gray-900">
                                <span x-text="selected?.currency"></span>
                                <span x-text="formatNumber(selected?.price)"></span>
                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-gray-500">{{ __('Duration') }}</dt>
                            <dd class="font-bold text-gray-900">
                                <span x-text="selected?.duration_days"></span> {{ __('days') }}
                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-gray-500">{{ __('Meals per day') }}</dt>
                            <dd class="font-bold text-gray-900" x-text="selected?.meals_per_day"></dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-gray-500">{{ __('Total meals') }}</dt>
                            <dd class="font-bold text-gray-900" x-text="selected?.total_meals"></dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-gray-500">{{ __('Subscribers') }}</dt>
                            <dd class="font-bold text-gray-900" x-text="selected?.subscribers_count"></dd>
                        </div>
                    </dl>
                </div>

                <div class="rounded-xl border border-[#6E7A25]/10 bg-[#6E7A25]/5 p-4">
                    <h4 class="text-xs font-extrabold uppercase tracking-wide text-[#6E7A25]">
                        {{ __('Menu configuration') }}
                    </h4>
                    <p class="mt-2 text-sm text-gray-700">
                        <span class="font-bold" x-text="selected?.menu_days_count"></span>
                        {{ __('days configured') }}
                        ·
                        <span class="font-bold" x-text="selected?.menu_items_count"></span>
                        {{ __('meal slots') }}
                    </p>

                    <a
                        :href="'{{ url('admin/plans') }}/' + selected?.id + '/menu'"
                        class="mt-4 inline-flex rounded-lg bg-[#173327] px-4 py-2 text-xs font-bold text-white"
                    >
                        {{ __('Open Plan Menu') }}
                    </a>
                </div>

                <div x-show="selected?.description">
                    <h4 class="text-xs font-extrabold uppercase tracking-wide text-gray-400">
                        {{ __('Description') }}
                    </h4>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600" x-text="selected?.description"></p>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <button type="button" @click="editPlan(selected)" class="rounded-xl bg-[#025C5F] px-4 py-3 text-xs font-bold text-white">
                        {{ __('Edit Package') }}
                    </button>
                    <button type="button" @click="confirmDelete(selected)" class="rounded-xl bg-red-50 px-4 py-3 text-xs font-bold text-red-600">
                        {{ __('Delete') }}
                    </button>
                </div>
            </div>
        </aside>
    </div>

    {{-- Delete confirmation --}}
    <div x-show="deleteModal.open" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/60" @click="deleteModal.open = false"></div>

        <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-2xl">
            <h3 class="text-lg font-extrabold text-gray-900">{{ __('Delete plan?') }}</h3>
            <p class="mt-2 text-sm text-gray-500">
                {{ __('This is only allowed when the backend confirms that no protected subscription depends on this plan.') }}
            </p>

            <div class="mt-6 flex gap-3">
                <button type="button" @click="deleteModal.open = false" class="flex-1 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-bold text-gray-600">
                    {{ __('Cancel') }}
                </button>
                <button type="button" @click="deletePlan()" :disabled="deleteModal.loading" class="flex-1 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white disabled:opacity-60">
                    {{ __('Delete') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function plansPage() {
    const sourcePlans = @json($plans);

    return {
        modalOpen: false,
        submitting: false,
        editing: false,
        editId: null,
        selected: null,
        search: '',
        statusFilter: 'all',
        durationFilter: 'all',
        errors: {},
        toasts: [],

        deleteModal: {
            open: false,
            plan: null,
            loading: false,
        },

        form: {
            name_en: '',
            name_ar: '',
            description_en: '',
            description_ar: '',
            price: '',
            duration_days: 30,
            meals_per_day: 3,
            total_meals: 90,
            is_active: true,
        },

        plans: (Array.isArray(sourcePlans) ? sourcePlans : []).map((plan) => {
            const durationDays = Number(plan.duration_days ?? plan.duration ?? 0);
            const mealsPerDay = Number(plan.meals_per_day ?? 0);
            const calculatedMeals = durationDays * mealsPerDay;

            const menuDaysCount = Number(
                plan.menu_days_count
                ?? plan.configured_days
                ?? plan.menu?.days_count
                ?? 0
            );

            const menuItemsCount = Number(
                plan.menu_items_count
                ?? plan.meal_slots_count
                ?? plan.menu?.items_count
                ?? 0
            );

            return {
                ...plan,
                id: plan.id,
                name: plan.name_en ?? plan.name ?? plan.name_ar ?? 'Unnamed Plan',
                description: plan.description_en ?? plan.description ?? plan.description_ar ?? '',
                price: Number(plan.price ?? 0),
                currency: plan.currency ?? 'SAR',
                duration_days: durationDays,
                meals_per_day: mealsPerDay,
                total_meals: Number(plan.total_meals ?? calculatedMeals),
                is_active: Boolean(plan.is_active ?? plan.status === 'active'),
                subscribers_count: Number(
                    plan.subscribers_count
                    ?? plan.total_subscribers
                    ?? plan.subscriptions_count
                    ?? 0
                ),
                menu_days_count: menuDaysCount,
                menu_items_count: menuItemsCount,
                menu_configured: menuDaysCount > 0 || menuItemsCount > 0,
            };
        }),

        get calculatedTotalMeals() {
            return Math.max(
                0,
                Number(this.form.duration_days || 0)
                * Number(this.form.meals_per_day || 0)
            );
        },

        get filteredPlans() {
            const term = this.search.trim().toLowerCase();

            return this.plans.filter((plan) => {
                const searchMatches = !term
                    || String(plan.name || '').toLowerCase().includes(term)
                    || String(plan.description || '').toLowerCase().includes(term);

                const statusMatches = this.statusFilter === 'all'
                    || (this.statusFilter === 'active' && plan.is_active)
                    || (this.statusFilter === 'inactive' && !plan.is_active);

                const days = Number(plan.duration_days || 0);
                const durationMatches = this.durationFilter === 'all'
                    || (this.durationFilter === 'short' && days <= 14)
                    || (this.durationFilter === 'monthly' && days >= 15 && days <= 31)
                    || (this.durationFilter === 'long' && days > 31);

                return searchMatches && statusMatches && durationMatches;
            });
        },

        formatNumber(value) {
            return Number(value || 0).toLocaleString(undefined, {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2,
            });
        },

        firstError(field) {
            const value = this.errors?.[field];

            if (Array.isArray(value)) {
                return value[0] ?? '';
            }

            return value ?? '';
        },

        toast(message, type = 'success') {
            const id = Date.now() + Math.random();

            this.toasts.push({
                id,
                message,
                type,
                show: true,
            });

            setTimeout(() => {
                const toast = this.toasts.find((item) => item.id === id);

                if (toast) {
                    toast.show = false;
                }

                setTimeout(() => {
                    this.toasts = this.toasts.filter((item) => item.id !== id);
                }, 300);
            }, 3000);
        },

        resetForm() {
            this.form = {
                name_en: '',
                name_ar: '',
                description_en: '',
                description_ar: '',
                price: '',
                duration_days: 30,
                meals_per_day: 3,
                total_meals: 90,
                is_active: true,
            };
        },

        openModal() {
            this.errors = {};
            this.editing = false;
            this.editId = null;
            this.resetForm();
            this.modalOpen = true;
        },

        closeModal() {
            this.modalOpen = false;
            this.errors = {};
        },

        editPlan(plan) {
            if (!plan) return;

            this.selected = null;
            this.errors = {};
            this.editing = true;
            this.editId = plan.id;

            this.form = {
                name_en: plan.name_en ?? plan.name ?? '',
                name_ar: plan.name_ar ?? '',
                description_en: plan.description_en ?? plan.description ?? '',
                description_ar: plan.description_ar ?? '',
                price: plan.price ?? '',
                duration_days: Number(plan.duration_days ?? 30),
                meals_per_day: Number(plan.meals_per_day ?? 3),
                total_meals: Number(plan.total_meals ?? 0),
                is_active: Boolean(plan.is_active),
            };

            this.modalOpen = true;
        },

        viewPlan(plan) {
            this.selected = plan;
        },

        confirmDelete(plan) {
            this.selected = null;
            this.deleteModal.plan = plan;
            this.deleteModal.open = true;
        },

        async submitPlan() {
            this.submitting = true;
            this.errors = {};

            this.form.total_meals = this.calculatedTotalMeals;

            const url = this.editing
                ? @json(route('admin.plans.update', '__ID__')).replace('__ID__', this.editId)
                : @json(route('admin.plans.store'));

            try {
                const response = await fetch(url, {
                    method: this.editing ? 'PUT' : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector(
                            'meta[name="csrf-token"]'
                        )?.content || '',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(this.form),
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    this.errors = data.errors ?? {
                        general: data.message ?? '{{ __('Unable to save the plan.') }}',
                    };
                    return;
                }

                this.modalOpen = false;
                window.location.href = data.redirect ?? @json(route('admin.plans'));
            } catch (error) {
                this.errors = {
                    general: error.message ?? '{{ __('Something went wrong.') }}',
                };
            } finally {
                this.submitting = false;
            }
        },

        async deletePlan() {
            const plan = this.deleteModal.plan;

            if (!plan) return;

            this.deleteModal.loading = true;

            const url = @json(
                route('admin.plans.destroy', '__ID__')
            ).replace('__ID__', plan.id);

            try {
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector(
                            'meta[name="csrf-token"]'
                        )?.content || '',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    this.toast(
                        data.message ?? '{{ __('Unable to delete the plan.') }}',
                        'error'
                    );
                    return;
                }

                this.plans = this.plans.filter(
                    (item) => item.id !== plan.id
                );

                this.toast('{{ __('Plan deleted successfully.') }}');
                this.deleteModal.open = false;
                this.deleteModal.plan = null;
            } catch (error) {
                this.toast(
                    error.message ?? '{{ __('Something went wrong.') }}',
                    'error'
                );
            } finally {
                this.deleteModal.loading = false;
            }
        },
    };
}
</script>
@endpush
@endsection

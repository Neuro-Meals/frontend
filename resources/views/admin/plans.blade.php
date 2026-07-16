@extends('layouts.admin')

@section('title', __('Plans') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Meal Plans'))

@section('content')
<div x-data="plansPage()" x-cloak>

    {{-- Toast Notifications --}}
    <div class="fixed top-5 right-5 z-[70] space-y-2">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-5" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-5" :class="toast.type === 'success' ? 'bg-emerald-600' : 'bg-red-600'" class="text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-2 text-sm min-w-[260px]">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span x-text="toast.message"></span>
            </div>
        </template>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-xs text-gray-400 mb-1">{{ __('Total Plans') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-xs text-gray-400 mb-1">{{ __('Active Plans') }}</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-xs text-gray-400 mb-1">{{ __('Total Subscribers') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['totalSubscribers']) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-xs text-gray-400 mb-1">{{ __('Avg Revenue / Plan') }}</p>
            <p class="text-2xl font-bold text-gray-900">SAR {{ $stats['avgRevenue'] }}</p>
        </div>
    </div>

    {{-- Action Bar --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center bg-white rounded-xl px-4 py-3 border border-gray-100 shadow-sm flex-1 max-w-md w-full">
            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" x-model="search" placeholder="{{ __('Search plans...') }}" class="bg-transparent text-sm outline-none flex-1 text-gray-700 placeholder-gray-400">
        </div>
        <button @click="openModal" class="w-full sm:w-auto px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] hover:from-[#025C5F] hover:to-[#1E8A00] rounded-xl shadow-md shadow-brand-light/20 hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('Create Plan') }}
        </button>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('status') }}
        </div>
    @endif

    {{-- Plans Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-for="plan in filteredPlans" :key="plan.id">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                {{-- Header --}}
                <div class="p-5 border-b border-gray-50" :style="`background: linear-gradient(135deg, ${plan.color}15, ${plan.color}05);`">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center" :style="`background: ${plan.color}20;`">
                            <svg class="w-6 h-6" :style="`color: ${plan.color};`" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold border" :class="plan.status === 'active' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-500 border-gray-200'">
                            <span x-text="statusLabel(plan.status)"></span>
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900" x-text="plan.name"></h3>
                    <p class="text-xs text-gray-400 mt-1" x-text="plan.calories + ' {{ __('kcal/day') }}'"></p>
                </div>
                {{-- Body --}}
                <div class="p-5">
                    <div class="flex items-end gap-1 mb-4">
                        <span class="text-2xl font-bold text-gray-900" x-text="'SAR ' + Number(plan.price).toLocaleString()"></span>
                        <span class="text-xs text-gray-400 mb-1" x-text="'/ ' + plan.duration"></span>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider">{{ __('Meals') }}</p>
                            <p class="text-sm font-bold text-gray-900" x-text="plan.meals"></p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider">{{ __('Subscribers') }}</p>
                            <p class="text-sm font-bold text-gray-900" x-text="plan.subscribers"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a :href="'{{ url('admin/plans') }}/' + plan.id + '/menu'" class="flex-1 px-3 py-2 text-xs font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-lg transition-all hover:shadow-md flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ __('Menu') }}
                        </a>
                        <button @click="editPlan(plan)" class="px-3 py-2 text-xs font-bold text-white rounded-lg transition-all hover:opacity-90" :style="`background: ${plan.color};`">
                            {{ __('Edit') }}
                        </button>
                        <button @click="viewPlan(plan)" class="px-3 py-2 text-xs font-medium text-gray-600 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            {{ __('View') }}
                        </button>
                        <button @click="confirmDelete(plan)" class="px-3 py-2 text-xs font-medium text-red-500 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <div x-show="filteredPlans.length === 0" class="text-center py-16">
        <div class="w-20 h-20 mx-auto rounded-full bg-gray-50 flex items-center justify-center mb-4">
            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <p class="text-gray-500 font-medium">{{ __('No plans found') }}</p>
    </div>

    {{-- Create Plan Modal --}}
    <div x-show="modalOpen" class="fixed inset-0 z-[60] flex items-center justify-center p-4" x-cloak>
        <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="modalOpen = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4" class="relative bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between sticky top-0 bg-white dark:bg-gray-900 z-10">
                <div>
                    <h3 class="text-xl font-extrabold text-gray-900 dark:text-white" x-text="editing ? '{{ __('Edit Plan') }}' : '{{ __('Create New Plan') }}'"></h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5" x-text="editing ? '{{ __('Update the plan details below.') }}' : '{{ __('Fill in the details below to add a new meal plan.') }}'"></p>
                </div>
                <button @click="modalOpen = false" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form class="p-6 space-y-5" @submit.prevent="submitPlan">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Plan Name (English)') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="name_en" x-model="form.name_en" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all" :class="errors.name_en ? 'border-red-300 ring-2 ring-red-100' : ''" placeholder="e.g. Weight Loss Pro">
                        <p x-show="errors.name_en" x-text="errors.name_en" class="mt-1 text-xs text-red-600"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Plan Name (Arabic)') }}</label>
                        <input type="text" name="name_ar" x-model="form.name_ar" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all" :class="errors.name_ar ? 'border-red-300 ring-2 ring-red-100' : ''" placeholder="مثال: خطة التخسيس المحترفة">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Plan Type') }} <span class="text-red-500">*</span></label>
                        <select name="plan_type" x-model="form.plan_type" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                            <option value="weekly">{{ __('Weekly') }}</option>
                            <option value="monthly">{{ __('Monthly') }}</option>
                            <option value="custom">{{ __('Custom') }}</option>
                            <option value="corporate">{{ __('Corporate') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Goal') }}</label>
                        <select name="goal" x-model="form.goal" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                            <option value="">{{ __('Select goal') }}</option>
                            <option value="weight_loss">{{ __('Weight Loss') }}</option>
                            <option value="muscle_gain">{{ __('Muscle Gain') }}</option>
                            <option value="maintenance">{{ __('Maintenance') }}</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Description (English)') }}</label>
                    <textarea name="description_en" x-model="form.description_en" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all resize-none" placeholder="Brief description of the plan..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Price (SAR)') }} <span class="text-red-500">*</span></label>
                        <input type="number" name="price" x-model="form.price" step="0.01" min="0" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all" :class="errors.price ? 'border-red-300 ring-2 ring-red-100' : ''" placeholder="0.00">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Duration (days)') }} <span class="text-red-500">*</span></label>
                        <input type="number" name="duration_days" x-model="form.duration_days" min="1" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all" :class="errors.duration_days ? 'border-red-300 ring-2 ring-red-100' : ''" placeholder="28">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Meals / Day') }} <span class="text-red-500">*</span></label>
                        <input type="number" name="meals_per_day" x-model="form.meals_per_day" min="1" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all" :class="errors.meals_per_day ? 'border-red-300 ring-2 ring-red-100' : ''" placeholder="3">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Total Meals') }} <span class="text-red-500">*</span></label>
                        <input type="number" name="total_meals" x-model="form.total_meals" min="1" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all" :class="errors.total_meals ? 'border-red-300 ring-2 ring-red-100' : ''" placeholder="84">
                    </div>
                    <div class="flex items-center gap-3 pt-7">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" x-model="form.is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            <span class="ml-3 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Active Plan') }}</span>
                        </label>
                    </div>
                </div>

                <div x-show="errors.general" x-text="errors.general" class="p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm" x-cloak></div>

                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100 dark:border-gray-800">
                    <button type="button" @click="modalOpen = false" class="px-6 py-2.5 text-sm font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-colors">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" :disabled="submitting" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] hover:from-[#025C5F] hover:to-[#1E8A00] rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                        <svg x-show="!submitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <svg x-show="submitting" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                        <span x-text="submitting ? (editing ? '{{ __('Updating...') }}' : '{{ __('Creating...') }}') : (editing ? '{{ __('Update Plan') }}' : '{{ __('Create Plan') }}')"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- View Plan Detail Slide-Out Panel --}}
    <div x-show="selected" class="fixed inset-0 z-[60] flex justify-end" style="display: none" x-cloak>
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="selected = null"></div>
        <div class="relative w-full max-w-md bg-white dark:bg-gray-900 shadow-2xl h-full overflow-y-auto" @click.outside="selected = null">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] p-6 text-white sticky top-0 z-10">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h3 class="text-base font-bold">{{ __('Plan Details') }}</h3>
                        <p class="text-xs text-white/70" x-text="selected?.name"></p>
                    </div>
                    <button @click="selected = null" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="flex items-center gap-2 mt-3">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold border" :class="selected?.status === 'active' ? 'bg-white/20 text-white border-white/30' : 'bg-white/10 text-white/70 border-white/20'">
                        <span x-text="statusLabel(selected?.status)"></span>
                    </span>
                    <span class="text-xs text-white/70" x-text="selected?.calories + ' {{ __('kcal/day') }}'"></span>
                </div>
            </div>

            <div class="p-6 space-y-6">
                {{-- Plan Overview --}}
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Overview') }}</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-xs text-gray-500">{{ __('Plan Type') }}</span>
                            <span class="text-xs font-semibold text-gray-900 dark:text-white capitalize" x-text="selected?.plan_type || '—'"></span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-xs text-gray-500">{{ __('Goal') }}</span>
                            <span class="text-xs font-semibold text-gray-900 dark:text-white capitalize" x-text="selected?.goal ? selected.goal.replace('_', ' ') : '—'"></span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-xs text-gray-500">{{ __('Duration') }}</span>
                            <span class="text-xs font-semibold text-gray-900 dark:text-white" x-text="selected?.duration || '—'"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">{{ __('Subscribers') }}</span>
                            <span class="text-xs font-semibold text-gray-900 dark:text-white" x-text="selected?.subscribers || 0"></span>
                        </div>
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Pricing') }}</h4>
                    <div class="flex items-end gap-1">
                        <span class="text-3xl font-bold text-[#6E7A25]" x-text="'SAR ' + Number(selected?.price || 0).toLocaleString()"></span>
                        <span class="text-xs text-gray-400 mb-1" x-text="'/ ' + selected?.duration"></span>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-3">
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-3">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider">{{ __('Meals') }}</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="selected?.meals || 0"></p>
                        </div>
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-3">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider">{{ __('Meals/Day') }}</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="selected?.meals_per_day || 0"></p>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div x-show="selected?.description_en">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Description') }}</h4>
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4">
                        <p class="text-xs text-gray-700 dark:text-gray-200 leading-relaxed" x-text="selected?.description_en"></p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-2 pt-2">
                    <button @click="selected && editPlan(selected)" class="flex-1 px-4 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-xl hover:shadow-lg transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        {{ __('Edit Plan') }}
                    </button>
                    <button @click="selected && confirmDelete(selected)" class="px-4 py-2.5 text-xs font-bold text-red-600 bg-red-50 rounded-xl hover:bg-red-100 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        {{ __('Delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="deleteModal.open" class="fixed inset-0 z-[70] flex items-center justify-center p-4" x-cloak>
        <div x-show="deleteModal.open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="deleteModal.open = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        <div x-show="deleteModal.open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
            <div class="w-14 h-14 mx-auto rounded-full bg-red-50 flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('Delete Plan?') }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ __('Are you sure you want to delete') }} <span class="font-semibold text-gray-900 dark:text-white" x-text="deleteModal.plan?.name"></span>? {{ __('This action cannot be undone.') }}</p>
            <div class="flex gap-3">
                <button @click="deleteModal.open = false" class="flex-1 px-4 py-2.5 text-sm font-bold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    {{ __('Cancel') }}
                </button>
                <button @click="deletePlan" :disabled="deleteModal.loading" class="flex-1 px-4 py-2.5 text-sm font-bold text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                    <svg x-show="deleteModal.loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                    <span x-text="deleteModal.loading ? '{{ __('Deleting...') }}' : '{{ __('Delete') }}'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function plansPage() {
        return {
            modalOpen: false,
            submitting: false,
            search: '',
            plans: @json($plans),
            errors: {},
            editing: false,
            editId: null,
            selected: null,
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
                plan_type: 'monthly',
                goal: '',
                price: '',
                duration_days: '28',
                meals_per_day: '3',
                total_meals: '84',
                is_active: true,
            },
            get filteredPlans() {
                if (!this.search) return this.plans;
                const term = this.search.toLowerCase();
                return this.plans.filter(p => p.name.toLowerCase().includes(term));
            },
            statusLabel(s) {
                const m = { active: '{{ __('Active') }}', draft: '{{ __('Draft') }}' };
                return m[s] || s;
            },
            toast(message, type = 'success') {
                const id = Date.now() + Math.random();
                this.toasts.push({ id, message, type, show: true });
                setTimeout(() => {
                    const t = this.toasts.find(x => x.id === id);
                    if (t) t.show = false;
                    setTimeout(() => this.toasts = this.toasts.filter(x => x.id !== id), 300);
                }, 3000);
            },
            openModal() {
                this.errors = {};
                this.editing = false;
                this.editId = null;
                this.form = {
                    name_en: '',
                    name_ar: '',
                    description_en: '',
                    description_ar: '',
                    plan_type: 'monthly',
                    goal: '',
                    price: '',
                    duration_days: '28',
                    meals_per_day: '3',
                    total_meals: '84',
                    is_active: true,
                };
                this.modalOpen = true;
            },
            editPlan(plan) {
                this.selected = null;
                this.errors = {};
                this.editing = true;
                this.editId = plan.id;
                this.form = {
                    name_en: plan.name_en || plan.name || '',
                    name_ar: plan.name_ar || '',
                    description_en: plan.description_en || '',
                    description_ar: plan.description_ar || '',
                    plan_type: plan.plan_type || 'monthly',
                    goal: plan.goal || '',
                    price: plan.price || '',
                    duration_days: plan.duration_days || 28,
                    meals_per_day: plan.meals_per_day || 3,
                    total_meals: plan.total_meals || 84,
                    is_active: plan.is_active !== false,
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

                const url = this.editing
                    ? @json(route('admin.plans.update', '__ID__')).replace('__ID__', this.editId)
                    : @json(route('admin.plans.store'));

                try {
                    const response = await fetch(url, {
                        method: this.editing ? 'PUT' : 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(this.form)
                    });

                    const data = await response.json();
                    this.submitting = false;

                    if (data.success) {
                        this.modalOpen = false;
                        window.location.href = data.redirect || @json(route('admin.plans'));
                        return;
                    }

                    this.errors = data.errors || { general: data.message || (this.editing ? '{{ __('Failed to update plan.') }}' : '{{ __('Failed to create plan.') }}') };
                } catch (error) {
                    this.submitting = false;
                    this.errors = { general: error.message || '{{ __('Something went wrong. Please try again.') }}' };
                }
            },
            async deletePlan() {
                if (!this.deleteModal.plan) return;
                this.deleteModal.loading = true;

                const url = @json(route('admin.plans.destroy', '__ID__')).replace('__ID__', this.deleteModal.plan.id);

                try {
                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });

                    const data = await response.json();
                    this.deleteModal.loading = false;
                    this.deleteModal.open = false;

                    if (data.success) {
                        this.plans = this.plans.filter(p => p.id !== this.deleteModal.plan.id);
                        this.toast('{{ __('Plan deleted successfully.') }}');
                    } else {
                        this.toast(data.message || '{{ __('Failed to delete plan.') }}', 'error');
                    }
                    this.deleteModal.plan = null;
                } catch (error) {
                    this.deleteModal.loading = false;
                    this.deleteModal.open = false;
                    this.toast(error.message || '{{ __('Something went wrong. Please try again.') }}', 'error');
                    this.deleteModal.plan = null;
                }
            }
        };
    }
</script>
@endpush
@endsection

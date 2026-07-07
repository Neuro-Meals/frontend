@extends('layouts.admin')

@section('title', __('Plans') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Meal Plans'))

@section('content')
<div x-data="plansPage()" x-cloak>

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
                            <svg class="w-6 h-6" :style="`color: ${plan.color};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold border" :class="plan.status === 'active' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-500 border-gray-200'">
                            <span x-text="plan.status.charAt(0).toUpperCase() + plan.status.slice(1)"></span>
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
                        <button class="flex-1 px-3 py-2 text-xs font-bold text-white rounded-lg transition-all hover:opacity-90" :style="`background: ${plan.color};`">
                            {{ __('Edit Plan') }}
                        </button>
                        <button class="px-3 py-2 text-xs font-medium text-gray-500 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            {{ __('View') }}
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
                    <h3 class="text-xl font-extrabold text-gray-900 dark:text-white">{{ __('Create New Plan') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ __('Fill in the details below to add a new meal plan.') }}</p>
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
                        <span x-text="submitting ? '{{ __('Creating...') }}' : '{{ __('Create Plan') }}'"></span>
                    </button>
                </div>
            </form>
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
            openModal() {
                this.errors = {};
                this.modalOpen = true;
            },
            async submitPlan() {
                this.submitting = true;
                this.errors = {};

                try {
                    const response = await fetch(@json(route('admin.plans.store')), {
                        method: 'POST',
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

                    this.errors = data.errors || { general: data.message || '{{ __('Failed to create plan.') }}' };
                } catch (error) {
                    this.submitting = false;
                    this.errors = { general: error.message || '{{ __('Something went wrong. Please try again.') }}' };
                }
            }
        };
    }
</script>
@endpush
@endsection

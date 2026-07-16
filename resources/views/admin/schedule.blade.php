@extends('layouts.admin')

@section('title', __('Kitchen Schedule') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Kitchen Schedule'))

@section('content')
<div x-data="scheduleBoard()" x-init="init()" class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ __('Kitchen Schedule') }}</h2>
            <p class="text-sm text-gray-400 mt-1">{{ __('Each schedule is a time period (Morning, Lunch, Evening, Snacks). Transfer only the items needed for that schedule to the kitchen — the order stays active until every schedule is complete.') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <input type="date" x-model="date" @change="changeDate()"
                class="px-3 py-2 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
            <button @click="refresh()" :disabled="loading" class="p-2 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors disabled:opacity-50">
                <svg class="w-4 h-4" :class="loading ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>
        </div>
    </div>

    {{-- Schedule (category) tabs --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center border-b border-gray-100 overflow-x-auto">
            <template x-for="cat in categories" :key="cat.category_id">
                <button @click="selectCategory(cat.category_id)"
                    :class="selectedCategoryId === cat.category_id ? 'border-b-2 border-[#6E7A25] text-[#6E7A25] bg-[#6E7A25]/5' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                    class="px-6 py-4 text-sm font-bold whitespace-nowrap transition-all flex items-center gap-2">
                    <span x-text="cat.category_name"></span>
                    <span x-show="cat.pending > 0" class="text-[10px] font-bold rounded-full px-1.5 py-0.5 bg-orange-100 text-orange-600" x-text="cat.pending + ' {{ __('pending') }}'"></span>
                    <span x-show="cat.pending === 0 && cat.total_items > 0" class="text-[10px] font-bold rounded-full px-1.5 py-0.5 bg-green-100 text-green-700">✓</span>
                </button>
            </template>
            <div x-show="!categories.length" class="px-6 py-4 text-sm text-gray-400">{{ __('No schedules found for this date.') }}</div>
        </div>
    </div>

    <template x-if="selectedCategory">
        <div class="space-y-6">

            {{-- Stat cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">{{ __('Total Required') }}</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" x-text="production.total_required ?? 0"></p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">{{ __('Pending Transfer') }}</p>
                    <p class="text-2xl font-bold text-orange-500 mt-1" x-text="selectedCategory.pending ?? 0"></p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">{{ __('In Kitchen') }}</p>
                    <p class="text-2xl font-bold text-[#6E7A25] mt-1" x-text="(kitchenQueue.totals?.sent_to_kitchen ?? 0) + (kitchenQueue.totals?.preparing ?? 0)"></p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">{{ __('Ready / Served') }}</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1" x-text="(selectedCategory.ready ?? 0) + (selectedCategory.served ?? 0)"></p>
                </div>
            </div>

            {{-- Transfer action --}}
            <div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-2xl shadow-sm p-5 flex flex-col sm:flex-row items-center justify-between gap-4 text-white">
                <div>
                    <p class="font-bold" x-text="'{{ __('Transfer') }} ' + selectedCategory.category_name + ' {{ __('to Kitchen') }}'"></p>
                    <p class="text-sm text-white/70 mt-0.5">{{ __('Only the items belonging to this schedule are sent — not the whole order.') }}</p>
                </div>
                <button @click="transfer()" :disabled="transferring || !selectedCategory.pending"
                    class="px-5 py-2.5 rounded-lg bg-white text-[#173327] text-sm font-bold shadow-sm hover:shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 whitespace-nowrap">
                    <svg x-show="!transferring" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    <svg x-show="transferring" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                    <span x-text="!selectedCategory.pending ? '{{ __('All Transferred') }}' : '{{ __('Transfer to Kitchen') }}'"></span>
                </button>
            </div>

            {{-- Production requirements: per-meal, with ingredients + the customers/orders behind each dish --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">{{ __('Production Requirements') }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ __('Automatically aggregated quantities needed for this schedule. Tap a dish to see ingredients and who ordered it.') }}</p>
                </div>
                <div class="divide-y divide-gray-50">
                    <template x-for="meal in production.meals" :key="meal.meal_id ?? meal.meal_name">
                        <div>
                            <button @click="toggleMeal(meal)" type="button" class="w-full px-5 py-3.5 flex items-center justify-between gap-3 text-left hover:bg-gray-50/70 transition-colors">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-bold text-gray-800 truncate" x-text="meal.meal_name"></p>
                                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                                        <span x-show="meal.pending" class="text-[10px] font-bold rounded-full px-2 py-0.5 bg-gray-100 text-gray-500" x-text="meal.pending + ' {{ __('pending') }}'"></span>
                                        <span x-show="meal.sent_to_kitchen" class="text-[10px] font-bold rounded-full px-2 py-0.5 bg-amber-100 text-amber-700" x-text="meal.sent_to_kitchen + ' {{ __('sent') }}'"></span>
                                        <span x-show="meal.preparing" class="text-[10px] font-bold rounded-full px-2 py-0.5 bg-blue-100 text-blue-700" x-text="meal.preparing + ' {{ __('preparing') }}'"></span>
                                        <span x-show="meal.ready" class="text-[10px] font-bold rounded-full px-2 py-0.5 bg-green-100 text-green-700" x-text="meal.ready + ' {{ __('ready') }}'"></span>
                                        <span x-show="meal.served" class="text-[10px] font-bold rounded-full px-2 py-0.5 bg-emerald-100 text-emerald-700" x-text="meal.served + ' {{ __('served') }}'"></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 flex-shrink-0">
                                    <p class="text-lg font-bold text-gray-900" x-text="'×' + meal.total_required"></p>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="isMealOpen(meal) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </button>

                            <div x-show="isMealOpen(meal)"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="px-5 pb-4 bg-gray-50/60" style="display: none;">
                                <div x-show="meal.ingredients?.length" class="flex flex-wrap items-center gap-1.5 mb-3">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mr-1">{{ __('Ingredients') }}:</span>
                                    <template x-for="ing in meal.ingredients" :key="ing">
                                        <span class="px-2 py-1 rounded-full bg-white border border-gray-200 text-[11px] text-gray-600" x-text="ing"></span>
                                    </template>
                                </div>
                                <div x-show="meal.allergens?.length" class="flex flex-wrap items-center gap-1.5 mb-3">
                                    <span class="text-[10px] font-bold text-red-400 uppercase tracking-wide mr-1">{{ __('Allergens') }}:</span>
                                    <template x-for="a in meal.allergens" :key="a">
                                        <span class="px-2 py-1 rounded-full bg-red-50 border border-red-100 text-[11px] text-red-600" x-text="a"></span>
                                    </template>
                                </div>

                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-2">
                                    {{ __('Customers') }} (<span x-text="meal.customers?.length ?? 0"></span>)
                                </p>
                                <div class="space-y-1.5 max-h-72 overflow-y-auto pr-1">
                                    <template x-for="c in meal.customers" :key="c.order_id">
                                        <div class="flex items-center justify-between gap-2 bg-white rounded-lg px-3 py-2 border border-gray-100">
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-gray-800 truncate" x-text="c.customer_name"></p>
                                                <p class="text-[10px] text-gray-400 truncate" x-text="(c.order_number || ('#' + c.order_id)) + (c.address ? (' · ' + c.address) : '')"></p>
                                            </div>
                                            <div class="flex items-center gap-2 flex-shrink-0">
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full capitalize" :class="itemStatusClass(c.item_status)" x-text="c.item_status?.replaceAll('_',' ')"></span>
                                                <span class="text-xs font-bold text-gray-700" x-text="'×' + c.quantity"></span>
                                            </div>
                                        </div>
                                    </template>
                                    <div x-show="!meal.customers?.length" class="text-xs text-gray-400 text-center py-2">{{ __('No customers found.') }}</div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div x-show="!production.meals?.length" class="px-5 py-8 text-center text-sm text-gray-400">{{ __('No items scheduled for this category.') }}</div>
                </div>
            </div>

            {{-- Kitchen queue: what's physically in the kitchen right now, with full detail --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900" x-text="'{{ __('Today\'s') }} ' + selectedCategory.category_name + ' {{ __('Preparation') }}'"></h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ __('Sent to kitchen, preparing, or ready — tap a dish to see ingredients and who ordered it.') }}</p>
                </div>
                <div class="divide-y divide-gray-50">
                    <template x-for="meal in kitchenQueue.meals" :key="meal.meal_id ?? meal.meal_name">
                        <div>
                            <button @click="toggleKitchenMeal(meal)" type="button" class="w-full px-5 py-3.5 flex items-center gap-3 text-left hover:bg-gray-50/70 transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden flex items-center justify-center">
                                    <img x-show="meal.image_url" :src="meal.image_url" class="w-full h-full object-cover" alt="">
                                    <svg x-show="!meal.image_url" class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-bold text-gray-800 truncate" x-text="meal.meal_name"></p>
                                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                                        <span class="text-[10px] text-gray-400" x-text="(meal.customer_count ?? meal.customers?.length ?? 0) + ' {{ __('customers') }}'"></span>
                                        <span x-show="meal.sent_to_kitchen" class="text-[10px] font-bold rounded-full px-2 py-0.5 bg-amber-100 text-amber-700" x-text="meal.sent_to_kitchen + ' {{ __('sent') }}'"></span>
                                        <span x-show="meal.preparing" class="text-[10px] font-bold rounded-full px-2 py-0.5 bg-blue-100 text-blue-700" x-text="meal.preparing + ' {{ __('preparing') }}'"></span>
                                        <span x-show="meal.ready" class="text-[10px] font-bold rounded-full px-2 py-0.5 bg-green-100 text-green-700" x-text="meal.ready + ' {{ __('ready') }}'"></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 flex-shrink-0">
                                    <p class="text-lg font-bold text-[#6E7A25]" x-text="'×' + ((meal.sent_to_kitchen||0) + (meal.preparing||0) + (meal.ready||0))"></p>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="isKitchenMealOpen(meal) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </button>

                            <div x-show="isKitchenMealOpen(meal)"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="px-5 pb-4 bg-gray-50/60" style="display: none;">
                                <div x-show="meal.ingredients?.length" class="flex flex-wrap items-center gap-1.5 mb-3">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mr-1">{{ __('Ingredients') }}:</span>
                                    <template x-for="ing in meal.ingredients" :key="ing">
                                        <span class="px-2 py-1 rounded-full bg-white border border-gray-200 text-[11px] text-gray-600" x-text="ing"></span>
                                    </template>
                                </div>
                                <div x-show="meal.allergens?.length" class="flex flex-wrap items-center gap-1.5 mb-3">
                                    <span class="text-[10px] font-bold text-red-400 uppercase tracking-wide mr-1">{{ __('Allergens') }}:</span>
                                    <template x-for="a in meal.allergens" :key="a">
                                        <span class="px-2 py-1 rounded-full bg-red-50 border border-red-100 text-[11px] text-red-600" x-text="a"></span>
                                    </template>
                                </div>

                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-2">
                                    {{ __('Customers') }} (<span x-text="meal.customers?.length ?? 0"></span>)
                                </p>
                                <div class="space-y-1.5 max-h-72 overflow-y-auto pr-1">
                                    <template x-for="c in meal.customers" :key="c.order_id">
                                        <div class="flex items-center justify-between gap-2 bg-white rounded-lg px-3 py-2 border border-gray-100">
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-gray-800 truncate" x-text="c.customer_name"></p>
                                                <p class="text-[10px] text-gray-400 truncate" x-text="(c.order_number || ('#' + c.order_id)) + (c.address ? (' · ' + c.address) : '')"></p>
                                            </div>
                                            <div class="flex items-center gap-2 flex-shrink-0">
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full capitalize" :class="itemStatusClass(c.item_status)" x-text="c.item_status?.replaceAll('_',' ')"></span>
                                                <span class="text-xs font-bold text-gray-700" x-text="'×' + c.quantity"></span>
                                            </div>
                                        </div>
                                    </template>
                                    <div x-show="!meal.customers?.length" class="text-xs text-gray-400 text-center py-2">{{ __('No customers found.') }}</div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div x-show="!kitchenQueue.meals?.length" class="px-5 py-8 text-center text-sm text-gray-400">{{ __('Nothing transferred to the kitchen yet.') }}</div>
                </div>
            </div>

        </div>
    </template>

</div>

<script>
function scheduleBoard() {
    return {
        date: @json($date),
        categories: @json($categories),
        selectedCategoryId: @json($selectedCategoryId),
        production: @json($production),
        kitchenQueue: @json($kitchenQueue),
        loading: false,
        transferring: false,
        openMeals: [],
        openKitchenMeals: [],
        strings: {
            confirmTitle: @json(__('Transfer to Kitchen?')),
            confirmText: @json(__('This sends only the items in this schedule to the kitchen. The order will stay active until every schedule is completed.')),
            confirmButton: @json(__('Yes, transfer')),
            cancelButton: @json(__('Cancel')),
            successTitle: @json(__('Transferred!')),
            errorTitle: @json(__('Error')),
        },

        init() {
            // nothing extra — data is server-rendered for first paint
        },

        get selectedCategory() {
            return this.categories.find(c => c.category_id === this.selectedCategoryId) || null;
        },

        mealKey(meal) {
            return meal.meal_id ?? meal.meal_name;
        },

        toggleMeal(meal) {
            const key = this.mealKey(meal);
            const idx = this.openMeals.indexOf(key);
            if (idx === -1) this.openMeals.push(key);
            else this.openMeals.splice(idx, 1);
        },

        isMealOpen(meal) {
            return this.openMeals.includes(this.mealKey(meal));
        },

        toggleKitchenMeal(meal) {
            const key = this.mealKey(meal);
            const idx = this.openKitchenMeals.indexOf(key);
            if (idx === -1) this.openKitchenMeals.push(key);
            else this.openKitchenMeals.splice(idx, 1);
        },

        isKitchenMealOpen(meal) {
            return this.openKitchenMeals.includes(this.mealKey(meal));
        },

        itemStatusClass(status) {
            return {
                'bg-gray-100 text-gray-500': status === 'pending',
                'bg-amber-100 text-amber-700': status === 'sent_to_kitchen',
                'bg-blue-100 text-blue-700': status === 'preparing',
                'bg-green-100 text-green-700': status === 'ready',
                'bg-emerald-100 text-emerald-700': status === 'served',
            };
        },

        async selectCategory(id) {
            this.selectedCategoryId = id;
            await this.refresh();
        },

        async changeDate() {
            await this.refresh();
        },

        async refresh() {
            this.loading = true;
            try {
                const url = new URL('{{ route('admin.schedule.data') }}', window.location.origin);
                url.searchParams.set('date', this.date);
                if (this.selectedCategoryId) {
                    url.searchParams.set('category_id', this.selectedCategoryId);
                }
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                if (data.success) {
                    this.categories = data.categories;
                    this.production = data.production;
                    this.kitchenQueue = data.kitchen_queue;
                    if (!this.selectedCategoryId && this.categories.length) {
                        const withPending = this.categories.find(c => c.pending > 0);
                        this.selectedCategoryId = (withPending || this.categories[0]).category_id;
                        await this.refresh();
                        return;
                    }
                }
            } finally {
                this.loading = false;
            }
        },

        async transfer() {
            const confirmed = await Swal.fire({
                title: this.strings.confirmTitle,
                text: this.strings.confirmText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6E7A25',
                confirmButtonText: this.strings.confirmButton,
                cancelButtonText: this.strings.cancelButton,
            });
            if (!confirmed.isConfirmed) return;

            this.transferring = true;
            try {
                const res = await fetch('{{ route('admin.schedule.transfer') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ date: this.date, category_id: this.selectedCategoryId }),
                });
                const data = await res.json();
                if (data.success) {
                    Swal.fire({ title: this.strings.successTitle, icon: 'success', timer: 1400, showConfirmButton: false });
                    await this.refresh();
                } else {
                    Swal.fire({ title: this.strings.errorTitle, text: data.message, icon: 'error' });
                }
            } catch (e) {
                Swal.fire({ title: this.strings.errorTitle, text: String(e), icon: 'error' });
            } finally {
                this.transferring = false;
            }
        },
    };
}
</script>
@endsection

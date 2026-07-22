@extends('layouts.admin')

@section('title', __('Plan Menu Builder') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Weekly Menu Builder'))

@section('content')
<div x-data="menuBuilder()" class="space-y-6">

{{-- Flash Messages --}}
@if(session('success'))
<div class="bg-green-50 border border-green-100 text-green-700 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="bg-red-50 border border-red-100 text-red-700 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div>
@endif

{{-- Header --}}
<div class="flex items-center justify-between">
    <div>
        <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
            <a href="{{ route('admin.plans') }}" class="hover:text-[#6E7A25] transition-colors">{{ __('Plans') }}</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600">{{ $plan['name_en'] ?? ($plan['name'] ?? 'Plan') }}</span>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-[#6E7A25] font-bold">{{ __('Menu Builder') }}</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">{{ $plan['name_en'] ?? ($plan['name'] ?? 'Plan') }} — {{ __('Weekly Menu') }}</h2>
        <p class="text-sm text-gray-400 mt-1">{{ __('Assign meals to each day and category. This menu drives automatic order generation.') }}</p>
    </div>
    <a href="{{ route('admin.plans') }}" class="px-4 py-2 text-sm font-bold text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        {{ __('Back to Plans') }}
    </a>
</div>

{{-- Day Tabs --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="flex items-center border-b border-gray-100 overflow-x-auto">
        <template x-for="(day, index) in days" :key="day.day_of_week">
            <button @click="activeDay = index"
                :class="activeDay === index ? 'border-b-2 border-[#6E7A25] text-[#6E7A25] bg-[#6E7A25]/5' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                class="px-6 py-4 text-sm font-bold whitespace-nowrap transition-all flex items-center gap-2">
                <span x-text="dayLabels[day.day_of_week]"></span>
                <span class="text-[10px] font-bold rounded-full px-1.5 py-0.5"
                    :class="activeDay === index ? 'bg-[#6E7A25] text-white' : 'bg-gray-100 text-gray-500'"
                    x-text="getDayItemCount(day)"></span>
            </button>
        </template>
    </div>

    {{-- Day Content --}}
    <div class="p-6">
        <template x-for="(day, dayIndex) in days" :key="day.day_of_week">
            <div x-show="activeDay === dayIndex" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

                {{-- Add Meal Form --}}
                <div class="mb-6 bg-gray-50 rounded-xl border border-gray-100 p-4">
                    <h4 class="text-xs font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        {{ __('Add Meal to') }} <span x-text="dayLabels[day.day_of_week]"></span>
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 mb-1">{{ __('Category') }}</label>
                            <select x-model="addForm.category_id" @change="filterMealsByCategory()"
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                                <option value="">{{ __('Select category...') }}</option>
                                <template x-for="cat in categories" :key="cat.id">
                                    <option :value="cat.id" x-text="cat.name"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 mb-1">{{ __('Meal') }}</label>
                            <select x-model="addForm.meal_id"
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                                <option value="">{{ __('Select meal...') }}</option>
                                <template x-for="meal in filteredMeals" :key="meal.id">
                                    <option :value="meal.id" x-text="meal.name + ' (' + meal.calories + ' cal)' + (meal.is_available ? '' : ' — Unavailable')"></option>
                                </template>
                            </select>
                            <p x-show="filteredMeals.length === 0" class="text-[10px] text-amber-600 mt-1">{{ __('No meals in this category. Add meals first.') }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 mb-1">{{ __('Quantity') }}</label>
                            <input type="number" x-model.number="addForm.quantity" min="1" max="100"
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                        </div>
                        <div class="flex items-end">
                            <button @click="addMenuItem(day.day_of_week)"
                                :disabled="!addForm.meal_id || !addForm.category_id || saving"
                                class="w-full px-4 py-2 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-xs font-bold shadow-sm hover:shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-1.5">
                                <svg x-show="!saving" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <svg x-show="saving" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                                <span x-text="saving ? '{{ __('Adding...') }}' : '{{ __('Add Meal') }}'"></span>
                            </button>
                        </div>
                    </div>
                    <div x-show="addError" x-text="addError" class="mt-2 text-xs text-red-600 bg-red-50 rounded-lg px-3 py-2"></div>
                    <div x-show="addSuccess" x-text="addSuccess" class="mt-2 text-xs text-green-700 bg-green-50 rounded-lg px-3 py-2"></div>
                </div>

                {{-- Category Groups --}}
                <div class="space-y-4">
                    <template x-for="catGroup in day.categories" :key="catGroup.category_name">
                        <div class="border border-gray-100 rounded-xl overflow-hidden">
                            <div class="px-4 py-2.5 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-[#6E7A25]"></div>
                                <span class="text-sm font-bold text-gray-800" x-text="catGroup.category_name"></span>
                                <span class="text-[10px] text-gray-400" x-text="catGroup.items.length + ' {{ __('items') }}'"></span>
                            </div>
                            <div class="divide-y divide-gray-50">
                                <template x-for="item in catGroup.items" :key="item.id">
                                    <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-50/50 transition-colors">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex-shrink-0 overflow-hidden">
                                                <img x-show="item.meal && item.meal.image_url" :src="item.meal.image_url" :alt="item.meal.name_en" class="w-full h-full object-cover">
                                                <div x-show="!item.meal || !item.meal.image_url" class="w-full h-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-gray-900 truncate" x-text="item.meal ? item.meal.name_en : 'Unknown meal'"></p>
                                                <div class="flex items-center gap-3 text-[10px] text-gray-400">
                                                    <span x-show="item.meal && item.meal.calories" x-text="item.meal.calories + ' cal'"></span>
                                                    <span x-text="'x' + item.quantity"></span>
                                                    <span x-show="!item.is_active" class="text-amber-600 font-bold">{{ __('Inactive') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            {{-- Toggle Active --}}
                                            <button @click="toggleActive(item)"
                                                class="p-1.5 rounded-lg transition-colors"
                                                :class="item.is_active ? 'text-green-600 hover:bg-green-50' : 'text-gray-400 hover:bg-gray-100'"
                                                :title="item.is_active ? '{{ __('Active — click to deactivate') }}' : '{{ __('Inactive — click to activate') }}'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
                                            {{-- Delete --}}
                                            <button @click="removeMenuItem(item)"
                                                class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="{{ __('Remove') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Empty State --}}
                    <div x-show="day.categories.length === 0" class="text-center py-12">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <p class="text-sm font-bold text-gray-400">{{ __('No meals assigned yet') }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ __('Use the form above to add meals for this day.') }}</p>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

{{-- Summary Bar --}}
<div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-2xl p-5 text-white shadow-lg flex items-center justify-between">
    <div class="flex items-center gap-6">
        <div>
            <p class="text-xs text-white/60">{{ __('Total Menu Items') }}</p>
            <p class="text-2xl font-bold" x-text="totalItems"></p>
        </div>
        <div class="w-px h-12 bg-white/20"></div>
        <div>
            <p class="text-xs text-white/60">{{ __('Days Configured') }}</p>
            <p class="text-2xl font-bold" x-text="configuredDays + '/7'"></p>
        </div>
        <div class="w-px h-12 bg-white/20"></div>
        <div>
            <p class="text-xs text-white/60">{{ __('Categories Used') }}</p>
            <p class="text-2xl font-bold" x-text="usedCategories"></p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <div x-show="configuredDays < 7" class="flex items-center gap-2 text-amber-200 text-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ __('Some days have no meals configured') }}
        </div>
        <div x-show="configuredDays === 7" class="flex items-center gap-2 text-green-200 text-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ __('All days configured') }}
        </div>
    </div>
</div>

</div>

@push('scripts')
<script>
function menuBuilder() {
    return {
        activeDay: 0,
        days: @json($normalizedDays),
        meals: @json($meals),
        categories: @json($categories),
        planId: {{ $plan['id'] ?? 0 }},
        saving: false,
        addError: '',
        addSuccess: '',
        addForm: {
            category_id: '',
            meal_id: '',
            quantity: 1,
        },
        dayLabels: {
            monday: '{{ __('Monday') }}',
            tuesday: '{{ __('Tuesday') }}',
            wednesday: '{{ __('Wednesday') }}',
            thursday: '{{ __('Thursday') }}',
            friday: '{{ __('Friday') }}',
            saturday: '{{ __('Saturday') }}',
            sunday: '{{ __('Sunday') }}',
        },

        get filteredMeals() {
            const catId = parseInt(this.addForm.category_id);
            if (!catId) return this.meals;
            return this.meals.filter(m => parseInt(m.category_id) === catId);
        },

        get totalItems() {
            let count = 0;
            this.days.forEach(day => {
                day.categories.forEach(cat => {
                    count += cat.items.length;
                });
            });
            return count;
        },

        get configuredDays() {
            return this.days.filter(day => day.categories.length > 0).length;
        },

        get usedCategories() {
            const cats = new Set();
            this.days.forEach(day => {
                day.categories.forEach(cat => {
                    if (cat.items.length > 0) cats.add(cat.category_name);
                });
            });
            return cats.size;
        },

        getDayItemCount(day) {
            let count = 0;
            day.categories.forEach(cat => { count += cat.items.length; });
            return count;
        },

        filterMealsByCategory() {
            this.addForm.meal_id = '';
        },

        async addMenuItem(dayOfWeek) {
            if (!this.addForm.meal_id || !this.addForm.category_id) return;
            this.saving = true;
            this.addError = '';
            this.addSuccess = '';

            try {
                const res = await fetch('{{ url('admin/plans') }}/' + this.planId + '/menu', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        plan_id: this.planId,
                        meal_id: parseInt(this.addForm.meal_id),
                        category_id: parseInt(this.addForm.category_id),
                        day_of_week: dayOfWeek,
                        quantity: this.addForm.quantity || 1,
                    }),
                });
                const text = await res.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    this.addError = '{{ __('Server returned an unexpected response.') }}';
                    return;
                }
                if (data.success) {
                    this.addSuccess = data.message || '{{ __('Meal added successfully.') }}';
                    const newItem = data.menu_item || data.item || data.data || {};
                    const meal = this.meals.find(m => m.id === parseInt(this.addForm.meal_id));
                    const cat = this.categories.find(c => c.id === parseInt(this.addForm.category_id));
                    const item = {
                        id: newItem.id || Date.now(),
                        is_active: newItem.is_active ?? true,
                        quantity: this.addForm.quantity || 1,
                        meal: meal ? {
                            name_en: meal.name,
                            calories: meal.calories,
                            image_url: meal.image_url,
                        } : { name_en: 'Meal', calories: 0, image_url: null },
                    };
                    const dayIdx = this.days.findIndex(d => d.day_of_week === dayOfWeek);
                    if (dayIdx !== -1) {
                        const catName = cat ? cat.name : 'Category';
                        let catGroup = this.days[dayIdx].categories.find(c => c.category_name === catName);
                        if (!catGroup) {
                            catGroup = { category_name: catName, items: [] };
                            this.days[dayIdx].categories.push(catGroup);
                        }
                        catGroup.items.push(item);
                    }
                    this.addForm.meal_id = '';
                    this.addForm.quantity = 1;
                    setTimeout(() => { this.addSuccess = ''; }, 3000);
                } else {
                    this.addError = data.message || '{{ __('Failed to add meal.') }}';
                }
            } catch (err) {
                this.addError = '{{ __('Network error. Please try again.') }}';
            } finally {
                this.saving = false;
            }
        },

        async toggleActive(item) {
            try {
                const res = await fetch('{{ url('admin/plans/menu') }}/' + item.id, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ is_active: !item.is_active }),
                });
                const data = await res.json();
                if (data.success) {
                    item.is_active = !item.is_active;
                } else {
                    alert(data.message || '{{ __('Failed to toggle item.') }}');
                }
            } catch (err) {
                console.error('Failed to toggle:', err);
                alert('{{ __('Network error. Please try again.') }}');
            }
        },

        async removeMenuItem(item) {
            if (!confirm('{{ __('Remove this meal from the menu?') }}')) return;
            try {
                const res = await fetch('{{ url('admin/plans/menu') }}/' + item.id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });
                const data = await res.json();
                if (data.success) {
                    this.days.forEach(day => {
                        day.categories.forEach(cat => {
                            cat.items = cat.items.filter(i => i.id !== item.id);
                        });
                        day.categories = day.categories.filter(cat => cat.items.length > 0);
                    });
                } else {
                    alert(data.message || '{{ __('Failed to remove item.') }}');
                }
            } catch (err) {
                console.error('Failed to remove:', err);
                alert('{{ __('Network error. Please try again.') }}');
            }
        },
    };
}
</script>
@endpush
@endsection

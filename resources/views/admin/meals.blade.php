@extends('layouts.admin')

@section('title', __('Meals & Nutrition') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Meals & Nutrition'))

@section('content')
@php
    $statusColors = [
        'active' => 'bg-green-50 text-green-700 border-green-200',
        'draft' => 'bg-gray-50 text-gray-500 border-gray-200',
    ];
    $catColors = [
        'High Protein' => 'bg-green-50 text-green-700',
        'Vegan' => 'bg-purple-50 text-purple-700',
        'Keto' => 'bg-blue-50 text-blue-700',
        'Breakfast' => 'bg-amber-50 text-amber-700',
        'Maintenance' => 'bg-teal-50 text-teal-700',
    ];
@endphp

<div x-data="mealManager()" x-init="init()">

{{-- Flash Messages --}}
@if(session('status'))
<div class="mb-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    {{ session('status') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 bg-red-50 border border-red-100 text-red-700 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('error') }}
</div>
@endif

{{-- KPI Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="kpi-card bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-[#6E7A25]/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <p class="text-xs text-white/60 font-medium mb-1">{{ __('Total Meals') }}</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['total'] }}</p>
            <p class="text-xs text-white/50 mt-1">{{ $stats['active'] }} {{ __('active') }} · {{ $stats['draft'] }} {{ __('draft') }}</p>
        </div>
    </div>
    <div class="kpi-card bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-blue-500/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <p class="text-xs text-white/60 font-medium mb-1">{{ __('Categories') }}</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['categories'] }}</p>
        </div>
    </div>
    <div class="kpi-card bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-amber-500/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <p class="text-xs text-white/60 font-medium mb-1">{{ __('Total Orders') }}</p>
            <p class="text-2xl font-bold tracking-tight">{{ number_format($stats['totalOrders']) }}</p>
        </div>
    </div>
    <div class="kpi-card bg-gradient-to-br from-violet-500 to-purple-700 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-violet-500/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <p class="text-xs text-white/60 font-medium mb-1">{{ __('Avg Rating') }}</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['avgRating'] }}<span class="text-lg">/5</span></p>
        </div>
    </div>
</div>

{{-- Category Distribution --}}
<div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm mb-6">
    <h3 class="text-base font-bold text-gray-900 mb-4">{{ __('Category') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Distribution') }}</span></h3>
    <div class="flex flex-wrap gap-3">
        @foreach($categories as $cat)
        <div class="flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-100 hover:shadow-sm transition-all">
            <div class="w-3 h-3 rounded-full" style="background: {{ $cat['color'] }};"></div>
            <span class="text-xs font-semibold text-gray-700">{{ $cat['name'] }}</span>
            <span class="text-xs font-bold text-gray-900">{{ $cat['count'] }}</span>
        </div>
        @endforeach
    </div>
</div>

{{-- Action Bar --}}
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center bg-white rounded-lg px-3 py-2 border border-gray-100 shadow-sm flex-1 max-w-xs">
        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" x-model="search" placeholder="{{ __('Search meals...') }}" class="bg-transparent text-sm outline-none flex-1 text-gray-600 placeholder-gray-400">
    </div>
    <button @click="openCreate()" class="px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-lg shadow-sm hover:shadow-md transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        {{ __('Add Meal') }}
    </button>
</div>

{{-- Meals Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <template x-for="meal in filteredMeals" :key="meal.id">
        <div class="kpi-card bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            {{-- Image --}}
            <div class="h-40 overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center relative">
                <template x-if="meal.image">
                    <img :src="meal.image" :alt="meal.name" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                </template>
                <template x-if="!meal.image">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </template>
            </div>
            {{-- Body --}}
            <div class="p-5">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900" x-text="meal.name"></h3>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] font-semibold" :class="categoryColor(meal.category)" x-text="meal.category"></span>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-semibold border" :class="meal.status === 'active' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-500 border-gray-200'" x-text="meal.status.charAt(0).toUpperCase() + meal.status.slice(1)"></span>
                </div>
                {{-- Price --}}
                <div class="mt-2 flex items-center justify-between">
                    <span class="text-[10px] text-gray-400">{{ __('Price') }}</span>
                    <span class="text-sm font-bold text-[#6E7A25]" x-text="'SAR ' + parseFloat(meal.price).toFixed(2)"></span>
                </div>
                {{-- Macros --}}
                <div class="grid grid-cols-4 gap-2 mt-3">
                    <div class="text-center bg-gray-50 rounded-lg py-2">
                        <p class="text-[10px] text-gray-400">{{ __('Kcal') }}</p>
                        <p class="text-xs font-bold text-gray-900" x-text="meal.calories"></p>
                    </div>
                    <div class="text-center bg-green-50 rounded-lg py-2">
                        <p class="text-[10px] text-green-400">{{ __('Protein') }}</p>
                        <p class="text-xs font-bold text-green-700" x-text="meal.protein + 'g'"></p>
                    </div>
                    <div class="text-center bg-amber-50 rounded-lg py-2">
                        <p class="text-[10px] text-amber-400">{{ __('Carbs') }}</p>
                        <p class="text-xs font-bold text-amber-700" x-text="meal.carbs + 'g'"></p>
                    </div>
                    <div class="text-center bg-blue-50 rounded-lg py-2">
                        <p class="text-[10px] text-blue-400">{{ __('Fat') }}</p>
                        <p class="text-xs font-bold text-blue-700" x-text="meal.fat + 'g'"></p>
                    </div>
                </div>
                {{-- Footer --}}
                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span class="text-xs font-bold text-gray-700" x-text="meal.rating > 0 ? meal.rating : '—'"></span>
                        </div>
                        <span class="text-xs text-gray-400" x-text="meal.orders + ' {{ __('orders') }}'"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="openEdit(meal.id)" class="text-xs font-bold text-[#6E7A25] hover:text-[#173327] transition-colors">{{ __('Edit') }} →</button>
                        <button @click="confirmDelete(meal.id)" class="text-xs font-bold text-red-600 hover:text-red-800 transition-colors">{{ __('Delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

{{-- Meal Sidebar (Create / Edit) --}}
<div x-show="modalOpen" x-cloak class="fixed inset-0 z-50" aria-labelledby="meal-sidebar-title" role="dialog" aria-modal="true">
    <div x-show="modalOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="closeModal()"></div>

    <div x-show="modalOpen"
         x-transition:enter="transform transition ease-in-out duration-300"
         x-transition:enter-start="translate-x-full rtl:-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full rtl:-translate-x-full"
         class="absolute inset-y-0 right-0 rtl:right-auto rtl:left-0 w-full sm:w-[32rem] lg:w-[36rem] bg-white shadow-2xl"
         style="max-width: 100vw;">
        <div class="h-full flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 flex-shrink-0">
                <h3 id="meal-sidebar-title" class="text-lg font-bold text-gray-900" x-text="editingId ? '{{ __('Edit Meal') }}' : '{{ __('Add Meal') }}'"></h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6">
                <form id="mealForm" :action="formAction" method="POST" class="space-y-5" @submit.prevent="submitForm">
                    @csrf
                    <template x-if="editingId">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Name (EN)') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name_en" x-model="form.name_en" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Name (AR)') }}</label>
                    <input type="text" name="name_ar" x-model="form.name_ar" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none" dir="rtl">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Category') }} <span class="text-red-500">*</span></label>
                    <select name="category_id" x-model="form.category_id" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none bg-white">
                        <option value="">{{ __('Select category') }}</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Status') }}</label>
                    <select name="is_available" x-model="form.is_available" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none bg-white">
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Draft') }}</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Description (EN)') }}</label>
                    <textarea name="description_en" x-model="form.description_en" rows="3" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Description (AR)') }}</label>
                    <textarea name="description_ar" x-model="form.description_ar" rows="3" dir="rtl" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none resize-none"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Calories') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="calories" x-model="form.calories" step="0.1" min="0" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Protein (g)') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="protein_g" x-model="form.protein_g" step="0.1" min="0" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Carbs (g)') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="carbs_g" x-model="form.carbs_g" step="0.1" min="0" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Fat (g)') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="fat_g" x-model="form.fat_g" step="0.1" min="0" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Fiber (g)') }}</label>
                    <input type="number" name="fiber_g" x-model="form.fiber_g" step="0.1" min="0" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Sugar (g)') }}</label>
                    <input type="number" name="sugar_g" x-model="form.sugar_g" step="0.1" min="0" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Sodium (mg)') }}</label>
                    <input type="number" name="sodium_mg" x-model="form.sodium_mg" step="0.1" min="0" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Price (SAR)') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="price" x-model="form.price" step="0.01" min="0" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Meal Image') }}</label>
                <div class="flex items-center gap-4">
                    <div x-show="form.image_url" class="w-16 h-16 rounded-lg overflow-hidden border border-gray-200 flex-shrink-0">
                        <img :src="form.image_url" class="w-full h-full object-cover">
                    </div>
                    <input type="file" @change="uploadImage($event)" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#173327] file:text-white hover:file:bg-[#6E7A25] file:transition-all">
                </div>
                <input type="hidden" name="image_url" x-model="form.image_url">
                <p x-show="uploadError" x-text="uploadError" class="text-xs text-red-600 mt-1"></p>
                <p x-show="uploading" class="text-xs text-[#6E7A25] mt-1">{{ __('Uploading...') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Ingredients') }} <span class="text-[10px] text-gray-400">({{ __('comma separated') }})</span></label>
                    <input type="text" name="ingredients" x-model="form.ingredients" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Allergens') }} <span class="text-[10px] text-gray-400">({{ __('comma separated') }})</span></label>
                    <input type="text" name="allergens" x-model="form.allergens" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Diet Tags') }} <span class="text-[10px] text-gray-400">({{ __('comma separated') }})</span></label>
                    <input type="text" name="diet_tags" x-model="form.diet_tags" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                </div>
            </div>

            <div x-show="formError" class="p-3 rounded-lg bg-red-50 border border-red-100 text-red-700 text-xs" x-text="formError"></div>

            <div class="sticky bottom-0 -mx-6 -mb-6 px-6 py-4 bg-white border-t border-gray-100 flex items-center justify-end gap-3 z-10">
                <button type="button" @click="closeModal()" class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors">{{ __('Cancel') }}</button>
                <button type="submit" :disabled="saving || uploading" class="px-4 py-2 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-sm font-bold shadow-md hover:shadow-lg transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                    <span x-show="!saving" x-text="editingId ? '{{ __('Update Meal') }}' : '{{ __('Create Meal') }}'"></span>
                    <span x-show="saving">{{ __('Saving...') }}</span>
                </button>
            </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation --}}
<div x-show="deleteOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5);">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
        <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('Delete Meal') }}</h3>
        <p class="text-sm text-gray-500 mb-6">{{ __('Are you sure? This action cannot be undone.') }}</p>
        <div class="flex items-center justify-center gap-3">
            <button @click="deleteOpen = false" class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors">{{ __('Cancel') }}</button>
            <form :action="deleteAction" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition-colors">{{ __('Delete') }}</button>
            </form>
        </div>
    </div>
</div>

</div>

@push('scripts')
<script>
    function mealManager() {
        return {
            search: '',
            modalOpen: false,
            deleteOpen: false,
            editingId: null,
            saving: false,
            uploading: false,
            uploadError: '',
            formError: '',
            meals: @json($meals),
            categories: @json($categories),
            form: {
                name_en: '', name_ar: '', description_en: '', description_ar: '',
                category_id: '', calories: '', protein_g: '', carbs_g: '', fat_g: '',
                fiber_g: '', sugar_g: '', sodium_mg: '', price: '',
                image_url: '', ingredients: '', allergens: '', diet_tags: '', is_available: '1'
            },
            get filteredMeals() {
                const term = this.search.toLowerCase();
                return this.meals.filter(m => m.name.toLowerCase().includes(term) || m.category.toLowerCase().includes(term));
            },
            get formAction() {
                return this.editingId ? '{{ url('admin/meals') }}/' + this.editingId : '{{ route('admin.meals.store') }}';
            },
            get deleteAction() {
                return '{{ url('admin/meals') }}/' + this.deleteId;
            },
            init() {
                this.deleteId = null;
            },
            categoryColor(category) {
                const map = {
                    'High Protein': 'bg-green-50 text-green-700',
                    'Vegan': 'bg-purple-50 text-purple-700',
                    'Keto': 'bg-blue-50 text-blue-700',
                    'Breakfast': 'bg-amber-50 text-amber-700',
                    'Maintenance': 'bg-teal-50 text-teal-700'
                };
                return map[category] || 'bg-gray-50 text-gray-600';
            },
            resetForm() {
                this.form = {
                    name_en: '', name_ar: '', description_en: '', description_ar: '',
                    category_id: '', calories: '', protein_g: '', carbs_g: '', fat_g: '',
                    fiber_g: '', sugar_g: '', sodium_mg: '', price: '',
                    image_url: '', ingredients: '', allergens: '', diet_tags: '', is_available: '1'
                };
                this.formError = '';
                this.uploadError = '';
            },
            openCreate() {
                this.editingId = null;
                this.resetForm();
                this.modalOpen = true;
            },
            async openEdit(id) {
                this.editingId = id;
                this.resetForm();
                this.modalOpen = true;
                try {
                    const res = await fetch('{{ url('admin/meals') }}/' + id);
                    const data = await res.json();
                    if (!data.success) {
                        this.formError = data.message || 'Failed to load meal.';
                        return;
                    }
                    const m = data.meal;
                    this.form = {
                        name_en: m.name_en || '', name_ar: m.name_ar || '',
                        description_en: m.description_en || '', description_ar: m.description_ar || '',
                        category_id: m.category_id || '', calories: m.calories || '',
                        protein_g: m.protein_g || '', carbs_g: m.carbs_g || '', fat_g: m.fat_g || '',
                        fiber_g: m.fiber_g || '', sugar_g: m.sugar_g || '', sodium_mg: m.sodium_mg || '',
                        price: m.price || '', image_url: m.image_url || '',
                        ingredients: Array.isArray(m.ingredients) ? m.ingredients.join(', ') : (m.ingredients || ''),
                        allergens: Array.isArray(m.allergens) ? m.allergens.join(', ') : (m.allergens || ''),
                        diet_tags: Array.isArray(m.diet_tags) ? m.diet_tags.join(', ') : (m.diet_tags || ''),
                        is_available: m.is_available ? '1' : '0'
                    };
                } catch (e) {
                    this.formError = 'Network error while loading meal.';
                }
            },
            closeModal() {
                this.modalOpen = false;
                this.editingId = null;
            },
            confirmDelete(id) {
                this.deleteId = id;
                this.deleteOpen = true;
            },
            async uploadImage(e) {
                const file = e.target.files[0];
                if (!file) return;
                this.uploading = true;
                this.uploadError = '';
                const formData = new FormData();
                formData.append('file', file);
                try {
                    const res = await fetch('{{ route('upload.image') }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    });
                    const data = await res.json();
                    if (data.success) {
                        this.form.image_url = data.image_url;
                    } else {
                        this.uploadError = data.message || 'Upload failed.';
                    }
                } catch (err) {
                    this.uploadError = 'Network error during upload.';
                } finally {
                    this.uploading = false;
                }
            },
            async submitForm(e) {
                this.saving = true;
                this.formError = '';
                const form = e.target;
                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form)
                    });
                    if (res.redirected) {
                        window.location.href = res.url;
                        return;
                    }
                    const data = await res.json();
                    if (data.success === false) {
                        this.formError = data.message || 'Validation failed.';
                    } else {
                        window.location.href = '{{ route('admin.meals') }}';
                    }
                } catch (err) {
                    this.formError = 'Network error while saving.';
                } finally {
                    this.saving = false;
                }
            }
        };
    }
</script>
@endpush
@endsection

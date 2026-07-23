@extends('layouts.user')

@section('title', __('Settings') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Settings'))

@section('content')

{{-- Profile Header --}}
<div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-2xl p-5 sm:p-6 text-white shadow-lg mb-6 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12 blur-2xl"></div>
    <div class="relative z-10 flex items-center gap-4">
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/15 flex items-center justify-center flex-shrink-0 backdrop-blur-sm text-2xl sm:text-3xl font-bold">
            {{ strtoupper(substr($profile['first_name'] ?? 'U', 0, 1) . substr($profile['last_name'] ?? '', 0, 1)) }}
        </div>
        <div class="min-w-0">
            <h2 class="text-lg sm:text-xl font-bold truncate">{{ $profile['name'] ?: 'User' }}</h2>
            <div class="flex items-center gap-2 mt-1 flex-wrap">
                <span class="text-xs text-white/60">{{ $profile['email'] }}</span>
                @if($subscriptionInfo)
                <span class="w-1 h-1 bg-white/30 rounded-full"></span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $subscriptionInfo['status'] === 'active' ? 'bg-green-400/20 text-green-300' : 'bg-amber-400/20 text-amber-300' }}">
                    @if($subscriptionInfo['status'] === 'active')
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                    @endif
                    {{ ucfirst($subscriptionInfo['status']) }}
                </span>
                @endif
            </div>
        </div>
    </div>
</div>

<div x-data="profileEditor()" class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    {{-- Profile Info --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 lg:col-span-2">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-[#6E7A25]/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Profile') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Information') }}</span></h3>
            </div>
            <button x-show="!editing" @click="startEdit()" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold rounded-lg bg-[#6E7A25]/10 text-[#6E7A25] hover:bg-[#6E7A25]/20 transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                {{ __('Edit') }}
            </button>
            <div x-show="editing" class="flex items-center gap-2" style="display:none">
                <button @click="cancelEdit()" class="px-3 py-1.5 text-xs font-bold rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all">{{ __('Cancel') }}</button>
                <button @click="saveEdit()" :disabled="saving" class="px-3 py-1.5 text-xs font-bold rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white hover:shadow-md transition-all" x-text="saving ? '{{ __('Saving...') }}' : '{{ __('Save') }}'"></button>
            </div>
        </div>

        {{-- View Mode --}}
        <div x-show="!editing" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('First Name') }}</label>
                <input type="text" :value="form.first_name" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50/50" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Last Name') }}</label>
                <input type="text" :value="form.last_name" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50/50" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Email') }}</label>
                <input type="email" :value="form.email" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50/50" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Phone') }}</label>
                <input type="text" :value="form.phone || 'N/A'" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50/50" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Gender') }}</label>
                <input type="text" :value="form.gender ? form.gender.charAt(0).toUpperCase() + form.gender.slice(1) : 'N/A'" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50/50" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Delivery Zone') }}</label>
                <input type="text" :value="form.location || 'N/A'" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50/50" readonly>
            </div>
        </div>
        <div x-show="!editing" class="mt-4">
            <label class="text-[10px] font-medium text-gray-400">{{ __('Delivery Address') }}</label>
            <textarea class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50/50" rows="2" readonly x-text="form.address || 'N/A'"></textarea>
        </div>

        {{-- Edit Mode --}}
        <div x-show="editing" style="display:none" class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-[10px] font-medium text-gray-400">{{ __('First Name') }}</label>
                    <input type="text" x-model="form.first_name" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-medium text-gray-400">{{ __('Last Name') }}</label>
                    <input type="text" x-model="form.last_name" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all">
                </div>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Email') }}</label>
                <input type="email" :value="form.email" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50/50" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Phone') }}</label>
                <input type="text" x-model="form.phone" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[10px] font-medium text-gray-400">{{ __('Gender') }}</label>
                    <select x-model="form.gender" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all">
                        <option value="">—</option>
                        <option value="male">{{ __('Male') }}</option>
                        <option value="female">{{ __('Female') }}</option>
                        <option value="other">{{ __('Other') }}</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-medium text-gray-400">{{ __('Age') }}</label>
                    <input type="number" x-model="form.age" min="0" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all">
                </div>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Delivery Zone') }}</label>
                <input type="text" x-model="form.location" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all">
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Delivery Address') }}</label>
                <textarea x-model="form.address" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all" rows="2"></textarea>
            </div>
        </div>
    </div>

    {{-- Health Goals --}}
    <div class="bg-gradient-to-br from-[#173327] to-[#122620] rounded-2xl p-5 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-[#6E7A25]/10 rounded-full -mr-12 -mt-12 blur-2xl"></div>
        <div class="absolute bottom-0 left-0 w-20 h-20 bg-[#6E7A25]/5 rounded-full -ml-10 -mb-10 blur-xl"></div>
        <div class="flex items-center gap-2 mb-5 relative z-10">
            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h3 class="text-sm font-bold">{{ __('Health') }} <span class="text-[#6E7A25]">{{ __('Goals') }}</span></h3>
        </div>

        {{-- View Mode --}}
        <div x-show="!editing" class="space-y-4 relative z-10">
            <div class="flex items-center justify-between py-2 border-b border-white/10">
                <span class="text-[10px] text-white/50">{{ __('Height') }}</span>
                <p class="text-lg font-bold" x-text="form.height_cm || 'N/A'"></p>
                <span class="text-xs text-white/40">cm</span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-white/10">
                <span class="text-[10px] text-white/50">{{ __('Current Weight') }}</span>
                <p class="text-lg font-bold" x-text="form.weight_kg || 'N/A'"></p>
                <span class="text-xs text-white/40">kg</span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-white/10">
                <span class="text-[10px] text-white/50">{{ __('Goal') }}</span>
                <p class="text-sm font-bold text-[#6E7A25]" x-text="form.fitness_goal ? form.fitness_goal.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()) : 'N/A'"></p>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-white/10">
                <span class="text-[10px] text-white/50">{{ __('Dietary Preference') }}</span>
                <p class="text-sm font-bold" x-text="form.dietary_preference || 'N/A'"></p>
            </div>
            <div class="py-2">
                <span class="text-[10px] text-white/50 block mb-2">{{ __('Allergies') }}</span>
                <div class="flex flex-wrap gap-1">
                    <template x-if="form.allergies && form.allergies.length">
                        <template x-for="a in form.allergies" :key="a">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-400/20 text-red-300" x-text="a"></span>
                        </template>
                    </template>
                    <template x-if="!form.allergies || !form.allergies.length">
                        <span class="text-sm text-white/40">N/A</span>
                    </template>
                </div>
            </div>
        </div>

        {{-- Edit Mode --}}
        <div x-show="editing" style="display:none" class="space-y-3 relative z-10">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[10px] text-white/50">{{ __('Height (cm)') }}</label>
                    <input type="number" step="0.1" x-model="form.height_cm" class="mt-1 w-full px-3 py-2 text-sm border border-white/10 rounded-lg bg-white/5 text-white outline-none focus:ring-2 focus:ring-[#6E7A25]/30">
                </div>
                <div>
                    <label class="text-[10px] text-white/50">{{ __('Weight (kg)') }}</label>
                    <input type="number" step="0.1" x-model="form.weight_kg" class="mt-1 w-full px-3 py-2 text-sm border border-white/10 rounded-lg bg-white/5 text-white outline-none focus:ring-2 focus:ring-[#6E7A25]/30">
                </div>
            </div>
            <div>
                <label class="text-[10px] text-white/50">{{ __('Fitness Goal') }}</label>
                <select x-model="form.fitness_goal" class="mt-1 w-full px-3 py-2 text-sm border border-white/10 rounded-lg bg-white/5 text-white outline-none focus:ring-2 focus:ring-[#6E7A25]/30">
                    <option value="">—</option>
                    <option value="weight_loss">{{ __('Weight Loss') }}</option>
                    <option value="muscle_gain">{{ __('Muscle Gain') }}</option>
                    <option value="maintenance">{{ __('Maintenance') }}</option>
                    <option value="healthy_lifestyle">{{ __('Healthy Lifestyle') }}</option>
                </select>
            </div>
            <div>
                <label class="text-[10px] text-white/50">{{ __('Dietary Preference') }}</label>
                <input type="text" x-model="form.dietary_preference" class="mt-1 w-full px-3 py-2 text-sm border border-white/10 rounded-lg bg-white/5 text-white outline-none focus:ring-2 focus:ring-[#6E7A25]/30">
            </div>
            <div>
                <label class="text-[10px] text-white/50">{{ __('Allergies') }}</label>
                <input type="text" x-model="allergiesText" placeholder="e.g. Peanuts, Lactose, Gluten" class="mt-1 w-full px-3 py-2 text-sm border border-white/10 rounded-lg bg-white/5 text-white outline-none focus:ring-2 focus:ring-[#6E7A25]/30">
                <p class="text-[9px] text-white/30 mt-1">{{ __('Separate with commas') }}</p>
            </div>
        </div>
    </div>
</div>

<script>
function profileEditor() {
    return {
        editing: false,
        saving: false,
        allergiesText: '',
        form: {
            first_name: '{{ $profile['first_name'] }}',
            last_name: '{{ $profile['last_name'] }}',
            email: '{{ $profile['email'] }}',
            phone: '{{ $profile['phone'] }}',
            gender: '{{ strtolower($profile['gender']) }}',
            age: '{{ $profile['age'] ?? '' }}',
            height_cm: '{{ $profile['height'] ?? '' }}',
            weight_kg: '{{ $profile['weight'] ?? '' }}',
            fitness_goal: '{{ $profile['fitness_goal_raw'] ?? '' }}',
            dietary_preference: '{{ $profile['dietary_preference'] ?? '' }}',
            location: '{{ $profile['zone'] }}',
            address: '{{ $profile['address'] }}',
            allergies: @json($profile['allergies'] ?? []),
        },

        startEdit() {
            this.allergiesText = Array.isArray(this.form.allergies) ? this.form.allergies.join(', ') : '';
            this.editing = true;
        },

        cancelEdit() {
            this.editing = false;
        },

        async saveEdit() {
            this.saving = true;
            try {
                const payload = { ...this.form };
                if (this.allergiesText) {
                    payload.allergies = this.allergiesText.split(',').map(s => s.trim()).filter(s => s.length > 0);
                } else {
                    payload.allergies = [];
                }
                delete payload.email;
                if (payload.age === '') payload.age = null;
                if (payload.height_cm === '') payload.height_cm = null;
                if (payload.weight_kg === '') payload.weight_kg = null;
                if (payload.gender === '') payload.gender = null;
                if (payload.fitness_goal === '') payload.fitness_goal = null;

                const r = await fetch('{{ route('user.settings.update') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const d = await r.json();
                if (d.success) {
                    this.editing = false;
                    if (d.user) {
                        this.form.first_name = d.user.first_name || this.form.first_name;
                        this.form.last_name = d.user.last_name || this.form.last_name;
                        this.form.phone = d.user.phone || this.form.phone;
                        this.form.gender = d.user.gender || '';
                        this.form.age = d.user.age ?? '';
                        this.form.height_cm = d.user.height_cm ?? '';
                        this.form.weight_kg = d.user.weight_kg ?? '';
                        this.form.fitness_goal = d.user.fitness_goal || '';
                        this.form.dietary_preference = d.user.dietary_preference || '';
                        this.form.location = d.user.location || '';
                        this.form.address = d.user.address || '';
                        this.form.allergies = d.user.allergies || [];
                    }
                    alert('{{ __('Profile updated successfully!') }}');
                } else {
                    alert(d.error || '{{ __('Failed to update profile.') }}');
                }
            } catch(e) { console.error('Failed to update profile', e); alert('{{ __('Failed to update profile.') }}'); }
            finally { this.saving = false; }
        }
    }
}
</script>

{{-- Subscription Panel --}}
@if($subscriptionInfo)
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
    <div class="flex items-center gap-2 mb-5">
        <div class="w-8 h-8 rounded-lg bg-[#025C5F]/10 flex items-center justify-center">
            <svg class="w-4 h-4 text-[#025C5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </div>
        <h3 class="text-sm font-bold text-gray-900">{{ __('Active') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Subscription') }}</span></h3>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        {{-- Plan name --}}
        <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-3 sm:p-4 text-white shadow-md relative overflow-hidden">
            <div class="absolute top-0 right-0 w-12 h-12 bg-white/10 rounded-full -mr-6 -mt-6"></div>
            <div class="relative z-10">
                <span class="text-[10px] text-white/50">{{ __('Plan') }}</span>
                <p class="text-sm sm:text-base font-bold mt-1 truncate">{{ $subscriptionInfo['plan_name'] }}</p>
                <p class="text-[10px] text-white/40 mt-0.5">{{ $subscriptionInfo['duration_days'] }} days · {{ $subscriptionInfo['calories'] }} kcal</p>
            </div>
        </div>

        {{-- Meals progress --}}
        <div class="bg-white rounded-xl border border-gray-100 p-3 sm:p-4 shadow-sm">
            <span class="text-[10px] font-medium text-gray-400">{{ __('Meals Progress') }}</span>
            <div class="flex items-center gap-2 mt-1">
                <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $subscriptionInfo['meals_consumed'] }}</p>
                <p class="text-xs text-gray-400">/ {{ $subscriptionInfo['total_meals'] }}</p>
            </div>
            <div class="mt-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-full transition-all duration-1000" style="width: {{ $subscriptionInfo['progress'] }}%"></div>
            </div>
            <p class="text-[10px] text-gray-400 mt-1">{{ $subscriptionInfo['remaining'] }} {{ __('remaining') }}</p>
        </div>

        {{-- Period --}}
        <div class="bg-white rounded-xl border border-gray-100 p-3 sm:p-4 shadow-sm">
            <span class="text-[10px] font-medium text-gray-400">{{ __('Period') }}</span>
            <p class="text-xs sm:text-sm font-bold text-gray-900 mt-1">{{ $subscriptionInfo['start_date'] }}</p>
            <p class="text-[10px] text-gray-400">to {{ $subscriptionInfo['end_date'] }}</p>
            <div class="mt-2 flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold {{ $subscriptionInfo['status'] === 'active' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">
                    {{ ucfirst($subscriptionInfo['status']) }}
                </span>
                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold {{ $subscriptionInfo['payment_status'] === 'paid' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">
                    {{ ucfirst($subscriptionInfo['payment_status']) }}
                </span>
            </div>
        </div>

        {{-- Price & pauses --}}
        <div class="bg-white rounded-xl border border-gray-100 p-3 sm:p-4 shadow-sm">
            <span class="text-[10px] font-medium text-gray-400">{{ __('Price') }}</span>
            <p class="text-xl sm:text-2xl font-bold text-[#6E7A25] mt-1">SAR {{ number_format($subscriptionInfo['price']) }}</p>
            <p class="text-[10px] text-gray-400 mt-0.5">{{ $subscriptionInfo['meals_per_day'] }} {{ __('meals/day') }}</p>
            <p class="text-[10px] text-gray-400 mt-1">{{ $subscriptionInfo['remaining_pauses'] }} {{ __('pauses remaining') }}</p>
        </div>
    </div>
</div>
@endif

{{-- Payment History --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
    <div class="px-4 sm:px-5 py-4 border-b border-gray-50 flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900">{{ __('Payment') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('History') }}</span></h3>
        </div>
        <div class="text-right">
            <span class="text-[10px] text-gray-400">{{ __('Total Spent') }}</span>
            <p class="text-base sm:text-lg font-bold text-[#6E7A25]">SAR {{ number_format($totalSpent, 2) }}</p>
        </div>
    </div>

    @if(!empty($paymentHistory))
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[500px]">
            <thead>
                <tr class="text-left text-[10px] text-gray-400 border-b border-gray-50">
                    <th class="px-4 sm:px-5 py-3 font-medium">{{ __('Plan') }}</th>
                    <th class="px-4 sm:px-5 py-3 font-medium">{{ __('Amount') }}</th>
                    <th class="px-4 sm:px-5 py-3 font-medium">{{ __('Provider') }}</th>
                    <th class="px-4 sm:px-5 py-3 font-medium">{{ __('Date') }}</th>
                    <th class="px-4 sm:px-5 py-3 font-medium">{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paymentHistory as $payment)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-4 sm:px-5 py-3">
                        <span class="text-xs font-bold text-gray-900">{{ $payment['plan_name'] }}</span>
                    </td>
                    <td class="px-4 sm:px-5 py-3">
                        <span class="text-xs font-bold text-gray-900">{{ $payment['currency'] }} {{ number_format($payment['amount'], 2) }}</span>
                    </td>
                    <td class="px-4 sm:px-5 py-3">
                        <span class="text-[10px] text-gray-500 capitalize">{{ $payment['provider'] }}</span>
                        @if($payment['provider_payment_id'])
                        <p class="text-[9px] text-gray-400 truncate max-w-[100px] sm:max-w-[120px]">{{ $payment['provider_payment_id'] }}</p>
                        @endif
                    </td>
                    <td class="px-4 sm:px-5 py-3">
                        <span class="text-[10px] text-gray-500 whitespace-nowrap">{{ $payment['paid_at'] ?: $payment['created_at'] }}</span>
                    </td>
                    <td class="px-4 sm:px-5 py-3">
                        @if($payment['status'] === 'paid' || $payment['status'] === 'completed')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Paid') }}
                        </span>
                        @elseif($payment['status'] === 'pending')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-yellow-50 text-yellow-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __('Pending') }}
                        </span>
                        @elseif($payment['status'] === 'failed')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            {{ __('Failed') }}
                        </span>
                        @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500">
                            {{ ucfirst($payment['status']) }}
                        </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="p-8 sm:p-10 text-center">
        <div class="w-14 h-14 mx-auto bg-gradient-to-br from-[#6E7A25]/10 to-[#173327]/10 rounded-2xl flex items-center justify-center mb-3">
            <svg class="w-7 h-7 text-[#6E7A25]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <p class="text-sm font-bold text-gray-900">{{ __('No payment history') }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ __('Your payment transactions will appear here.') }}</p>
    </div>
    @endif
</div>

{{-- Logout Section --}}
<div class="bg-white rounded-2xl border border-red-100 shadow-sm p-5 sm:p-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Logout') }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">{{ __('Sign out of your account on this device.') }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 shadow-lg shadow-red-500/20 hover:shadow-red-500/30 transition-all duration-300 hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                {{ __('Logout') }}
            </button>
        </form>
    </div>
</div>

@endsection

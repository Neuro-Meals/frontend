@extends('layouts.user')

@section('title', __('Delivery Destinations') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Delivery Destinations'))

@section('content')

<div x-data="deliveryPreferencesPage()" x-init="init()" class="max-w-3xl mx-auto space-y-5">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-100 text-green-700 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-100 text-red-700 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div>
    @endif

    {{-- Hero Header --}}
    <div class="bg-gradient-to-br from-[#173327] to-[#025C5F] rounded-2xl p-6 text-white relative overflow-hidden shadow-lg shadow-[#025C5F]/20">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-[#6E7A25]/20 rounded-full -ml-16 -mb-16 blur-2xl"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center backdrop-blur-sm flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold">{{ __('Set Your Delivery Destinations') }}</h2>
                    <p class="text-xs text-white/70 mt-0.5">{{ __('Tell us where to deliver each meal category') }}</p>
                </div>
            </div>
            <div class="bg-white/10 rounded-xl p-3 flex items-start gap-2 backdrop-blur-sm">
                <svg class="w-4 h-4 text-amber-300 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-xs text-white/90">{{ __('You can have different delivery locations for each meal category (e.g. breakfast at home, lunch at work). Please set a destination for each category below.') }}</span>
            </div>
        </div>
    </div>

    {{-- Progress Indicator --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-700">{{ __('Progress') }}</span>
            <span class="text-xs font-bold text-[#6E7A25]" x-text="completedCount + ' / ' + preferences.length"></span>
        </div>
        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-[#6E7A25] to-[#173327] rounded-full transition-all duration-500" :style="'width: ' + (preferences.length > 0 ? (completedCount / preferences.length * 100) : 0) + '%'"></div>
        </div>
    </div>

    {{-- Category Cards --}}
    <form @submit.prevent="save()" class="space-y-4">
        <template x-for="(pref, index) in preferences" :key="index">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all" :class="isCategoryComplete(pref) ? 'border-green-200' : ''">
                {{-- Category Header --}}
                <div class="flex items-center justify-between p-4 border-b border-gray-50" :class="isCategoryComplete(pref) ? 'bg-green-50/30' : 'bg-gray-50/30'">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-800" x-text="pref.category_name"></h3>
                            <p class="text-[10px] text-gray-400" x-show="isCategoryComplete(pref)">{{ __('Completed') }}</p>
                            <p class="text-[10px] text-amber-500" x-show="!isCategoryComplete(pref)">{{ __('Please fill all required fields') }}</p>
                        </div>
                    </div>
                    <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 transition-all" :class="isCategoryComplete(pref) ? 'bg-green-100' : 'bg-gray-100'">
                        <svg x-show="isCategoryComplete(pref)" class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        <svg x-show="!isCategoryComplete(pref)" class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                </div>

                {{-- Category Body --}}
                <div class="p-4 space-y-4">
                    {{-- Place Type & Name --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Place Type') }} <span class="text-red-500">*</span></label>
                            <select x-model="pref.place_type" required class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-white outline-none focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] transition-all">
                                <option value="">{{ __('Select...') }}</option>
                                <option value="home">{{ __('Home') }}</option>
                                <option value="work">{{ __('Work') }}</option>
                                <option value="gym">{{ __('Gym') }}</option>
                                <option value="school">{{ __('School') }}</option>
                                <option value="university">{{ __('University') }}</option>
                                <option value="other">{{ __('Other') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Place Name') }}</label>
                            <input type="text" x-model="pref.place_name" class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-white outline-none focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] transition-all" :placeholder="pref.place_type === 'home' ? '{{ __('e.g. My Apartment') }}' : '{{ __('e.g. Office Building') }}'">
                        </div>
                    </div>

                    {{-- City & Area --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('City') }} <span class="text-red-500">*</span></label>
                            <input type="text" x-model="pref.city" required class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-white outline-none focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] transition-all" placeholder="Riyadh">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Delivery Area') }} <span class="text-red-500">*</span></label>
                            <input type="text" x-model="pref.delivery_area" required class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-white outline-none focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] transition-all" placeholder="{{ __('e.g. Al Olaya') }}">
                        </div>
                    </div>

                    {{-- Address --}}
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Delivery Address') }} <span class="text-red-500">*</span></label>
                        <input type="text" x-model="pref.delivery_address" required class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-white outline-none focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] transition-all" placeholder="{{ __('e.g. King Fahd Rd, Building 123, Apt 4') }}">
                    </div>

                    {{-- Time & Note --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Preferred Delivery Time') }} <span class="text-red-500">*</span></label>
                            <input type="time" x-model="pref.preferred_delivery_time" required class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-white outline-none focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Delivery Note') }}</label>
                            <input type="text" x-model="pref.delivery_note" class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-white outline-none focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] transition-all" placeholder="{{ __('e.g. Ring the bell twice') }}">
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- Message --}}
        <div x-show="message" x-transition class="px-4 py-3 rounded-xl" :class="success ? 'bg-green-50 border border-green-100' : 'bg-red-50 border border-red-100'">
            <span class="text-xs font-medium" :class="success ? 'text-green-600' : 'text-red-600'" x-text="message"></span>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <a href="{{ route('user.dashboard') }}" class="px-5 py-3 text-sm font-bold rounded-xl bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all text-center order-2 sm:order-1">
                {{ __('Skip for Now') }}
            </a>
            <button type="submit" :disabled="saving || completedCount === 0" class="flex-1 px-5 py-3 text-sm font-bold rounded-xl bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white hover:shadow-lg hover:shadow-[#6E7A25]/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 order-1 sm:order-2">
                <svg x-show="saving" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span x-text="saving ? '{{ __('Saving...') }}' : '{{ __('Save Delivery Preferences') }}'"></span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function deliveryPreferencesPage() {
    return {
        saving: false,
        message: '',
        success: false,
        preferences: @json($deliveryPrefsJson),

        get completedCount() {
            return this.preferences.filter(p => this.isCategoryComplete(p)).length;
        },

        init() {},

        isCategoryComplete(pref) {
            return pref.place_type && pref.city && pref.delivery_area && pref.delivery_address && pref.preferred_delivery_time;
        },

        async save() {
            this.saving = true;
            this.message = '';
            try {
                const payload = {
                    delivery_preferences: this.preferences.map(p => ({
                        meal_category_id: p.meal_category_id,
                        place_type: p.place_type,
                        place_name: p.place_name || null,
                        city: p.city,
                        delivery_area: p.delivery_area,
                        delivery_address: p.delivery_address,
                        latitude: p.latitude || null,
                        longitude: p.longitude || null,
                        preferred_delivery_time: p.preferred_delivery_time,
                        delivery_note: p.delivery_note || null,
                    }))
                };

                const r = await fetch('{{ route('user.onboarding.delivery-preferences') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const d = await r.json();
                this.success = d.success;
                if (d.success) {
                    this.message = d.message || '{{ __('Delivery preferences saved! Redirecting...') }}';
                    setTimeout(() => { window.location.href = '{{ route('user.dashboard') }}'; }, 1500);
                } else {
                    this.message = d.error || d.message || '{{ __('Failed to save. Please try again.') }}';
                }
            } catch(e) {
                console.error('Delivery preferences save failed', e);
                this.success = false;
                this.message = '{{ __('Failed to save. Please try again.') }}';
            } finally {
                this.saving = false;
            }
        }
    }
}
</script>
@endpush

@endsection

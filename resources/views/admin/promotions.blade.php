@extends('layouts.admin')

@section('title', __('Promotions') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Promotions'))

@section('content')
<div
    x-data="promotionsApp()"
    class="space-y-5"
>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-black text-gray-900">{{ __('Promotions & Referrals') }}</h1>
            <p class="mt-1 text-xs text-gray-400">{{ __('Manage discount codes, referral rewards and qualification rules.') }}</p>
        </div>
        <button type="button" @click="openCoupon()" class="rounded-xl bg-gradient-to-r from-[#173327] to-[#6E7A25] px-4 py-2.5 text-xs font-extrabold text-white shadow">
            + {{ __('New Discount Code') }}
        </button>
    </div>

    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-2xl font-black text-[#173327]">{{ count($coupons) }}</p>
            <p class="text-[10px] font-bold text-gray-400">{{ __('Discount Codes') }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-2xl font-black text-green-600">{{ count(array_filter($coupons, fn ($c) => (bool) ($c['is_active'] ?? false))) }}</p>
            <p class="text-[10px] font-bold text-gray-400">{{ __('Active Codes') }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-2xl font-black text-[#6E7A25]">{{ (int) ($referralMeta['total'] ?? count($referrals)) }}</p>
            <p class="text-[10px] font-bold text-gray-400">{{ __('Referrals') }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-2xl font-black text-amber-600">SAR {{ number_format((float) ($referralSettings['reward_amount'] ?? 100), 0) }}</p>
            <p class="text-[10px] font-bold text-gray-400">{{ __('Referral Reward') }}</p>
        </div>
    </div>

    <div class="flex gap-2 overflow-x-auto rounded-2xl border border-gray-100 bg-white p-2 shadow-sm">
        <button type="button" @click="tab='coupons'" :class="tab==='coupons' ? 'bg-[#173327] text-white' : 'text-gray-500'" class="whitespace-nowrap rounded-xl px-4 py-2 text-xs font-extrabold">{{ __('Discount Codes') }}</button>
        <button type="button" @click="tab='referrals'" :class="tab==='referrals' ? 'bg-[#173327] text-white' : 'text-gray-500'" class="whitespace-nowrap rounded-xl px-4 py-2 text-xs font-extrabold">{{ __('Referral Program') }}</button>
    </div>

    <section x-show="tab==='coupons'" class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-[900px] w-full text-left text-xs">
                <thead class="bg-gray-50 text-[10px] uppercase tracking-wider text-gray-400">
                    <tr>
                        <th class="px-4 py-3">{{ __('Code') }}</th>
                        <th class="px-4 py-3">{{ __('Discount') }}</th>
                        <th class="px-4 py-3">{{ __('Usage') }}</th>
                        <th class="px-4 py-3">{{ __('Rules') }}</th>
                        <th class="px-4 py-3">{{ __('Status') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($coupons as $coupon)
                    <tr>
                        <td class="px-4 py-4">
                            <p class="font-mono text-sm font-black text-[#173327]">{{ $coupon['code'] ?? '' }}</p>
                            <p class="mt-1 max-w-xs truncate text-[10px] text-gray-400">{{ $coupon['description'] ?? '' }}</p>
                        </td>
                        <td class="px-4 py-4 font-bold">
                            @if(($coupon['discount_type'] ?? '') === 'percentage')
                                {{ number_format((float) ($coupon['discount_value'] ?? 0), 0) }}%
                            @else
                                SAR {{ number_format((float) ($coupon['discount_value'] ?? 0), 2) }}
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            {{ (int) ($coupon['used_count'] ?? 0) }}
                            @if(!empty($coupon['max_uses'])) / {{ (int) $coupon['max_uses'] }} @endif
                        </td>
                        <td class="px-4 py-4 text-[10px] text-gray-500">
                            @if(!empty($coupon['new_customers_only'])) <span class="mr-1 rounded bg-blue-50 px-2 py-1 text-blue-700">{{ __('New only') }}</span> @endif
                            @if(!empty($coupon['applicable_plan_id'])) <span class="rounded bg-gray-100 px-2 py-1">{{ __('Plan') }} #{{ $coupon['applicable_plan_id'] }}</span> @endif
                        </td>
                        <td class="px-4 py-4">
                            <span class="rounded-full px-2 py-1 text-[9px] font-extrabold {{ !empty($coupon['is_active']) ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ !empty($coupon['is_active']) ? __('Active') : __('Inactive') }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <button type="button" @click='editCoupon(@json($coupon))' class="font-bold text-[#6E7A25]">{{ __('Edit') }}</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">{{ __('No discount codes yet.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section x-show="tab==='referrals'" x-cloak class="grid gap-5 lg:grid-cols-3">
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm lg:col-span-1">
            <h2 class="text-sm font-black text-gray-900">{{ __('Referral Settings') }}</h2>
            <div class="mt-4 space-y-4">
                <label class="flex items-center justify-between rounded-xl bg-gray-50 p-3">
                    <span class="text-xs font-bold text-gray-700">{{ __('Program Active') }}</span>
                    <input type="checkbox" x-model="referralSettings.is_active">
                </label>
                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">{{ __('Reward Amount (SAR)') }}</label>
                    <input type="number" min="1" step="1" x-model.number="referralSettings.reward_amount" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">{{ __('Reward Expiry Days') }}</label>
                    <input type="number" min="1" x-model.number="referralSettings.reward_expiry_days" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm">
                </div>
                <label class="flex items-start gap-2 rounded-xl bg-green-50 p-3">
                    <input type="checkbox" x-model="referralSettings.referred_customer_must_make_first_payment" class="mt-0.5">
                    <span class="text-[11px] font-semibold text-green-800">{{ __('Reward only after the referred customer completes their first successful payment.') }}</span>
                </label>
                <button type="button" @click="saveReferralSettings()" :disabled="savingReferral" class="w-full rounded-xl bg-[#173327] py-3 text-xs font-extrabold text-white disabled:opacity-50">
                    {{ __('Save Referral Program') }}
                </button>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden lg:col-span-2">
            <div class="border-b border-gray-100 px-5 py-4">
                <h2 class="text-sm font-black text-gray-900">{{ __('Recent Referrals') }}</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($referrals as $referral)
                    <div class="flex items-center justify-between gap-3 px-5 py-4">
                        <div>
                            <p class="text-xs font-bold text-gray-800">{{ __('Referral') }} #{{ $referral['id'] ?? '-' }}</p>
                            <p class="mt-1 text-[10px] text-gray-400">
                                {{ __('Referrer') }} #{{ $referral['referrer_user_id'] ?? '-' }}
                                → {{ __('Customer') }} #{{ $referral['referred_user_id'] ?? '-' }}
                            </p>
                        </div>
                        <span class="rounded-full bg-gray-100 px-2.5 py-1 text-[9px] font-extrabold uppercase text-gray-600">
                            {{ __($referral['status'] ?? 'pending') }}
                        </span>
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-gray-400">{{ __('No referrals recorded yet.') }}</div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Coupon Modal --}}
    <div x-show="couponModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/45 p-3" @click.self="couponModal=false">
        <div class="max-h-[92vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
            <div class="sticky top-0 flex items-center justify-between border-b border-gray-100 bg-white px-5 py-4">
                <div>
                    <h3 class="text-base font-black text-gray-900" x-text="couponForm.id ? '{{ __('Edit Discount Code') }}' : '{{ __('New Discount Code') }}'"></h3>
                    <p class="mt-0.5 text-[10px] text-gray-400">{{ __('The backend validates all rules during checkout.') }}</p>
                </div>
                <button type="button" @click="couponModal=false" class="text-gray-400">✕</button>
            </div>

            <div class="grid gap-4 p-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">{{ __('Code') }}</label>
                    <input type="text" x-model="couponForm.code" :disabled="!!couponForm.id" @input="couponForm.code=couponForm.code.toUpperCase()" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm font-black uppercase">
                </div>
                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">{{ __('Discount Type') }}</label>
                    <select x-model="couponForm.discount_type" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm">
                        <option value="percentage">{{ __('Percentage') }}</option>
                        <option value="fixed">{{ __('Fixed SAR') }}</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">{{ __('Discount Value') }}</label>
                    <input type="number" min="0.01" step="0.01" x-model.number="couponForm.discount_value" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">{{ __('Minimum Amount') }}</label>
                    <input type="number" min="0" step="0.01" x-model.number="couponForm.min_order_amount" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">{{ __('Total Usage Limit') }}</label>
                    <input type="number" min="1" x-model.number="couponForm.max_uses" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">{{ __('Per Customer Limit') }}</label>
                    <input type="number" min="1" x-model.number="couponForm.max_uses_per_user" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">{{ __('Applicable Plan') }}</label>
                    <select x-model="couponForm.applicable_plan_id" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm">
                        <option value="">{{ __('All Plans') }}</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan['id'] }}">{{ $plan['name_en'] ?? ('Plan #' . $plan['id']) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">{{ __('Description') }}</label>
                    <textarea x-model="couponForm.description" rows="2" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm"></textarea>
                </div>
                <label class="flex items-center gap-2"><input type="checkbox" x-model="couponForm.new_customers_only"><span class="text-xs font-bold">{{ __('New customers only') }}</span></label>
                <label class="flex items-center gap-2"><input type="checkbox" x-model="couponForm.is_active"><span class="text-xs font-bold">{{ __('Active') }}</span></label>
            </div>

            <div class="sticky bottom-0 flex gap-2 border-t border-gray-100 bg-white p-4">
                <button type="button" @click="couponModal=false" class="flex-1 rounded-xl bg-gray-100 py-3 text-xs font-bold text-gray-600">{{ __('Cancel') }}</button>
                <button type="button" @click="saveCoupon()" :disabled="savingCoupon" class="flex-1 rounded-xl bg-[#173327] py-3 text-xs font-extrabold text-white disabled:opacity-50">{{ __('Save Code') }}</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function promotionsApp() {
    return {
        tab: 'coupons',
        couponModal: false,
        savingCoupon: false,
        savingReferral: false,
        referralSettings: @json($referralSettings),
        couponForm: {},

        emptyCoupon() {
            return {
                id: null,
                code: '',
                description: '',
                discount_type: 'percentage',
                discount_value: 10,
                max_uses: null,
                max_uses_per_user: 1,
                min_order_amount: null,
                applicable_plan_id: '',
                allowed_user_id: null,
                new_customers_only: false,
                is_active: true,
            };
        },

        openCoupon() {
            this.couponForm = this.emptyCoupon();
            this.couponModal = true;
        },

        editCoupon(coupon) {
            this.couponForm = {
                ...this.emptyCoupon(),
                ...coupon,
                applicable_plan_id: coupon.applicable_plan_id || '',
            };
            this.couponModal = true;
        },

        cleanPayload(payload) {
            const cleaned = { ...payload };
            delete cleaned.id;
            delete cleaned.used_count;
            delete cleaned.created_at;
            delete cleaned.source;

            ['max_uses', 'max_uses_per_user', 'min_order_amount', 'applicable_plan_id', 'allowed_user_id']
                .forEach(key => {
                    if (cleaned[key] === '' || cleaned[key] === null || cleaned[key] === undefined) {
                        cleaned[key] = null;
                    } else {
                        cleaned[key] = Number(cleaned[key]);
                    }
                });

            cleaned.discount_value = Number(cleaned.discount_value || 0);
            return cleaned;
        },

        async saveCoupon() {
            if (this.savingCoupon) return;
            this.savingCoupon = true;

            try {
                const isEdit = !!this.couponForm.id;
                const url = isEdit
                    ? @js(route('admin.promotions.coupons.update', ['id' => '__ID__'])).replace('__ID__', this.couponForm.id)
                    : @js(route('admin.promotions.coupons.store'));

                const response = await fetch(url, {
                    method: isEdit ? 'PUT' : 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @js(csrf_token()),
                    },
                    body: JSON.stringify(this.cleanPayload(this.couponForm)),
                });

                const data = await response.json().catch(() => ({}));
                if (!response.ok || data.success === false) {
                    throw new Error(data.message || '{{ __('Unable to save discount code.') }}');
                }

                await Swal.fire({
                    icon: 'success',
                    title: '{{ __('Saved') }}',
                    text: data.message || '{{ __('Discount code saved.') }}',
                    confirmButtonColor: '#173327',
                });
                window.location.reload();
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('Error') }}',
                    text: error.message || '{{ __('Unable to save discount code.') }}',
                    confirmButtonColor: '#173327',
                });
            } finally {
                this.savingCoupon = false;
            }
        },

        async saveReferralSettings() {
            if (this.savingReferral) return;
            this.savingReferral = true;

            try {
                const response = await fetch(@js(route('admin.promotions.referrals.settings')), {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @js(csrf_token()),
                    },
                    body: JSON.stringify({
                        is_active: !!this.referralSettings.is_active,
                        reward_amount: Number(this.referralSettings.reward_amount),
                        reward_expiry_days: Number(this.referralSettings.reward_expiry_days),
                        referred_customer_must_make_first_payment:
                            !!this.referralSettings.referred_customer_must_make_first_payment,
                    }),
                });

                const data = await response.json().catch(() => ({}));
                if (!response.ok || data.success === false) {
                    throw new Error(data.message || '{{ __('Unable to update referral program.') }}');
                }

                Swal.fire({
                    icon: 'success',
                    title: '{{ __('Referral Program Updated') }}',
                    text: data.message,
                    confirmButtonColor: '#173327',
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('Error') }}',
                    text: error.message,
                    confirmButtonColor: '#173327',
                });
            } finally {
                this.savingReferral = false;
            }
        },
    };
}
</script>
@endpush
@endsection

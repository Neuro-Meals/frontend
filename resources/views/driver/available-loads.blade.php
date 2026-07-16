@extends('layouts.driver')

@section('title', __('Available Loads') . ' - ' . __('Nutrio Meals'))

@section('content')
<div x-data="availableLoads()" class="pb-24">
    {{-- Header --}}
    <div class="bg-gradient-to-br from-brand-700 to-brand-600 text-white p-5 rounded-b-3xl shadow-lg shadow-brand-700/20 animate-slide-up">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('driver.dashboard') }}" class="p-2 -ml-2 rounded-full hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-lg font-bold">{{ __('Available Loads') }}</h1>
        </div>
        <p class="text-xs text-white/70">{{ __('Ready orders with no driver yet — claim one to add it to your deliveries.') }}</p>
    </div>

    <div class="p-4 space-y-3">
        @forelse($loads as $load)
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 animate-slide-up">
            <div class="flex items-start justify-between gap-3 mb-2">
                <div class="min-w-0">
                    <p class="text-sm font-bold text-gray-900">{{ $load['order_number'] ?? ('#' . ($load['order_id'] ?? '')) }}</p>
                    <p class="text-xs text-gray-500 mt-0.5 flex items-start gap-1">
                        <svg class="w-3.5 h-3.5 text-brand-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $load['delivery_address'] ?: __('No address provided') }}</span>
                    </p>
                </div>
                <span class="px-2 py-1 rounded-full bg-brand-50 text-brand-700 text-[10px] font-bold flex-shrink-0">{{ $load['item_count'] ?? count($load['items'] ?? []) }} {{ __('items') }}</span>
            </div>

            @if(!empty($load['items']))
            <div class="flex flex-wrap gap-1.5 mb-3">
                @foreach($load['items'] as $item)
                <span class="px-2 py-1 rounded-full bg-gray-50 border border-gray-100 text-[10px] text-gray-600">{{ $item['meal_name'] ?? __('Item') }} × {{ $item['quantity'] ?? 1 }}</span>
                @endforeach
            </div>
            @endif

            @if(!empty($load['delivery_notes']))
            <div class="flex items-start gap-2 mb-3 bg-amber-50 p-2.5 rounded-xl border border-amber-100">
                <svg class="w-4 h-4 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <p class="text-xs text-amber-800 italic">{{ $load['delivery_notes'] }}</p>
            </div>
            @endif

            <button @click="claim({{ $load['order_id'] }}, $event.target)"
                :disabled="claiming === {{ $load['order_id'] }}"
                class="btn-action w-full py-3 rounded-xl bg-gradient-to-l from-brand-700 to-brand-600 text-white text-sm font-bold shadow-md shadow-brand-700/20 flex items-center justify-center gap-2 disabled:opacity-60">
                <svg x-show="claiming !== {{ $load['order_id'] }}" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <svg x-show="claiming === {{ $load['order_id'] }}" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                <span x-text="claiming === {{ $load['order_id'] }} ? strings.claiming : strings.claim"></span>
            </button>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm">
            <div class="w-14 h-14 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <p class="text-sm text-gray-500">{{ __('No loads available right now.') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ __('Check back soon — new ready orders appear here as the kitchen finishes them.') }}</p>
        </div>
        @endforelse
    </div>
</div>

<script>
function availableLoads() {
    return {
        claiming: null,
        strings: {
            claim: @json(__('Claim This Load')),
            claiming: @json(__('Claiming...')),
            confirmTitle: @json(__('Claim this load?')),
            confirmText: @json(__('This order will be added to your deliveries.')),
            yesClaim: @json(__('Yes, Claim')),
            cancel: @json(__('Cancel')),
        },

        async claim(orderId) {
            const confirmed = await Swal.fire({
                title: this.strings.confirmTitle,
                text: this.strings.confirmText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#173327',
                cancelButtonColor: '#d1d5db',
                confirmButtonText: this.strings.yesClaim,
                cancelButtonText: this.strings.cancel,
                reverseButtons: true,
                customClass: { popup: 'rounded-2xl' },
            });
            if (!confirmed.isConfirmed) return;

            this.claiming = orderId;
            try {
                const res = await fetch(`{{ url('driver/available-loads') }}/${orderId}/claim`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                });
                const data = await res.json();
                if (data.success) {
                    window.location.href = '{{ route('driver.deliveries') }}';
                } else {
                    Swal.fire({ title: '{{ __('Error') }}', text: data.message, icon: 'error', customClass: { popup: 'rounded-2xl' } });
                    this.claiming = null;
                }
            } catch (e) {
                Swal.fire({ title: '{{ __('Error') }}', text: String(e), icon: 'error', customClass: { popup: 'rounded-2xl' } });
                this.claiming = null;
            }
        },
    };
}
</script>
@endsection

@extends('layouts.driver')

@section('title', __('My Deliveries') . ' - ' . __('Nutrio Meals'))

@section('content')
<div x-data="{ tab: 'active' }" class="pb-4">
    {{-- Header --}}
    <div class="bg-gradient-to-br from-brand-700 to-brand-600 text-white p-5 rounded-b-3xl shadow-lg shadow-brand-700/20">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('driver.dashboard') }}" class="p-2 -ml-2 rounded-full hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-lg font-bold">{{ __('My Deliveries') }}</h1>
        </div>
        <p class="text-xs text-white/70">{{ __('Manage your assigned deliveries') }}</p>
    </div>

    <div class="p-4">
        {{-- Tabs --}}
        <div class="bg-white rounded-2xl p-1 shadow-sm border border-gray-100 mb-4 flex">
            <button @click="tab = 'active'" :class="tab === 'active' ? 'bg-brand-700 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-50'" class="flex-1 py-2 rounded-xl text-xs font-bold transition-all">{{ __('Active') }}</button>
            <button @click="tab = 'history'" :class="tab === 'history' ? 'bg-brand-700 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-50'" class="flex-1 py-2 rounded-xl text-xs font-bold transition-all">{{ __('History') }}</button>
        </div>

        @php
        $activeDeliveries = array_filter($deliveries, fn ($d) => !in_array($d['status'], ['delivered', 'failed', 'cancelled']));
        $historyDeliveries = array_filter($deliveries, fn ($d) => in_array($d['status'], ['delivered', 'failed', 'cancelled']));
        @endphp

        {{-- Active Tab --}}
        <div x-show="tab === 'active'" class="space-y-3 animate-slide-up">
            @forelse($activeDeliveries as $delivery)
            @php
                $statusColor = match($delivery['status']) {
                    'assigned' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'picked_up' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'out_for_delivery' => 'bg-purple-50 text-purple-700 border-purple-200',
                    default => 'bg-gray-50 text-gray-600 border-gray-200',
                };
            @endphp
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <p class="text-xs font-bold text-gray-900">{{ $delivery['order_number'] }}</p>
                        <p class="text-[10px] text-gray-400">{{ $delivery['zone'] }} · {{ $delivery['time'] }}</p>
                    </div>
                    <span class="px-2 py-1 rounded-full text-[10px] font-semibold border {{ $statusColor }}">{{ __($delivery['status_label']) }}</span>
                </div>
                <div class="flex items-start gap-2 mb-4">
                    <svg class="w-4 h-4 text-brand-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <p class="text-xs text-gray-700 leading-relaxed">{{ $delivery['address'] ?: __('No address provided') }}</p>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    @if(in_array($delivery['status'], ['assigned', 'pending']))
                    <form action="{{ route('driver.deliveries.status', $delivery['id']) }}" method="POST" class="col-span-2">
                        @csrf
                        <input type="hidden" name="status" value="picked_up">
                        <button type="submit" class="btn-action w-full py-2.5 rounded-xl bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-700/20 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Pick Up') }}
                        </button>
                    </form>
                    @elseif($delivery['status'] === 'picked_up')
                    <form action="{{ route('driver.deliveries.status', $delivery['id']) }}" method="POST" class="col-span-2">
                        @csrf
                        <input type="hidden" name="status" value="out_for_delivery">
                        <button type="submit" class="btn-action w-full py-2.5 rounded-xl bg-purple-600 text-white text-xs font-bold shadow-md shadow-purple-600/20 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            {{ __('Out for Delivery') }}
                        </button>
                    </form>
                    @elseif($delivery['status'] === 'out_for_delivery')
                    <form action="{{ route('driver.deliveries.status', $delivery['id']) }}" method="POST" class="col-span-1">
                        @csrf
                        <input type="hidden" name="status" value="delivered">
                        <button type="submit" class="btn-action w-full py-2.5 rounded-xl bg-green-600 text-white text-xs font-bold shadow-md shadow-green-600/20 flex items-center justify-center gap-1">
                            {{ __('Delivered') }}
                        </button>
                    </form>
                    <button onclick="openFailModal({{ $delivery['id'] }})" class="btn-action w-full py-2.5 rounded-xl bg-red-50 text-red-600 border border-red-100 text-xs font-bold flex items-center justify-center gap-1">
                        {{ __('Failed') }}
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm">
                <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-sm text-gray-500">{{ __('No active deliveries.') }}</p>
                <a href="{{ route('driver.dashboard') }}" class="inline-block mt-3 text-xs font-bold text-brand-600">{{ __('Back to Dashboard') }}</a>
            </div>
            @endforelse
        </div>

        {{-- History Tab --}}
        <div x-show="tab === 'history'" class="space-y-3 animate-slide-up" style="display: none;">
            @forelse($historyDeliveries as $delivery)
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-900">{{ $delivery['order_number'] }}</p>
                    <p class="text-[10px] text-gray-400">{{ $delivery['date'] }} · {{ $delivery['customer'] }}</p>
                    @if($delivery['status'] === 'failed' && $delivery['failure_reason'])
                    <p class="text-[10px] text-red-500 mt-1">{{ $delivery['failure_reason'] }}</p>
                    @endif
                </div>
                <span class="px-2 py-1 rounded-full text-[10px] font-semibold border {{ $delivery['status'] === 'delivered' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-600 border-red-200' }}">
                    {{ __($delivery['status_label']) }}
                </span>
            </div>
            @empty
            <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm">
                <p class="text-sm text-gray-500">{{ __('No delivery history yet.') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<div id="failModal" class="fixed inset-0 z-50 hidden flex items-end justify-center" style="background: rgba(0,0,0,0.5);">
    <div class="bg-white rounded-t-3xl w-full max-w-md p-5 pb-8 animate-slide-up">
        <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-5"></div>
        <h3 class="text-base font-bold text-gray-900 mb-1">{{ __('Mark Delivery as Failed') }}</h3>
        <p class="text-xs text-gray-400 mb-4">{{ __('Please tell us why the delivery could not be completed.') }}</p>
        <form id="failForm" action="" method="POST" class="space-y-3">
            @csrf
            <input type="hidden" name="status" value="failed">
            <textarea name="reason" rows="3" placeholder="{{ __('Reason...') }}" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20 outline-none resize-none" required></textarea>
            <div class="flex gap-2">
                <button type="button" onclick="closeFailModal()" class="flex-1 py-3 rounded-xl border border-gray-200 text-xs font-bold text-gray-600">{{ __('Cancel') }}</button>
                <button type="submit" class="flex-1 py-3 rounded-xl bg-red-600 text-white text-xs font-bold shadow-md shadow-red-600/20">{{ __('Submit') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openFailModal(id) {
    const form = document.getElementById('failForm');
    form.action = `{{ url('driver/deliveries') }}/${id}/status`;
    document.getElementById('failModal').classList.remove('hidden');
}

function closeFailModal() {
    document.getElementById('failModal').classList.add('hidden');
}

// Close modal when clicking outside
const failModal = document.getElementById('failModal');
failModal.addEventListener('click', function(e) {
    if (e.target === failModal) closeFailModal();
});
</script>
@endpush

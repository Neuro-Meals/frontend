@php
$statusColor = match($order['status']) {
    'pending' => 'bg-blue-50 text-blue-700 border-blue-200',
    'confirmed' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
    'preparing' => 'bg-amber-50 text-amber-700 border-amber-200',
    'ready_for_delivery' => 'bg-green-50 text-green-700 border-green-200',
    'out_for_delivery' => 'bg-purple-50 text-purple-700 border-purple-200',
    'delivered' => 'bg-gray-50 text-gray-500 border-gray-200',
    'cancelled' => 'bg-red-50 text-red-600 border-red-200',
    default => 'bg-gray-50 text-gray-600 border-gray-200',
};
@endphp

<div class="meal-card bg-white rounded-2xl p-4 shadow-sm border border-gray-100 animate-slide-up">
    {{-- Top row: order number + status --}}
    <div class="flex items-start justify-between mb-3">
        <div class="flex-1 min-w-0">
            <p class="text-xs font-bold text-gray-900 truncate">{{ $order['order_number'] }}</p>
            <p class="text-[10px] text-gray-400 mt-0.5">{{ $order['customer'] }} · {{ $order['time'] }}</p>
        </div>
        <span class="px-2 py-1 rounded-full text-[10px] font-semibold border {{ $statusColor }} flex-shrink-0 ml-2">{{ __($order['status_label']) }}</span>
    </div>

    {{-- Meal summary --}}
    <div class="flex items-start gap-2 mb-2">
        <svg class="w-4 h-4 text-chef-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        <p class="text-xs text-gray-700 leading-relaxed flex-1">{{ $order['meal_summary'] }}</p>
    </div>

    {{-- Info row --}}
    <div class="flex items-center gap-3 mb-2 flex-wrap">
        @if($order['meal_count'] > 0)
        <div class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <span class="text-[10px] font-semibold text-gray-600">{{ $order['meal_count'] }} {{ __('items') }}</span>
        </div>
        @endif
        @if($order['total_calories'] > 0)
        <div class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <span class="text-[10px] font-semibold text-gray-600">{{ $order['total_calories'] }} kcal</span>
        </div>
        @endif
        <div class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-[10px] font-semibold text-gray-600">{{ number_format($order['total_amount'], 2) }} SAR</span>
        </div>
    </div>

    @if($order['delivery_address'])
    <div class="flex items-start gap-2 mb-2">
        <svg class="w-3.5 h-3.5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <p class="text-[10px] text-gray-500 leading-relaxed flex-1">{{ $order['delivery_address'] }}</p>
    </div>
    @endif

    @if($order['delivery_notes'])
    <div class="flex items-start gap-2 mb-3 bg-amber-50 p-2 rounded-lg border border-amber-100">
        <svg class="w-3.5 h-3.5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        <p class="text-[10px] text-amber-800 leading-tight flex-1 italic">{{ $order['delivery_notes'] }}</p>
    </div>
    @endif

    {{-- Action buttons --}}
    @if(!in_array($order['status'], ['delivered', 'cancelled', 'out_for_delivery']))
    <div class="grid grid-cols-1 gap-2 mt-3">
        @if(in_array($order['status'], ['pending', 'confirmed']))
        <button type="button"
            onclick="confirmMealAction('{{ route('chef.orders.start_preparing', $order['id']) }}', 'preparing', {
                title: '{{ __('Start preparing this order?') }}',
                text: '{{ __('This will mark the order as being prepared in the kitchen.') }}',
                confirmText: '{{ __('Yes, Start') }}',
                icon: 'question',
                confirmColor: '#C2410C'
            })"
            class="btn-action w-full py-2.5 rounded-xl bg-chef-600 text-white text-xs font-bold shadow-md shadow-chef-600/20 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            {{ __('Start Preparing') }}
        </button>
        @elseif($order['status'] === 'preparing')
        <button type="button"
            onclick="confirmMealAction('{{ route('chef.orders.mark_ready', $order['id']) }}', 'ready', {
                title: '{{ __('Mark order as ready?') }}',
                text: '{{ __('This will notify the team that the order is ready for delivery.') }}',
                confirmText: '{{ __('Yes, Ready') }}',
                icon: 'success',
                confirmColor: '#16a34a'
            })"
            class="btn-action w-full py-2.5 rounded-xl bg-green-600 text-white text-xs font-bold shadow-md shadow-green-600/20 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ __('Mark as Ready') }}
        </button>
        @elseif($order['status'] === 'ready_for_delivery')
        <div class="flex items-center justify-center gap-1.5 py-2 rounded-xl bg-green-50 border border-green-100">
            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-[10px] font-bold text-green-600">{{ __('Ready for delivery') }}</span>
        </div>
        @endif
    </div>
    @endif

    @if($order['status'] === 'delivered')
    <div class="flex items-center justify-center gap-1.5 mt-2 pt-3 border-t border-gray-50">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="text-[10px] font-bold text-gray-400">{{ __('Order delivered') }}</span>
    </div>
    @endif
</div>

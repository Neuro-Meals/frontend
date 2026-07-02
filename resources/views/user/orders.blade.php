@extends('layouts.user')

@section('title', 'Orders - Nutrio Meals')
@section('page_title', 'My Orders')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-4 text-white shadow-lg shadow-[#6E7A25]/20">
        <span class="text-[10px] font-medium text-white/60">Total Orders</span>
        <div class="text-2xl font-bold mt-1">{{ $stats['total'] }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">Delivered</span>
        <div class="text-2xl font-bold text-green-600 mt-1">{{ $stats['delivered'] }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">Cancelled</span>
        <div class="text-2xl font-bold text-red-500 mt-1">{{ $stats['cancelled'] }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">Total Spent</span>
        <div class="text-2xl font-bold text-gray-900 mt-1">SAR {{ number_format($stats['totalSpent']) }}</div>
    </div>
</div>

{{-- Orders Table --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50">
        <h3 class="text-sm font-bold text-gray-900">Order <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">History</span></h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b border-gray-50">
                    <th class="px-5 py-3 font-medium">Order ID</th>
                    <th class="px-5 py-3 font-medium">Plan</th>
                    <th class="px-5 py-3 font-medium">Meals</th>
                    <th class="px-5 py-3 font-medium">Amount</th>
                    <th class="px-5 py-3 font-medium">Date</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">{{ $order['id'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $order['plan'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $order['meals'] }}</td>
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">SAR {{ $order['amount'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ date('M d, Y', strtotime($order['date'])) }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $order['status'] === 'delivered' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">{{ ucfirst($order['status']) }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

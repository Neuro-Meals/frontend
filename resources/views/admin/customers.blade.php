@extends('layouts.admin')

@section('title', 'Customers - Nutrio Meals')
@section('page_title', 'Customers')

@section('content')
@php
    $statusColors = [
        'active' => 'bg-green-50 text-green-700 border-green-200',
        'paused' => 'bg-amber-50 text-amber-700 border-amber-200',
        'cancelled' => 'bg-red-50 text-red-600 border-red-200',
    ];
@endphp

{{-- Stats Row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-xs font-bold text-green-600">+{{ $stats['newThisWeek'] }}</span>
        </div>
        <p class="text-xs text-gray-400 mb-1">Total Customers</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-xs text-gray-400 mb-1">Active</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['active']) }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-xs text-gray-400 mb-1">Paused</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['paused'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
        </div>
        <p class="text-xs text-gray-400 mb-1">Cancelled</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['cancelled'] }}</p>
    </div>
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl border border-gray-100 p-4 mb-6 shadow-sm flex flex-wrap items-center gap-3">
    <div class="flex items-center bg-gray-50 rounded-lg px-3 py-2 border border-gray-100 flex-1 min-w-[200px]">
        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" placeholder="Search customers..." class="bg-transparent text-sm outline-none flex-1 text-gray-600 placeholder-gray-400">
    </div>
    <select class="text-sm border border-gray-100 rounded-lg px-3 py-2 bg-gray-50 text-gray-600 outline-none cursor-pointer">
        <option>All Status</option>
        <option>Active</option>
        <option>Paused</option>
        <option>Cancelled</option>
    </select>
    <select class="text-sm border border-gray-100 rounded-lg px-3 py-2 bg-gray-50 text-gray-600 outline-none cursor-pointer">
        <option>All Plans</option>
        <option>Weight Loss Pro</option>
        <option>Muscle Gain</option>
        <option>Maintenance</option>
        <option>Keto Premium</option>
    </select>
    <button class="ml-auto px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-lg shadow-sm hover:shadow-md transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Customer
    </button>
</div>

{{-- Customers Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-3 font-medium">Customer</th>
                    <th class="px-6 py-3 font-medium">Contact</th>
                    <th class="px-6 py-3 font-medium">Plan</th>
                    <th class="px-6 py-3 font-medium">Orders</th>
                    <th class="px-6 py-3 font-medium">Total Spent</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium">Joined</th>
                    <th class="px-6 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-6 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                {{ strtoupper(substr($customer['name'], 0, 1)) }}
                            </div>
                            <span class="text-xs font-semibold text-gray-900">{{ $customer['name'] }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-3.5">
                        <p class="text-xs text-gray-500">{{ $customer['email'] }}</p>
                        <p class="text-[10px] text-gray-400">{{ $customer['phone'] }}</p>
                    </td>
                    <td class="px-6 py-3.5 text-xs text-gray-600">{{ $customer['plan'] }}</td>
                    <td class="px-6 py-3.5">
                        <span class="text-xs font-bold text-gray-900">{{ $customer['orders'] }}</span>
                    </td>
                    <td class="px-6 py-3.5">
                        <span class="text-xs font-bold text-gray-900">SAR {{ number_format($customer['spent']) }}</span>
                    </td>
                    <td class="px-6 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold border {{ $statusColors[$customer['status']] }}">
                            {{ ucfirst($customer['status']) }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5 text-xs text-gray-400">{{ date('M d, Y', strtotime($customer['joined'])) }}</td>
                    <td class="px-6 py-3.5 text-right">
                        <button class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- Pagination --}}
    <div class="px-6 py-4 border-t border-gray-50 flex items-center justify-between">
        <p class="text-xs text-gray-400">Showing 1-8 of {{ number_format($stats['total']) }} customers</p>
        <div class="flex items-center gap-1">
            <button class="px-3 py-1.5 text-xs font-medium text-gray-400 rounded-lg hover:bg-gray-50 transition-colors">Previous</button>
            <button class="px-3 py-1.5 text-xs font-bold text-white bg-[#6E7A25] rounded-lg">1</button>
            <button class="px-3 py-1.5 text-xs font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">2</button>
            <button class="px-3 py-1.5 text-xs font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">3</button>
            <button class="px-3 py-1.5 text-xs font-medium text-gray-400 rounded-lg hover:bg-gray-50 transition-colors">Next</button>
        </div>
    </div>
</div>
@endsection

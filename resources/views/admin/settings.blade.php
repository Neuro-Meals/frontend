@extends('layouts.admin')

@section('title', 'Settings - Nutrio Meals')
@section('page_title', 'Settings')

@section('content')
<div class="max-w-4xl">
    {{-- Tabs --}}
    <div class="flex items-center gap-1 mb-6 bg-white rounded-2xl border border-gray-100 p-1.5 shadow-sm w-fit">
        <button class="px-4 py-2 text-sm font-bold text-white bg-[#259B00] rounded-xl transition-all">Company</button>
        <button class="px-4 py-2 text-sm font-medium text-gray-500 rounded-xl hover:bg-gray-50 transition-colors">Delivery</button>
        <button class="px-4 py-2 text-sm font-medium text-gray-500 rounded-xl hover:bg-gray-50 transition-colors">Payment</button>
        <button class="px-4 py-2 text-sm font-medium text-gray-500 rounded-xl hover:bg-gray-50 transition-colors">Notifications</button>
    </div>

    {{-- Company Settings --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#259B00] to-[#033133] flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900">Company Information</h3>
                <p class="text-xs text-gray-400 mt-0.5">Basic business details</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="text-xs font-medium text-gray-500 mb-1.5 block">Company Name</label>
                <input type="text" value="{{ $settings['company']['name'] }}" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#259B00] focus:bg-white transition-colors">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 mb-1.5 block">Email</label>
                <input type="email" value="{{ $settings['company']['email'] }}" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#259B00] focus:bg-white transition-colors">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 mb-1.5 block">Phone</label>
                <input type="text" value="{{ $settings['company']['phone'] }}" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#259B00] focus:bg-white transition-colors">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 mb-1.5 block">Currency</label>
                <select class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#259B00] focus:bg-white transition-colors cursor-pointer">
                    <option selected>{{ $settings['company']['currency'] }}</option>
                    <option>USD</option>
                    <option>EUR</option>
                    <option>AED</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="text-xs font-medium text-gray-500 mb-1.5 block">Address</label>
                <input type="text" value="{{ $settings['company']['address'] }}" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#259B00] focus:bg-white transition-colors">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 mb-1.5 block">Timezone</label>
                <select class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#259B00] focus:bg-white transition-colors cursor-pointer">
                    <option selected>{{ $settings['company']['timezone'] }}</option>
                    <option>Asia/Dubai</option>
                    <option>Europe/London</option>
                    <option>America/New_York</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Delivery Settings --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900">Delivery Configuration</h3>
                <p class="text-xs text-gray-400 mt-0.5">Delivery windows and thresholds</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="text-xs font-medium text-gray-500 mb-1.5 block">Order Cutoff Time</label>
                <input type="text" value="{{ $settings['delivery']['cutoff_time'] }}" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#259B00] focus:bg-white transition-colors">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 mb-1.5 block">Delivery Hours</label>
                <input type="text" value="{{ $settings['delivery']['delivery_hours'] }}" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#259B00] focus:bg-white transition-colors">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 mb-1.5 block">Minimum Order (SAR)</label>
                <input type="number" value="{{ $settings['delivery']['min_order'] }}" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#259B00] focus:bg-white transition-colors">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 mb-1.5 block">Free Delivery Threshold (SAR)</label>
                <input type="number" value="{{ $settings['delivery']['free_delivery_threshold'] }}" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#259B00] focus:bg-white transition-colors">
            </div>
        </div>
    </div>

    {{-- Payment Settings --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-400 to-purple-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900">Payment Methods</h3>
                <p class="text-xs text-gray-400 mt-0.5">Accepted payment options</p>
            </div>
        </div>
        <div class="space-y-3">
            @foreach($settings['payment']['methods'] as $method)
            <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-white flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">{{ $method }}</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-[#259B00] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                </label>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Save Button --}}
    <div class="flex items-center justify-end gap-3">
        <button class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors shadow-sm">Cancel</button>
        <button class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-[#033133] to-[#259B00] rounded-xl shadow-md hover:shadow-lg transition-all">Save Changes</button>
    </div>
</div>
@endsection

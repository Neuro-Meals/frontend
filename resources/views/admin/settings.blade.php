@extends('layouts.admin')

@section('title', __('Settings') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Settings'))

@section('content')
<div x-data="settingsApp()" x-init="init()" class="max-w-5xl">

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="animate__animated animate__fadeInUp bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-[#6E7A25]/20" style="animation-delay: 0.1s;">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
            <div class="absolute inset-0 opacity-[0.05]" style="background-image: repeating-linear-gradient(45deg, white 0px, white 1px, transparent 1px, transparent 12px);"></div>
            <div class="relative z-10">
                <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <p class="text-xs text-white/60 font-medium mb-1">{{ __('Total Roles') }}</p>
                <p class="text-2xl font-bold tracking-tight">{{ $systemInfo['roles_count'] }}</p>
                <p class="text-xs text-white/50 mt-1">{{ $systemInfo['permissions_count'] }} {{ __('permissions') }}</p>
            </div>
        </div>
        <div class="animate__animated animate__fadeInUp bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-blue-500/20" style="animation-delay: 0.15s;">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
            <div class="absolute inset-0 opacity-[0.05]" style="background-image: repeating-linear-gradient(45deg, white 0px, white 1px, transparent 1px, transparent 12px);"></div>
            <div class="relative z-10">
                <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <p class="text-xs text-white/60 font-medium mb-1">{{ __('Payments') }}</p>
                <p class="text-2xl font-bold tracking-tight">{{ $paymentStats['total'] }}</p>
                <p class="text-xs text-white/50 mt-1">{{ $paymentStats['paid'] }} {{ __('paid') }} · {{ $paymentStats['pending'] }} {{ __('pending') }}</p>
            </div>
        </div>
        <div class="animate__animated animate__fadeInUp bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-amber-500/20" style="animation-delay: 0.2s;">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
            <div class="absolute inset-0 opacity-[0.05]" style="background-image: repeating-linear-gradient(45deg, white 0px, white 1px, transparent 1px, transparent 12px);"></div>
            <div class="relative z-10">
                <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                </div>
                <p class="text-xs text-white/60 font-medium mb-1">{{ __('Drivers') }}</p>
                <p class="text-2xl font-bold tracking-tight">{{ $systemInfo['drivers_count'] }}</p>
                <p class="text-xs text-white/50 mt-1">{{ $systemInfo['active_drivers'] }} {{ __('active') }}</p>
            </div>
        </div>
        <div class="animate__animated animate__fadeInUp bg-gradient-to-br from-violet-500 to-purple-700 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-violet-500/20" style="animation-delay: 0.25s;">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
            <div class="absolute inset-0 opacity-[0.05]" style="background-image: repeating-linear-gradient(45deg, white 0px, white 1px, transparent 1px, transparent 12px);"></div>
            <div class="relative z-10">
                <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <p class="text-xs text-white/60 font-medium mb-1">{{ __('Plans') }}</p>
                <p class="text-2xl font-bold tracking-tight">{{ $systemInfo['plans_count'] }}</p>
                <p class="text-xs text-white/50 mt-1">{{ __('meal plans') }}</p>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex items-center gap-1 mb-6 bg-white rounded-2xl border border-gray-100 p-1.5 shadow-sm overflow-x-auto">
        <button @click="activeTab = 'company'" class="px-4 py-2.5 text-sm font-bold rounded-xl transition-all flex items-center gap-2 whitespace-nowrap" :class="activeTab === 'company' ? 'bg-gradient-to-r from-[#6E7A25] to-[#173327] text-white shadow-sm' : 'text-gray-500 hover:bg-gray-50'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            {{ __('Company') }}
        </button>
        <button @click="activeTab = 'delivery'" class="px-4 py-2.5 text-sm font-bold rounded-xl transition-all flex items-center gap-2 whitespace-nowrap" :class="activeTab === 'delivery' ? 'bg-gradient-to-r from-[#6E7A25] to-[#173327] text-white shadow-sm' : 'text-gray-500 hover:bg-gray-50'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
            {{ __('Delivery') }}
        </button>
        <button @click="activeTab = 'payment'" class="px-4 py-2.5 text-sm font-bold rounded-xl transition-all flex items-center gap-2 whitespace-nowrap" :class="activeTab === 'payment' ? 'bg-gradient-to-r from-[#6E7A25] to-[#173327] text-white shadow-sm' : 'text-gray-500 hover:bg-gray-50'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            {{ __('Payment') }}
        </button>
        <button @click="activeTab = 'notifications'" class="px-4 py-2.5 text-sm font-bold rounded-xl transition-all flex items-center gap-2 whitespace-nowrap" :class="activeTab === 'notifications' ? 'bg-gradient-to-r from-[#6E7A25] to-[#173327] text-white shadow-sm' : 'text-gray-500 hover:bg-gray-50'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            {{ __('Notifications') }}
        </button>
        <button @click="activeTab = 'security'" class="px-4 py-2.5 text-sm font-bold rounded-xl transition-all flex items-center gap-2 whitespace-nowrap" :class="activeTab === 'security' ? 'bg-gradient-to-r from-[#6E7A25] to-[#173327] text-white shadow-sm' : 'text-gray-500 hover:bg-gray-50'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ __('Security') }}
        </button>
        <button @click="activeTab = 'system'" class="px-4 py-2.5 text-sm font-bold rounded-xl transition-all flex items-center gap-2 whitespace-nowrap" :class="activeTab === 'system' ? 'bg-gradient-to-r from-[#6E7A25] to-[#173327] text-white shadow-sm' : 'text-gray-500 hover:bg-gray-50'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            {{ __('System') }}
        </button>
    </div>

    {{-- Company Tab --}}
    <div x-show="activeTab === 'company'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 bg-gradient-to-r from-[#173327]/5 to-transparent flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">{{ __('Company Information') }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ __('Basic business details and branding') }}</p>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Company Name') }}</label>
                    <input type="text" x-model="settings.company.name" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Email') }}</label>
                    <input type="email" x-model="settings.company.email" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Phone') }}</label>
                    <input type="text" x-model="settings.company.phone" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Website') }}</label>
                    <input type="text" x-model="settings.company.website" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Currency') }}</label>
                    <select x-model="settings.company.currency" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors cursor-pointer">
                        <option value="SAR">SAR - Saudi Riyal</option>
                        <option value="USD">USD - US Dollar</option>
                        <option value="EUR">EUR - Euro</option>
                        <option value="AED">AED - UAE Dirham</option>
                        <option value="KES">KES - Kenyan Shilling</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Timezone') }}</label>
                    <select x-model="settings.company.timezone" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors cursor-pointer">
                        <option value="Asia/Riyadh">Asia/Riyadh (UTC+3)</option>
                        <option value="Asia/Dubai">Asia/Dubai (UTC+4)</option>
                        <option value="Africa/Nairobi">Africa/Nairobi (UTC+3)</option>
                        <option value="Europe/London">Europe/London (UTC+0)</option>
                        <option value="America/New_York">America/New_York (UTC-5)</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Language') }}</label>
                    <select x-model="settings.company.language" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors cursor-pointer">
                        <option value="en">English</option>
                        <option value="ar">Arabic</option>
                        <option value="sw">Swahili</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Tax Rate') }} (%)</label>
                    <input type="number" x-model="settings.company.tax_rate" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Address') }}</label>
                    <input type="text" x-model="settings.company.address" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                </div>
            </div>
        </div>
    </div>

    {{-- Delivery Tab --}}
    <div x-show="activeTab === 'delivery'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 bg-gradient-to-r from-blue-500/5 to-transparent flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">{{ __('Delivery Configuration') }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ __('Delivery windows, fees, and zones') }}</p>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Order Cutoff Time') }}</label>
                    <input type="time" x-model="settings.delivery.cutoff_time" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Delivery Hours') }}</label>
                    <input type="text" x-model="settings.delivery.delivery_hours" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Minimum Order') }} (SAR)</label>
                    <input type="number" x-model="settings.delivery.min_order" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Free Delivery Threshold') }} (SAR)</label>
                    <input type="number" x-model="settings.delivery.free_delivery_threshold" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Delivery Fee') }} (SAR)</label>
                    <input type="number" x-model="settings.delivery.delivery_fee" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Max Delivery Distance') }} (km)</label>
                    <input type="number" x-model="settings.delivery.max_delivery_distance" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                </div>
                <div class="md:col-span-2 space-y-3 pt-2">
                    <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-white flex items-center justify-center shadow-sm">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">{{ __('Auto-assign Driver') }}</p>
                                <p class="text-[10px] text-gray-400">{{ __('Automatically assign nearest available driver') }}</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="settings.delivery.auto_assign_driver" class="sr-only peer">
                            <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-[#6E7A25] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                        </label>
                    </div>
                    <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-white flex items-center justify-center shadow-sm">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">{{ __('GPS Tracking') }}</p>
                                <p class="text-[10px] text-gray-400">{{ __('Real-time driver location tracking') }}</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="settings.delivery.gps_tracking" class="sr-only peer">
                            <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-[#6E7A25] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Tab --}}
    <div x-show="activeTab === 'payment'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 bg-gradient-to-r from-violet-500/5 to-transparent flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-400 to-purple-600 flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">{{ __('Payment Methods') }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ __('Accepted payment options and limits') }}</p>
                </div>
            </div>
            <div class="p-6 space-y-4">
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
                            <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-[#6E7A25] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                        </label>
                    </div>
                    @endforeach
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-2">
                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Refund Window') }} (days)</label>
                        <input type="number" x-model="settings.payment.refund_window" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Min Amount') }} (SAR)</label>
                        <input type="number" x-model="settings.payment.min_amount" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Max Amount') }} (SAR)</label>
                        <input type="number" x-model="settings.payment.max_amount" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                    </div>
                </div>
                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-white flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ __('Auto Capture Payments') }}</p>
                            <p class="text-[10px] text-gray-400">{{ __('Automatically capture payments after authorization') }}</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="settings.payment.auto_capture" class="sr-only peer">
                        <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-[#6E7A25] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                    </label>
                </div>
            </div>
        </div>
    </div>

    {{-- Notifications Tab --}}
    <div x-show="activeTab === 'notifications'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 bg-gradient-to-r from-amber-400/5 to-transparent flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">{{ __('Notification Channels') }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ __('Configure how notifications are sent') }}</p>
                </div>
            </div>
            <div class="p-6 space-y-3">
                @php
                    $notifChannels = [
                        ['key' => 'email_enabled', 'label' => __('Email'), 'desc' => __('Send notifications via email'), 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                        ['key' => 'sms_enabled', 'label' => __('SMS'), 'desc' => __('Send notifications via SMS'), 'icon' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z'],
                        ['key' => 'push_enabled', 'label' => __('Push'), 'desc' => __('Send push notifications'), 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5'],
                        ['key' => 'whatsapp_enabled', 'label' => __('WhatsApp'), 'desc' => __('Send notifications via WhatsApp'), 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
                    ];
                    $notifTypes = [
                        ['key' => 'order_updates', 'label' => __('Order Updates'), 'desc' => __('Notify on order status changes')],
                        ['key' => 'delivery_alerts', 'label' => __('Delivery Alerts'), 'desc' => __('Notify on delivery events')],
                        ['key' => 'payment_receipts', 'label' => __('Payment Receipts'), 'desc' => __('Send payment confirmations')],
                        ['key' => 'marketing_emails', 'label' => __('Marketing Emails'), 'desc' => __('Promotional and marketing content')],
                    ];
                @endphp
                @foreach($notifChannels as $channel)
                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-white flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $channel['icon'] }}"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ $channel['label'] }}</p>
                            <p class="text-[10px] text-gray-400">{{ $channel['desc'] }}</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="settings.notifications.{{ $channel['key'] }}" class="sr-only peer">
                        <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-[#6E7A25] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                    </label>
                </div>
                @endforeach
                <div class="pt-4 border-t border-gray-50">
                    <h4 class="text-xs font-bold text-gray-900 mb-3">{{ __('Notification Types') }}</h4>
                    @foreach($notifTypes as $type)
                    <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors mb-3">
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ $type['label'] }}</p>
                            <p class="text-[10px] text-gray-400">{{ $type['desc'] }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="settings.notifications.{{ $type['key'] }}" class="sr-only peer">
                            <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-[#6E7A25] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Security Tab --}}
    <div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 bg-gradient-to-r from-red-500/5 to-transparent flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">{{ __('Security Settings') }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ __('Authentication and access control') }}</p>
                </div>
            </div>
            <div class="p-6 space-y-5">
                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-white flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ __('Two-Factor Authentication') }}</p>
                            <p class="text-[10px] text-gray-400">{{ __('Require 2FA for admin accounts') }}</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="settings.security.two_factor" class="sr-only peer">
                        <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-[#6E7A25] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                    </label>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Session Timeout') }} (min)</label>
                        <input type="number" x-model="settings.security.session_timeout" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Password Expiry') }} (days)</label>
                        <input type="number" x-model="settings.security.password_expiry" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('Max Login Attempts') }}</label>
                        <input type="number" x-model="settings.security.max_login_attempts" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-500 mb-1.5 block">{{ __('IP Whitelist') }}</label>
                    <input type="text" x-model="settings.security.ip_whitelist" placeholder="{{ __('e.g. 192.168.1.1, 10.0.0.0/8') }}" class="w-full px-4 py-2.5 text-sm border border-gray-100 rounded-xl bg-gray-50 text-gray-700 outline-none focus:border-[#6E7A25] focus:bg-white transition-colors">
                    <p class="text-[10px] text-gray-400 mt-1">{{ __('Comma-separated list of allowed IPs. Leave empty to allow all.') }}</p>
                </div>
            </div>
        </div>

        {{-- Roles & Permissions --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 bg-gradient-to-r from-[#173327]/5 to-transparent flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">{{ __('Roles & Permissions') }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ __('Access control roles from API') }}</p>
                </div>
            </div>
            <div class="p-6">
                @if(!empty($roles))
                <div class="space-y-3">
                    @foreach($roles as $role)
                    <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-[#6E7A25]/20 hover:shadow-md transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                {{ strtoupper(substr($role['name'], 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $role['name'] }}</p>
                                <p class="text-[10px] text-gray-400">{{ $role['description'] ?: 'No description' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#6E7A25]/10 text-[#6E7A25] border border-[#6E7A25]/20">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $role['permissions_count'] }} {{ __('perms') }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $role['users_count'] }} {{ __('users') }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <p class="text-sm text-gray-400">{{ __('No roles found. API may not be connected.') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- System Tab --}}
    <div x-show="activeTab === 'system'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 bg-gradient-to-r from-teal-500/5 to-transparent flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">{{ __('System Information') }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ __('Application and server details') }}</p>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $systemItems = [
                        ['label' => __('PHP Version'), 'value' => $systemInfo['php_version'], 'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'],
                        ['label' => __('Laravel Version'), 'value' => $systemInfo['laravel_version'], 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                        ['label' => __('Server Time'), 'value' => $systemInfo['server_time'], 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['label' => __('Timezone'), 'value' => $systemInfo['timezone'], 'icon' => 'M3.6 9h16.8M3.6 15h16.8M11 3v18M13 3v18'],
                        ['label' => __('Environment'), 'value' => strtoupper($systemInfo['environment']), 'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9'],
                        ['label' => __('Debug Mode'), 'value' => $systemInfo['debug_mode'] ? __('Enabled') : __('Disabled'), 'icon' => 'M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ];
                @endphp
                @foreach($systemItems as $item)
                <div class="flex items-center gap-3 p-4 rounded-xl bg-gray-50">
                    <div class="w-9 h-9 rounded-lg bg-white flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-gray-400">{{ $item['label'] }}</p>
                        <p class="text-sm font-bold text-gray-900 truncate">{{ $item['value'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="px-6 pb-6">
                <div class="p-4 rounded-xl border border-gray-100 bg-gradient-to-r from-[#173327]/5 to-transparent">
                    <h4 class="text-xs font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        {{ __('Quick Stats') }}
                    </h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="text-center p-3 rounded-lg bg-white border border-gray-100">
                            <p class="text-lg font-bold text-[#6E7A25]">{{ $systemInfo['plans_count'] }}</p>
                            <p class="text-[10px] text-gray-400">{{ __('Plans') }}</p>
                        </div>
                        <div class="text-center p-3 rounded-lg bg-white border border-gray-100">
                            <p class="text-lg font-bold text-blue-600">{{ $systemInfo['drivers_count'] }}</p>
                            <p class="text-[10px] text-gray-400">{{ __('Drivers') }}</p>
                        </div>
                        <div class="text-center p-3 rounded-lg bg-white border border-gray-100">
                            <p class="text-lg font-bold text-amber-600">{{ $systemInfo['roles_count'] }}</p>
                            <p class="text-[10px] text-gray-400">{{ __('Roles') }}</p>
                        </div>
                        <div class="text-center p-3 rounded-lg bg-white border border-gray-100">
                            <p class="text-lg font-bold text-violet-600">{{ $systemInfo['permissions_count'] }}</p>
                            <p class="text-[10px] text-gray-400">{{ __('Permissions') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Save Button --}}
    <div class="flex items-center justify-end gap-3 mt-6">
        <button @click="resetSettings()" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            {{ __('Reset') }}
        </button>
        <button @click="saveSettings()" :disabled="saving" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2 disabled:opacity-50">
            <svg x-show="!saving" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <svg x-show="saving" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            <span x-text="saving ? '{{ __('Saving...') }}' : '{{ __('Save Changes') }}'"></span>
        </button>
    </div>

    {{-- Toast --}}
    <div x-show="toast" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed bottom-6 right-6 z-50">
        <div class="flex items-center gap-3 px-5 py-3 rounded-xl shadow-lg" :class="toastType === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'">
            <svg x-show="toastType === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <svg x-show="toastType === 'error'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium" x-text="toastMessage"></span>
        </div>
    </div>
</div>

@push('scripts')
<script>
function settingsApp() {
    return {
        activeTab: 'company',
        saving: false,
        toast: false,
        toastType: 'success',
        toastMessage: '',
        originalSettings: null,
        settings: @json($settings),

        init() {
            this.originalSettings = JSON.parse(JSON.stringify(this.settings));
        },

        async saveSettings() {
            this.saving = true;
            try {
                const response = await fetch('{{ route("admin.settings") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ settings: this.settings })
                });
                if (response.ok) {
                    this.showToast('success', '{{ __("Settings saved successfully!") }}');
                    this.originalSettings = JSON.parse(JSON.stringify(this.settings));
                } else {
                    this.showToast('error', '{{ __("Failed to save settings.") }}');
                }
            } catch (e) {
                this.showToast('error', '{{ __("Network error. Please try again.") }}');
            } finally {
                this.saving = false;
            }
        },

        resetSettings() {
            if (this.originalSettings) {
                this.settings = JSON.parse(JSON.stringify(this.originalSettings));
                this.showToast('success', '{{ __("Settings reset to original values.") }}');
            }
        },

        showToast(type, message) {
            this.toastType = type;
            this.toastMessage = message;
            this.toast = true;
            setTimeout(() => { this.toast = false; }, 3000);
        }
    };
}
</script>
@endpush
@endsection

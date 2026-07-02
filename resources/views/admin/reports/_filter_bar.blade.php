{{-- Report Filter Bar --}}
@php $reportName = $reportName ?? 'Report'; @endphp
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-6 no-print">
    {{-- Report Type Tabs --}}
    <div class="flex items-center gap-1 mb-4 pb-3 border-b border-gray-100 overflow-x-auto">
        <a href="{{ route('admin.reports.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ request()->routeIs('admin.reports.dashboard') ? 'bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white shadow-md shadow-[#6E7A25]/20' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
            {{ __('Report Overview') }}
        </a>
        <a href="{{ route('admin.reports.revenue') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ request()->routeIs('admin.reports.revenue') ? 'bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white shadow-md shadow-[#6E7A25]/20' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ __('Revenue & Finance') }}
        </a>
        <a href="{{ route('admin.reports.delivery') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ request()->routeIs('admin.reports.delivery') ? 'bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white shadow-md shadow-[#6E7A25]/20' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"/></svg>
            {{ __('Delivery Operations') }}
        </a>
        <a href="{{ route('admin.reports.subscriptions') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ request()->routeIs('admin.reports.subscriptions') ? 'bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white shadow-md shadow-[#6E7A25]/20' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            {{ __('Subscription & Retention') }}
        </a>
        <a href="{{ route('admin.reports.notifications') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ request()->routeIs('admin.reports.notifications') ? 'bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white shadow-md shadow-[#6E7A25]/20' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            {{ __('Notifications & Campaign') }}
        </a>
        <a href="{{ route('admin.reports.audit') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ request()->routeIs('admin.reports.audit') ? 'bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white shadow-md shadow-[#6E7A25]/20' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            {{ __('Audit & Compliance') }}
        </a>
    </div>
    <div class="flex flex-col lg:flex-row items-start lg:items-center gap-3 lg:gap-4">
        {{-- Date Range --}}
        <div class="flex items-center gap-2">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">Date Range</label>
            <select class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 bg-gray-50 focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all">
                <option>Today</option>
                <option>Yesterday</option>
                <option selected>Last 7 days</option>
                <option>Last 30 days</option>
                <option>This Month</option>
                <option>Last 3 Months</option>
                <option>Custom Range</option>
            </select>
        </div>

        {{-- Granularity --}}
        <div class="flex items-center gap-2">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">Granularity</label>
            <div class="inline-flex rounded-lg overflow-hidden border border-gray-200">
                <button class="px-3 py-1.5 text-xs font-medium text-gray-500 hover:bg-gray-50 transition-colors">Day</button>
                <button class="px-3 py-1.5 text-xs font-medium text-white bg-[#6E7A25] transition-colors">Week</button>
                <button class="px-3 py-1.5 text-xs font-medium text-gray-500 hover:bg-gray-50 transition-colors">Month</button>
            </div>
        </div>

        {{-- Zone --}}
        <div class="flex items-center gap-2">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">Zone</label>
            <select class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 bg-gray-50 focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all">
                <option>All Zones</option>
                <option>Riyadh Central</option>
                <option>Riyadh North</option>
                <option>Riyadh South</option>
                <option>Jeddah</option>
            </select>
        </div>

        {{-- Segment --}}
        <div class="flex items-center gap-2">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">Segment</label>
            <select class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 bg-gray-50 focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all">
                <option>All Segments</option>
                <option>Individual</option>
                <option>Corporate</option>
                <option>Trial</option>
            </select>
        </div>

        {{-- Plan Type --}}
        <div class="flex items-center gap-2">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">Plan</label>
            <select class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 bg-gray-50 focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all">
                <option>All Plans</option>
                <option>Weight Loss Pro</option>
                <option>Muscle Gain</option>
                <option>Maintenance</option>
                <option>Keto Premium</option>
            </select>
        </div>

        <div class="flex-1"></div>

        {{-- Export Controls --}}
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-gray-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print
            </button>
            <button class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-[#6E7A25] rounded-lg hover:bg-[#1e7a00] transition-all shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13v6m-3-3h6"/></svg>
                Export PDF
            </button>
            <button class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-[#173327] rounded-lg hover:bg-[#122620] transition-all shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export Excel
            </button>
        </div>
    </div>
    {{-- Meta row --}}
    <div class="flex items-center gap-4 mt-3 pt-3 border-t border-gray-50">
        <span class="text-[10px] text-gray-400">Report: <span class="font-semibold text-gray-600">{{ $reportName }}</span></span>
        <span class="text-[10px] text-gray-400">Timezone: <span class="font-semibold text-gray-600">{{ $timezone ?? 'UTC' }}</span></span>
        <span class="text-[10px] text-gray-400">Last Updated: <span class="font-semibold text-gray-600">{{ $lastUpdated ?? '—' }}</span></span>
        <div class="flex-1"></div>
        <span class="text-[10px] text-gray-400">Trace ID: <span class="font-mono font-semibold text-gray-600">trc_{{ substr(md5($reportName), 0, 12) }}</span></span>
    </div>
</div>

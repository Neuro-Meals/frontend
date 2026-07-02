{{-- Report Filter Bar --}}
@php $reportName = $reportName ?? 'Report'; @endphp
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-6 no-print">
    <div class="flex flex-col lg:flex-row items-start lg:items-center gap-3 lg:gap-4">
        {{-- Date Range --}}
        <div class="flex items-center gap-2">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">Date Range</label>
            <select class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 bg-gray-50 focus:ring-2 focus:ring-[#259B00]/20 focus:border-[#259B00] outline-none transition-all">
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
                <button class="px-3 py-1.5 text-xs font-medium text-white bg-[#259B00] transition-colors">Week</button>
                <button class="px-3 py-1.5 text-xs font-medium text-gray-500 hover:bg-gray-50 transition-colors">Month</button>
            </div>
        </div>

        {{-- Zone --}}
        <div class="flex items-center gap-2">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">Zone</label>
            <select class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 bg-gray-50 focus:ring-2 focus:ring-[#259B00]/20 focus:border-[#259B00] outline-none transition-all">
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
            <select class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 bg-gray-50 focus:ring-2 focus:ring-[#259B00]/20 focus:border-[#259B00] outline-none transition-all">
                <option>All Segments</option>
                <option>Individual</option>
                <option>Corporate</option>
                <option>Trial</option>
            </select>
        </div>

        {{-- Plan Type --}}
        <div class="flex items-center gap-2">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">Plan</label>
            <select class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 bg-gray-50 focus:ring-2 focus:ring-[#259B00]/20 focus:border-[#259B00] outline-none transition-all">
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
            <button class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-[#259B00] rounded-lg hover:bg-[#1e7a00] transition-all shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13v6m-3-3h6"/></svg>
                Export PDF
            </button>
            <button class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-[#033133] rounded-lg hover:bg-[#01241f] transition-all shadow-sm">
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

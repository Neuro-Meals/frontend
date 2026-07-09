@extends('layouts.admin')

@section('title', __('Revenue & Finance') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Revenue & Finance'))

@section('content')
@php $reportName = __('Revenue & Finance'); @endphp
@include('admin.reports._filter_bar')

<div class="hidden print:block mb-6">
    <h1 class="text-2xl font-bold text-gray-900">{{ __('Nutrio Meals') }} - {{ __('Revenue & Finance') }} {{ __('Report') }}</h1>
    <p class="text-sm text-gray-500">{{ __('Generated') }}: {{ $lastUpdated }} | {{ __('Timezone') }}: {{ $timezone }}</p>
</div>

{{-- KPI Row --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 mb-6">
    @foreach($kpis as $kpi)
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm kpi-card relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 rounded-full blur-2xl opacity-10" style="background: {{ $kpi['color'] }}"></div>
        <div class="flex items-center justify-between relative z-10">
            <span class="text-[10px] font-medium text-gray-400">{{ $kpi['label'] }}</span>
            @if($kpi['trend'] === 'up')
            <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
            @else
            <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
            @endif
        </div>
        <div class="text-lg font-bold text-gray-900 mt-2 relative z-10">{{ $kpi['value'] }}</div>
        <span class="text-[10px] font-semibold mt-1 block relative z-10 {{ $kpi['trend'] === 'up' ? 'text-green-600' : 'text-red-500' }}">{{ $kpi['delta'] }}</span>
    </div>
    @endforeach
</div>

{{-- Revenue Trend + Payment Trends --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    {{-- Revenue Trend with Comparison --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Revenue') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Trend') }}</span></h3>
                <span class="text-[10px] text-gray-400">SAR | {{ __('Current vs Previous Period') }}</span>
            </div>
            <div class="flex items-center gap-3 text-[10px]">
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-[#6E7A25]"></span> {{ __('Current') }}</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-gray-300"></span> {{ __('Previous') }}</span>
            </div>
        </div>
        <div class="relative h-56">
            <canvas id="revenueTrendChart"></canvas>
        </div>
    </div>

    {{-- Payment Success/Failure Trends --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Payment') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Success & Failure') }}</span></h3>
                <span class="text-[10px] text-gray-400">{{ __('Percentage') }} | {{ __('Monthly Trend') }}</span>
            </div>
            <div class="flex items-center gap-3 text-[10px]">
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-[#6E7A25]"></span> {{ __('Success') }}</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-red-400"></span> {{ __('Failure') }}</span>
            </div>
        </div>
        <div class="relative h-56">
            <canvas id="paymentTrendChart"></canvas>
        </div>
    </div>
</div>

{{-- Refund Volume + Revenue by Plan --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    {{-- Refund Volume --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Refund') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Volume') }}</span></h3>
                <span class="text-[10px] text-gray-400">SAR | {{ __('Monthly') }} | {{ __('Refund Ratio') }}: 1.4%</span>
            </div>
        </div>
        <div class="relative h-56">
            <canvas id="refundVolumeChart"></canvas>
        </div>
    </div>

    {{-- Revenue by Plan - Donut style --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('Revenue by') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Plan') }}</span></h3>
        <span class="text-[10px] text-gray-400 block mb-4">{{ __('Distribution') }} | SAR</span>
        <div class="space-y-3">
            @php $totalRev = array_sum(array_column($revenueByPlan, 'revenue')); @endphp
            @foreach($revenueByPlan as $plan)
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-sm" style="background: {{ $plan['color'] }}"></span>
                        <span class="text-xs font-semibold text-gray-700">{{ $plan['plan'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-gray-900">SAR {{ number_format($plan['revenue']) }}</span>
                        <span class="text-[10px] text-gray-400">{{ $plan['pct'] }}%</span>
                    </div>
                </div>
                <div class="h-5 bg-gray-50 rounded-lg overflow-hidden">
                    <div class="h-full rounded-lg transition-all duration-500" style="width: {{ $plan['pct'] }}%; background: linear-gradient(90deg, {{ $plan['color'] }}, {{ $plan['color'] }}cc)"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Payment Methods Table --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50">
        <h3 class="text-sm font-bold text-gray-900">{{ __('Payment') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Methods Breakdown') }}</span></h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b border-gray-50">
                    <th class="px-5 py-3 font-medium">{{ __('Method') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Transactions') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Volume') }} (SAR)</th>
                    <th class="px-5 py-3 font-medium">{{ __('Share') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Success Rate') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paymentMethods as $pm)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">{{ $pm['method'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ number_format($pm['count']) }}</td>
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">{{ number_format($pm['volume']) }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-[#6E7A25] rounded-full" style="width: {{ $pm['pct'] }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $pm['pct'] }}%</span>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $pm['successRate'] >= 99 ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">{{ $pm['successRate'] }}%</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    Chart.defaults.font.family = "'Nunito', sans-serif";
    Chart.defaults.color = '#9ca3af';

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#173327',
                titleColor: '#fff',
                bodyColor: '#fff',
                padding: 10,
                cornerRadius: 8,
                displayColors: true,
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { font: { size: 10 } }
            },
            y: {
                beginAtZero: true,
                grid: { color: '#f3f4f6', borderDash: [4, 4] },
                ticks: { font: { size: 10 } }
            }
        }
    };

    // Revenue Trend - smooth area/line chart
    new Chart(document.getElementById('revenueTrendChart'), {
        type: 'line',
        data: {
            labels: @json($revenueTrend['labels'] ?? []),
            datasets: [
                {
                    label: '{{ __('Current') }}',
                    data: @json($revenueTrend['current'] ?? []),
                    borderColor: '#6E7A25',
                    backgroundColor: 'rgba(110, 122, 37, 0.12)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#6E7A25',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: '{{ __('Previous') }}',
                    data: @json($revenueTrend['previous'] ?? []),
                    borderColor: '#d1d5db',
                    backgroundColor: 'transparent',
                    tension: 0.4,
                    borderDash: [5, 5],
                    pointBackgroundColor: '#d1d5db',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }
            ]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                tooltip: {
                    ...commonOptions.plugins.tooltip,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': SAR ' + Number(context.raw).toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Payment Success/Failure - stacked bar
    new Chart(document.getElementById('paymentTrendChart'), {
        type: 'bar',
        data: {
            labels: @json($paymentTrends['labels'] ?? []),
            datasets: [
                {
                    label: '{{ __('Success') }}',
                    data: @json($paymentTrends['success'] ?? []),
                    backgroundColor: '#6E7A25',
                    borderRadius: 4,
                },
                {
                    label: '{{ __('Failure') }}',
                    data: @json($paymentTrends['failure'] ?? []),
                    backgroundColor: '#f87171',
                    borderRadius: 4,
                }
            ]
        },
        options: {
            ...commonOptions,
            scales: {
                ...commonOptions.scales,
                y: { ...commonOptions.scales.y, max: 100, ticks: { callback: v => v + '%', font: { size: 10 } } }
            },
            plugins: {
                ...commonOptions.plugins,
                tooltip: {
                    ...commonOptions.plugins.tooltip,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.raw + '%';
                        }
                    }
                }
            }
        }
    });

    // Refund Volume - bar chart
    new Chart(document.getElementById('refundVolumeChart'), {
        type: 'bar',
        data: {
            labels: @json($refundVolume['labels'] ?? []),
            datasets: [{
                label: '{{ __('Refund Amount') }}',
                data: @json($refundVolume['amount'] ?? []),
                backgroundColor: 'rgba(239, 68, 68, 0.85)',
                hoverBackgroundColor: '#ef4444',
                borderRadius: 6,
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                tooltip: {
                    ...commonOptions.plugins.tooltip,
                    callbacks: {
                        label: function(context) {
                            return 'SAR ' + Number(context.raw).toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endpush

@endsection

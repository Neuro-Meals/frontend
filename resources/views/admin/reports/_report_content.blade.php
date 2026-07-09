@php
    $revArrays = array_merge($revenueTrend['current'] ?? [], $revenueTrend['previous'] ?? []);
    $revMax = !empty($revArrays) ? max($revArrays) : 500000;
@endphp

{{-- KPI Row --}}
<div class="mb-6 animate__animated animate__fadeIn">
    <h3 class="text-sm font-bold text-gray-900 mb-3">{{ __('Executive') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('KPIs') }}</span></h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
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
            <div class="text-xl font-bold text-gray-900 mt-2 relative z-10">{{ $kpi['value'] }}</div>
            <span class="text-[10px] font-semibold mt-1 block relative z-10 {{ $kpi['trend'] === 'up' ? 'text-green-600' : 'text-red-500' }}">{{ $kpi['delta'] }} {{ __('vs prev period') }}</span>
        </div>
        @endforeach
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    {{-- Revenue Trend --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 p-5 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Revenue') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Trend') }}</span></h3>
                <span class="text-[10px] text-gray-400">SAR | {{ __('Last 6 months') }} | {{ __('Current vs Previous') }}</span>
            </div>
            <div class="flex items-center gap-3 text-[10px]">
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-[#6E7A25]"></span> {{ __('Current') }}</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-gray-300"></span> {{ __('Previous') }}</span>
            </div>
        </div>
        <div class="relative h-56">
            <canvas id="reportRevenueChart"></canvas>
        </div>
    </div>

    {{-- Subscription Funnel --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
        <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('Subscription') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Funnel') }}</span></h3>
        <span class="text-[10px] text-gray-400 block mb-4">{{ __('Visit → Trial → Subscribe → Renew') }}</span>
        <div class="relative h-52">
            <canvas id="reportFunnelChart"></canvas>
        </div>
    </div>
</div>

{{-- Delivery SLA + Operational Metrics --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    {{-- Delivery SLA Bar Chart --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 p-5 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Delivery') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('SLA by Zone') }}</span></h3>
                <span class="text-[10px] text-gray-400">{{ __('On-time %') }} | {{ __('Target') }}: 92%</span>
            </div>
        </div>
        <div class="relative h-56">
            <canvas id="reportSlaChart"></canvas>
        </div>
    </div>

    {{-- Operational Metrics --}}
    <div class="bg-gradient-to-br from-[#173327] to-[#122620] rounded-xl p-5 text-white shadow-lg relative overflow-hidden animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
        <div class="absolute top-0 right-0 w-24 h-24 bg-[#6E7A25]/10 rounded-full -mr-12 -mt-12 blur-2xl"></div>
        <h3 class="text-sm font-bold mb-4 relative z-10">{{ __('Operational') }} <span class="text-[#6E7A25]">{{ __('Metrics') }}</span></h3>
        <div class="space-y-3 relative z-10">
            @foreach($operationalMetrics as $metric)
            <div class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
                <span class="text-xs text-white/60">{{ $metric['label'] }}</span>
                <span class="text-sm font-bold" style="color: {{ $metric['color'] }}">{{ $metric['value'] }}</span>
            </div>
            @endforeach
            @if(empty($operationalMetrics))
            <div class="text-xs text-white/40 text-center py-8">{{ __('No operational metrics available.') }}</div>
            @endif
        </div>
    </div>
</div>

{{-- Exceptions Table --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
        <h3 class="text-sm font-bold text-gray-900">{{ __('Exceptions') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Log') }}</span></h3>
        <span class="text-[10px] text-gray-400">{{ count($exceptions) }} {{ __('active exceptions') }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b border-gray-50">
                    <th class="px-5 py-3 font-medium">{{ __('ID') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Type') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Zone') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Detail') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Severity') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Time') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exceptions as $exc)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">{{ $exc['id'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-600">{{ $exc['type'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $exc['zone'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $exc['detail'] }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $exc['severity'] === 'critical' ? 'bg-red-50 text-red-700' : ($exc['severity'] === 'warning' ? 'bg-amber-50 text-amber-700' : 'bg-blue-50 text-blue-700') }}">{{ __(ucfirst($exc['severity'])) }}</span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400">{{ $exc['time'] }}</td>
                </tr>
                @endforeach
                @if(empty($exceptions))
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-xs text-gray-400">{{ __('No exceptions logged for the selected period.') }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<script>
    (function() {
        const palette = ['#6E7A25', '#3b82f6', '#949B50', '#173327', '#f59e0b', '#ef4444'];

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
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { font: { size: 10 } } }
            }
        };

        new Chart(document.getElementById('reportRevenueChart'), {
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
                            label: (ctx) => ctx.dataset.label + ': SAR ' + Number(ctx.raw).toLocaleString()
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('reportFunnelChart'), {
            type: 'bar',
            data: {
                labels: @json(array_column($subscriptionFunnel, 'stage')),
                datasets: [{
                    label: '{{ __('Subscribers') }}',
                    data: @json(array_column($subscriptionFunnel, 'count')),
                    backgroundColor: @json(array_column($subscriptionFunnel, 'color')) ?: palette,
                    borderRadius: 6,
                }]
            },
            options: {
                ...commonOptions,
                indexAxis: 'y',
                plugins: {
                    ...commonOptions.plugins,
                    tooltip: {
                        ...commonOptions.plugins.tooltip,
                        callbacks: {
                            label: (ctx) => ctx.raw + ' {{ __('subscribers') }}'
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('reportSlaChart'), {
            type: 'bar',
            data: {
                labels: @json(array_column($deliverySla, 'zone')),
                datasets: [{
                    label: '{{ __('On-time %') }}',
                    data: @json(array_column($deliverySla, 'onTime')),
                    backgroundColor: (ctx) => ctx.raw >= 92 ? '#6E7A25' : '#f59e0b',
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
                            label: (ctx) => ctx.raw + '% {{ __('on-time') }}'
                        }
                    }
                },
                scales: {
                    ...commonOptions.scales,
                    y: { ...commonOptions.scales.y, max: 100, ticks: { callback: v => v + '%', font: { size: 10 } } }
                }
            }
        });
    })();
</script>

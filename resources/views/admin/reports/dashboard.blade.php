@extends('layouts.admin')

@section('title', __('Report Overview') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Report Overview'))

@section('content')
@php $reportName = __('Report Overview'); @endphp

<div x-data="reportDashboard()" @report-filtered.window="reload($event.detail)">
    @include('admin.reports._filter_bar', ['ajaxMode' => true])

    {{-- Print Header --}}
    <div class="hidden print:block mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Nutrio Meals') }} - {{ __('Report Overview') }} {{ __('Report') }}</h1>
        <p class="text-sm text-gray-500">{{ __('Generated') }}: <span x-text="lastUpdated">{{ $lastUpdated }}</span> | {{ __('Timezone') }}: {{ $timezone }}</p>
    </div>

    {{-- AJAX Loading State --}}
    <div x-show="loading" x-cloak class="mb-6 rounded-2xl border border-gray-100 bg-white p-8 flex flex-col items-center justify-center shadow-sm">
        <div class="relative w-12 h-12 mb-3">
            <div class="absolute inset-0 rounded-full border-4 border-gray-100"></div>
            <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-[#6E7A25] animate-spin"></div>
        </div>
        <p class="text-xs font-bold text-gray-400">{{ __('Updating report...') }}</p>
    </div>

    <div id="reportContentWrapper" x-show="!loading" class="transition-opacity duration-300" :class="loading ? 'opacity-50' : 'opacity-100'">
        @include('admin.reports._report_content')
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    function reportFilterBar() {
        return {
            filters: {
                range: '{{ $range ?? '7d' }}',
                granularity: '{{ request('granularity', 'week') }}',
                zone: '{{ $zone ?? 'all' }}',
                segment: '{{ request('segment', 'all') }}',
                plan: '{{ request('plan', 'all') }}',
            },
            applyFilters() {
                window.dispatchEvent(new CustomEvent('report-filtered', { detail: this.filters }));
            },
            setGranularity(value) {
                this.filters.granularity = value;
                const buttons = this.$el.closest('form').querySelectorAll('.granularity-btn');
                buttons.forEach(btn => {
                    if (btn.value === value) {
                        btn.classList.remove('text-gray-500', 'hover:bg-gray-50');
                        btn.classList.add('text-white', 'bg-[#6E7A25]');
                    } else {
                        btn.classList.add('text-gray-500', 'hover:bg-gray-50');
                        btn.classList.remove('text-white', 'bg-[#6E7A25]');
                    }
                });
                this.applyFilters();
            }
        };
    }

    function initReportCharts() {
        const data = window.reportChartData;
        if (!data) return;

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
                labels: data.revenue.labels,
                datasets: [
                    {
                        label: '{{ __('Current') }}',
                        data: data.revenue.current,
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
                        data: data.revenue.previous,
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
                labels: data.funnel.labels,
                datasets: [{
                    label: '{{ __('Subscribers') }}',
                    data: data.funnel.counts,
                    backgroundColor: data.funnel.colors,
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
                labels: data.sla.labels,
                datasets: [{
                    label: '{{ __('On-time %') }}',
                    data: data.sla.values,
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
    }

    function reportDashboard() {
        return {
            loading: false,
            lastUpdated: '{{ $lastUpdated }}',
            async reload(filters) {
                this.loading = true;
                const params = new URLSearchParams(filters || {});
                try {
                    const res = await fetch('{{ route('admin.reports.dashboard') }}?' + params.toString(), {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await res.json();
                    if (data.html) {
                        const wrapper = document.getElementById('reportContentWrapper');
                        wrapper.innerHTML = data.html;
                        this.lastUpdated = data.lastUpdated;
                        wrapper.querySelectorAll('script').forEach(s => {
                            const newScript = document.createElement('script');
                            newScript.textContent = s.textContent;
                            document.body.appendChild(newScript);
                            newScript.remove();
                        });
                        initReportCharts();
                    }
                } catch (e) {
                    console.error('Report reload failed', e);
                } finally {
                    this.loading = false;
                }
            }
        };
    }

    document.addEventListener('DOMContentLoaded', initReportCharts);
</script>
@endpush

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

    <div id="reportContentWrapper" x-html="content" x-show="!loading" class="transition-opacity duration-300" :class="loading ? 'opacity-50' : 'opacity-100'">

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

    function reportDashboard() {
        return {
            loading: false,
            lastUpdated: '{{ $lastUpdated }}',
            content: '',
            init() {
                this.content = document.getElementById('reportContentWrapper').innerHTML;
            },
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
                        this.content = data.html;
                        this.lastUpdated = data.lastUpdated;
                        this.$nextTick(() => {
                            const wrapper = document.getElementById('reportContentWrapper');
                            const scripts = wrapper.querySelectorAll('script');
                            scripts.forEach(s => {
                                const newScript = document.createElement('script');
                                newScript.textContent = s.textContent;
                                document.body.appendChild(newScript);
                                newScript.remove();
                            });
                        });
                    }
                } catch (e) {
                    console.error('Report reload failed', e);
                } finally {
                    this.loading = false;
                }
            }
        };
    }
</script>
@endpush

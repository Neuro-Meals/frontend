@extends('layouts.driver')

@section('title', __('Directions') . ' - ' . __('Nutrio Meals'))

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #directionsMap { height: calc(100vh - 4rem); width: 100%; }
    .leaflet-container { background: #eef2ef; font-family: 'Nunito', sans-serif; }
    .leaflet-control-attribution { font-size: 9px !important; }

    @keyframes pulseMarker {
        0% { transform: scale(0.6); opacity: 0.9; }
        70% { transform: scale(2.4); opacity: 0; }
        100% { transform: scale(0.6); opacity: 0; }
    }
    .driver-marker-wrap { position: relative; width: 20px; height: 20px; }
    .driver-marker-pulse {
        position: absolute; inset: -8px; border-radius: 9999px;
        background: rgba(23, 51, 39, 0.55);
        animation: pulseMarker 1.8s ease-out infinite;
    }
    .driver-marker-dot {
        position: relative; width: 20px; height: 20px; border-radius: 9999px;
        background: linear-gradient(135deg, #173327, #6E7A25);
        border: 3px solid white; box-shadow: 0 3px 8px rgba(0,0,0,0.35);
    }

    .dest-marker-wrap { position: relative; width: 30px; height: 40px; }
    @keyframes destBounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }
    .dest-marker-pin {
        animation: destBounce 1.6s ease-in-out infinite;
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.35));
    }

    .map-fade-in { animation: mapFadeIn 0.4s ease-out both; }
    @keyframes mapFadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endpush

@section('content')
<div class="relative" x-data="directionsMap()" x-init="init()">
    <div id="directionsMap"></div>

    {{-- Top overlay --}}
    <div class="absolute top-0 left-0 right-0 p-4 z-[500] bg-gradient-to-b from-black/40 via-black/10 to-transparent">
        <div class="flex items-center gap-3 map-fade-in">
            <a href="{{ route('driver.deliveries') }}" class="w-10 h-10 rounded-full bg-white shadow-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="flex-1 bg-white/95 backdrop-blur rounded-2xl px-4 py-2.5 shadow-lg min-w-0">
                <p class="text-xs font-bold text-gray-900">{{ $delivery['order_number'] }}</p>
                <p class="text-[10px] text-gray-500 truncate">{{ $delivery['address'] ?: __('No address provided') }}</p>
            </div>
        </div>
    </div>

    {{-- Recenter button --}}
    <button @click="recenter()" class="absolute right-4 z-[500] w-11 h-11 rounded-full bg-white shadow-lg flex items-center justify-center map-fade-in" style="bottom: 168px;">
        <svg class="w-5 h-5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </button>

    {{-- Live tracking pill --}}
    <div class="absolute left-4 z-[500] flex items-center gap-1.5 bg-white/95 backdrop-blur px-3 py-1.5 rounded-full shadow-lg map-fade-in" style="bottom: 168px;">
        <span class="relative flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
        </span>
        <span class="text-[10px] font-bold text-gray-700" x-text="statusText">{{ __('Live tracking') }}</span>
    </div>

    {{-- Bottom info card --}}
    <div class="absolute bottom-0 left-0 right-0 z-[500] p-4">
        <div class="bg-white rounded-2xl shadow-2xl p-4 border border-gray-100 map-fade-in">
            <div class="flex items-center justify-between mb-3">
                <div class="min-w-0">
                    <p class="text-sm font-bold text-gray-900 truncate">{{ $delivery['customer'] }}</p>
                    <p class="text-[10px] text-gray-400 truncate">{{ $delivery['customer_phone'] ?: '' }}</p>
                    <p class="text-[10px] text-brand-600 mt-0.5" x-text="etaText">{{ __('Calculating route...') }}</p>
                    <p class="text-[10px] text-gray-400" x-text="distanceText"></p>
                </div>
                <div class="w-11 h-11 rounded-full bg-brand-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17h8m-8 0a2 2 0 11-4 0 2 2 0 014 0zm8 0a2 2 0 104 0 2 2 0 00-4 0zM3 11l1.5-4.5A2 2 0 016.4 5h7.2a2 2 0 011.9 1.5L17 11m-14 0h18m-18 0v4a1 1 0 001 1h1m16-5v4a1 1 0 01-1 1h-1"/></svg>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                @if($delivery['customer_phone'])
                <a href="tel:{{ $delivery['customer_phone'] }}" class="flex items-center justify-center gap-1.5 py-2.5 rounded-xl border border-brand-200 bg-brand-50 text-brand-700 text-xs font-bold hover:bg-brand-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    {{ __('Call') }}
                </a>
                @if($delivery['whatsapp_phone'])
                <a href="https://wa.me/{{ $delivery['whatsapp_phone'] }}" target="_blank" rel="noopener"
                    class="flex items-center justify-center gap-1.5 py-2.5 rounded-xl bg-green-50 text-green-700 border border-green-200 text-xs font-bold hover:bg-green-100 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.6 6.32A7.85 7.85 0 0012 4a7.94 7.94 0 00-8 7.88c0 1.39.36 2.74 1.05 3.94L4 20l4.3-1.12A7.93 7.93 0 0012 19.77h.02A7.94 7.94 0 0020 11.89a7.85 7.85 0 00-2.4-5.57zM12 18.1a6.2 6.2 0 01-3.16-.87l-.23-.14-2.55.67.68-2.49-.18-.28a6.23 6.23 0 119.16 1.91 6.18 6.18 0 01-3.72 1.2zM14.6 13.5c-.08-.13-.28-.2-.58-.35-.3-.15-1.77-.87-2.05-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.95 1.17-.17.2-.35.22-.65.08-.3-.15-1.27-.47-2.42-1.5a8.9 8.9 0 01-1.65-2.02c-.17-.3 0-.46.13-.6.13-.14.3-.35.44-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.5l-.37-.01c-.13 0-.35.05-.53.25-.18.2-.7.68-.7 1.66s.72 1.93.82 2.06c.1.13 1.4 2.13 3.4 2.99.47.2.85.33 1.14.42.48.15.92.13 1.27.08.39-.06 1.2-.49 1.37-.96.17-.47.17-.87.12-.96z"/></svg>
                    {{ __('WhatsApp') }}
                </a>
                @endif
                @endif
            </div>
            <a href="{{ route('driver.deliveries.detail', $delivery['id']) }}" class="mt-2 flex items-center justify-center gap-1.5 py-2 rounded-xl bg-gray-50 text-gray-600 text-[10px] font-bold hover:bg-gray-100 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ __('Full Details') }}
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
function directionsMap() {
    return {
        map: null,
        driverMarker: null,
        destMarker: null,
        routeLine: null,
        destination: null,
        lastRouteFetch: 0,
        watchId: null,
        etaText: '{{ __('Calculating route...') }}',
        distanceText: '',
        statusText: '{{ __('Live tracking') }}',

        init() {
            this.map = L.map('directionsMap', { zoomControl: false }).setView([24.7136, 46.6753], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(this.map);

            this.geocodeDestination(@json($delivery['address'] ?? ''));
            this.watchDriverLocation();
        },

        async geocodeDestination(address) {
            if (!address) return;
            try {
                const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(address)}`);
                const data = await res.json();
                if (data && data[0]) {
                    this.destination = { lat: parseFloat(data[0].lat), lon: parseFloat(data[0].lon) };

                    const destIcon = L.divIcon({
                        className: '',
                        html: `<div class="dest-marker-wrap dest-marker-pin">
                                    <svg width="30" height="40" viewBox="0 0 30 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15 0C6.7 0 0 6.7 0 15c0 10.5 15 25 15 25s15-14.5 15-25C30 6.7 23.3 0 15 0z" fill="#dc2626"/>
                                        <circle cx="15" cy="15" r="6" fill="white"/>
                                    </svg>
                               </div>`,
                        iconSize: [30, 40],
                        iconAnchor: [15, 40],
                    });

                    this.destMarker = L.marker([this.destination.lat, this.destination.lon], { icon: destIcon }).addTo(this.map);
                    this.map.setView([this.destination.lat, this.destination.lon], 14);
                }
            } catch (e) {}
        },

        watchDriverLocation() {
            if (!navigator.geolocation) {
                this.statusText = '{{ __('Location unavailable') }}';
                return;
            }
            this.watchId = navigator.geolocation.watchPosition(
                (pos) => this.updateDriverPosition(pos.coords.latitude, pos.coords.longitude),
                () => { this.statusText = '{{ __('Location unavailable') }}'; },
                { enableHighAccuracy: true, maximumAge: 5000, timeout: 10000 }
            );
        },

        updateDriverPosition(lat, lon) {
            const icon = L.divIcon({
                className: '',
                html: '<div class="driver-marker-wrap"><div class="driver-marker-pulse"></div><div class="driver-marker-dot"></div></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10],
            });

            if (!this.driverMarker) {
                this.driverMarker = L.marker([lat, lon], { icon, zIndexOffset: 1000 }).addTo(this.map);
                this.fitToBounds(lat, lon);
            } else {
                this.animateMarkerTo(this.driverMarker, lat, lon);
            }

            this.maybeDrawRoute(lat, lon);
        },

        animateMarkerTo(marker, lat, lon) {
            const start = marker.getLatLng();
            const startTime = performance.now();
            const duration = 900;

            const step = (now) => {
                const t = Math.min((now - startTime) / duration, 1);
                const eased = 1 - Math.pow(1 - t, 3);
                const nlat = start.lat + (lat - start.lat) * eased;
                const nlon = start.lng + (lon - start.lng) * eased;
                marker.setLatLng([nlat, nlon]);
                if (t < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        },

        fitToBounds(lat, lon) {
            if (this.destination) {
                this.map.fitBounds([[lat, lon], [this.destination.lat, this.destination.lon]], { padding: [70, 70] });
            } else {
                this.map.setView([lat, lon], 15);
            }
        },

        recenter() {
            if (this.driverMarker) {
                const pos = this.driverMarker.getLatLng();
                this.fitToBounds(pos.lat, pos.lng);
            } else if (this.destination) {
                this.map.setView([this.destination.lat, this.destination.lon], 14);
            }
        },

        maybeDrawRoute(driverLat, driverLon) {
            const now = Date.now();
            if (now - this.lastRouteFetch < 15000) return;
            this.lastRouteFetch = now;
            this.drawRoute(driverLat, driverLon);
        },

        async drawRoute(driverLat, driverLon) {
            if (!this.destination) return;
            try {
                const res = await fetch(`https://router.project-osrm.org/route/v1/driving/${driverLon},${driverLat};${this.destination.lon},${this.destination.lat}?overview=full&geometries=geojson`);
                const data = await res.json();
                if (data.routes && data.routes[0]) {
                    const coords = data.routes[0].geometry.coordinates.map((c) => [c[1], c[0]]);

                    if (this.routeLine) this.map.removeLayer(this.routeLine);
                    this.routeLine = L.polyline(coords, { color: '#6E7A25', weight: 5, opacity: 0.85, lineCap: 'round' }).addTo(this.map);

                    const seconds = data.routes[0].duration;
                    const meters = data.routes[0].distance;
                    const minutes = Math.round(seconds / 60);

                    this.etaText = minutes <= 1 ? '{{ __('Arriving now') }}' : minutes + ' {{ __('min away') }}';
                    this.distanceText = (meters / 1000).toFixed(1) + ' km · {{ __('to customer') }}';
                }
            } catch (e) {}
        },
    };
}
</script>
@endpush

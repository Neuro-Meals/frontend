<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Coming Soon') . ' - ' . __('Nutrio Meals'))</title>
    <link rel="icon" type="image/png" href="{{ asset('whitelogo.png') }}">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#173327',
                        accent: '#6E7A25',
                        accent2: '#949B50',
                        teal: '#025C5F',
                    },
                    fontFamily: {
                        sans: ['Nunito', 'Cairo', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Nunito', 'Cairo', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#0a1f1a]">
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#0a1f1a] via-[#0d2820] to-[#173327] px-4 py-12">
  {{-- Decorative background elements --}}
  <div class="absolute inset-0 overflow-hidden pointer-events-none">
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-[#6E7A25]/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-[#025C5F]/10 rounded-full blur-3xl"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] border border-white/5 rounded-full"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] border border-white/5 rounded-full"></div>
  </div>

  <div class="relative z-10 max-w-2xl w-full text-center">
    {{-- Logo --}}
    <div class="flex items-center justify-center mb-8">
      <img src="{{ asset('blackmodelogo.png') }}" alt="Nutrio Meals" class="h-16 w-auto brightness-0 invert" onerror="this.style.display='none'">
    </div>

    {{-- Coming Soon Icon --}}
    <div class="inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-gradient-to-br from-[#6E7A25] to-[#173327] shadow-2xl shadow-[#6E7A25]/30 mb-8 relative">
      <div class="absolute inset-0 rounded-3xl bg-white/5 backdrop-blur-sm"></div>
      <svg class="w-12 h-12 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
      </svg>
    </div>

    {{-- Heading --}}
    <h1 class="text-4xl md:text-5xl font-black text-white mb-4 tracking-tight">
      {{ __('Coming Soon to Your Area') }}
    </h1>

    {{-- Location badge --}}
    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/10 mb-6">
      <svg class="w-4 h-4 text-[#949B50]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
      </svg>
      <span class="text-sm font-semibold text-white/90" id="user-location">{{ $location ?? '' }}</span>
    </div>

    {{-- Description --}}
    <p class="text-base md:text-lg text-white/60 mb-2 max-w-lg mx-auto leading-relaxed">
      {{ __('We haven\'t started serving meals in your area yet, but we\'re expanding fast!') }}
    </p>
    <p class="text-sm text-white/40 mb-10 max-w-md mx-auto">
      {{ __('We\'ll notify you by email as soon as we begin operations in your region.') }}
    </p>

    {{-- Email notification badge --}}
    <div class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-[#6E7A25]/20 to-[#173327]/20 border border-[#6E7A25]/30 mb-10">
      <svg class="w-5 h-5 text-[#949B50]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
      </svg>
      <span class="text-sm text-white/80 font-medium" id="user-email">{{ $email ?? '' }}</span>
      <span class="text-xs text-[#949B50] font-bold bg-[#6E7A25]/20 px-2 py-0.5 rounded-full">{{ __('Notified') }}</span>
    </div>

    {{-- Available areas --}}
    <div class="mb-10">
      <p class="text-xs font-bold text-white/30 uppercase tracking-wider mb-4">{{ __('Currently Serving') }}</p>
      <div class="flex flex-wrap items-center justify-center gap-2">
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-500/10 border border-green-500/20 text-green-400 text-xs font-semibold">
          <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
          {{ __('Riyadh') }}
        </span>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-white/30 text-xs font-medium">
          {{ __('Jeddah') }} · {{ __('Soon') }}
        </span>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-white/30 text-xs font-medium">
          {{ __('Dammam') }} · {{ __('Soon') }}
        </span>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-white/30 text-xs font-medium">
          {{ __('Mecca') }} · {{ __('Soon') }}
        </span>
      </div>
    </div>

    {{-- Change Location --}}
    <div x-data="changeLocation()" class="mb-10">
      <button @click="open = !open" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-white/5 border border-white/10 text-white/80 text-sm font-semibold hover:bg-white/10 transition-all backdrop-blur-sm">
        <svg class="w-4 h-4 text-[#949B50]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        {{ __('Change My Location') }}
        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
      </button>

      <div x-show="open" x-transition class="mt-4 bg-white/5 border border-white/10 rounded-2xl p-5 text-left backdrop-blur-sm">
        <p class="text-xs text-white/50 mb-4 text-center">{{ __('Select your city to check if we deliver there') }}</p>

        <div class="space-y-4">
          <div>
            <label class="text-[10px] font-bold text-white/40 uppercase tracking-wider mb-1.5 block">{{ __('City') }} <span class="text-red-400">*</span></label>
            <select x-model="form.location" class="w-full text-sm bg-white/10 border border-white/15 rounded-xl px-3 py-2.5 text-white outline-none focus:ring-2 focus:ring-[#6E7A25]/40 focus:border-[#6E7A25] transition-all">
              <option value="" class="bg-[#173327]">{{ __('Select your city...') }}</option>
              <option value="Riyadh" class="bg-[#173327]">{{ __('Riyadh') }} ✓</option>
              <option value="Jeddah" class="bg-[#173327]">{{ __('Jeddah') }}</option>
              <option value="Dammam" class="bg-[#173327]">{{ __('Dammam') }}</option>
              <option value="Mecca" class="bg-[#173327]">{{ __('Mecca') }}</option>
              <option value="Medina" class="bg-[#173327]">{{ __('Medina') }}</option>
              <option value="Khobar" class="bg-[#173327]">{{ __('Khobar') }}</option>
              <option value="Tabuk" class="bg-[#173327]">{{ __('Tabuk') }}</option>
              <option value="Abha" class="bg-[#173327]">{{ __('Abha') }}</option>
              <option value="Hail" class="bg-[#173327]">{{ __('Hail') }}</option>
              <option value="Buraidah" class="bg-[#173327]">{{ __('Buraidah') }}</option>
              <option value="Najran" class="bg-[#173327]">{{ __('Najran') }}</option>
              <option value="Jazan" class="bg-[#173327]">{{ __('Jazan') }}</option>
            </select>
          </div>

          <div>
            <label class="text-[10px] font-bold text-white/40 uppercase tracking-wider mb-1.5 block">{{ __('Delivery Address') }}</label>
            <input type="text" x-model="form.address" class="w-full text-sm bg-white/10 border border-white/15 rounded-xl px-3 py-2.5 text-white placeholder-white/30 outline-none focus:ring-2 focus:ring-[#6E7A25]/40 focus:border-[#6E7A25] transition-all" placeholder="e.g. Al Olaya, King Fahd Rd...">
          </div>

          <div x-show="form.location === 'Riyadh'" x-transition class="flex items-center gap-2 px-3 py-2.5 rounded-xl bg-green-500/10 border border-green-500/20">
            <svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-xs text-green-400 font-medium">{{ __('Great! We serve Riyadh. Update to access your dashboard.') }}</span>
          </div>

          <div x-show="message" x-transition class="px-3 py-2.5 rounded-xl" :class="success ? 'bg-green-500/10 border border-green-500/20' : 'bg-red-500/10 border border-red-500/20'">
            <span class="text-xs font-medium" :class="success ? 'text-green-400' : 'text-red-400'" x-text="message"></span>
          </div>

          <button @click="submit()" :disabled="saving || !form.location" class="w-full px-4 py-3 rounded-xl bg-gradient-to-r from-[#6E7A25] to-[#173327] text-white text-sm font-bold hover:shadow-lg hover:shadow-[#6E7A25]/30 transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2">
            <svg x-show="!saving" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <svg x-show="saving" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            <span x-text="saving ? '{{ __('Updating...') }}' : '{{ __('Update Location') }}'"></span>
          </button>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
      <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-[#6E7A25] to-[#173327] text-white text-sm font-bold hover:shadow-lg hover:shadow-[#6E7A25]/30 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        {{ __('Logout') }}
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
    </div>

    {{-- Footer --}}
    <p class="text-xs text-white/20 mt-12">
      &copy; {{ date('Y') }} {{ __('Nutrio Meals') }} · {{ __('Healthy meals delivered to your door') }}
    </p>
  </div>
</div>

<script>
function changeLocation() {
    return {
        open: false,
        saving: false,
        message: '',
        success: false,
        form: {
            location: '{{ $location ?? '' }}',
            address: '',
        },

        async submit() {
            if (!this.form.location) return;
            this.saving = true;
            this.message = '';
            try {
                const r = await fetch('{{ route('coming-soon.update-location') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify(this.form)
                });
                const d = await r.json();
                this.success = d.success;
                this.message = d.message || (d.error || '{{ __('Something went wrong.') }}');
                if (d.success && d.redirect) {
                    setTimeout(() => { window.location.href = d.redirect; }, 1200);
                }
            } catch(e) {
                console.error('Location update failed', e);
                this.success = false;
                this.message = '{{ __('Failed to update location. Please try again.') }}';
            } finally {
                this.saving = false;
            }
        }
    }
}
</script>
</body>
</html>

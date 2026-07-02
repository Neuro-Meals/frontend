{{-- Language Switcher --}}
@php
    $currentLocale = app()->getLocale();
    $isAr = $currentLocale === 'ar';
@endphp

<div class="relative inline-block" x-data="{ open: false }" @click.outside="open = false">
    <button @click="open = !open"
            class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $isDark ?? false ? 'text-white/70 hover:text-white hover:bg-white/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
        </svg>
        <span>{{ $isAr ? 'ع' : 'EN' }}</span>
        <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>

    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="absolute {{ $isAr ? 'left-0' : 'right-0' }} top-full mt-1 w-32 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50" style="display: none;">
        <a href="{{ route('locale.switch', 'en') }}"
           class="flex items-center gap-2 px-3 py-2 text-xs font-medium transition-colors {{ $currentLocale === 'en' ? 'text-[#6E7A25] bg-[#949B50]/10' : 'text-gray-600 hover:bg-gray-50' }}">
            <span class="text-base">🇬🇧</span>
            <span>English</span>
            @if($currentLocale === 'en')
            <svg class="w-3.5 h-3.5 ml-auto text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            @endif
        </a>
        <a href="{{ route('locale.switch', 'ar') }}"
           class="flex items-center gap-2 px-3 py-2 text-xs font-medium transition-colors {{ $currentLocale === 'ar' ? 'text-[#6E7A25] bg-[#949B50]/10' : 'text-gray-600 hover:bg-gray-50' }}">
            <span class="text-base">🇸🇦</span>
            <span>العربية</span>
            @if($currentLocale === 'ar')
            <svg class="w-3.5 h-3.5 ml-auto text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            @endif
        </a>
    </div>
</div>

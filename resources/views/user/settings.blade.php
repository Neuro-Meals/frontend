@extends('layouts.user')

@section('title', __('Settings') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Settings'))

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    {{-- Profile Info --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 lg:col-span-2">
        <h3 class="text-sm font-bold text-gray-900 mb-4">{{ __('Profile') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Information') }}</span></h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Full Name') }}</label>
                <input type="text" value="{{ $profile['name'] }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Email') }}</label>
                <input type="email" value="{{ $profile['email'] }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Phone') }}</label>
                <input type="text" value="{{ $profile['phone'] }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Date of Birth') }}</label>
                <input type="text" value="{{ $profile['dob'] }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Gender') }}</label>
                <input type="text" value="{{ $profile['gender'] }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Activity Level') }}</label>
                <input type="text" value="{{ $profile['activity'] }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all" readonly>
            </div>
        </div>
        <div class="mt-5">
            <label class="text-[10px] font-medium text-gray-400">{{ __('Delivery Address') }}</label>
            <textarea class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all" rows="2" readonly>{{ $profile['address'] }}</textarea>
        </div>
        <button class="mt-5 px-4 py-2 text-xs font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-lg hover:shadow-md transition-all">
            {{ __('Save Changes') }}
        </button>
    </div>

    {{-- Health Goals --}}
    <div class="bg-gradient-to-br from-[#173327] to-[#122620] rounded-xl p-5 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-[#6E7A25]/10 rounded-full -mr-12 -mt-12 blur-2xl"></div>
        <h3 class="text-sm font-bold mb-4 relative z-10">{{ __('Health') }} <span class="text-[#6E7A25]">{{ __('Goals') }}</span></h3>
        <div class="space-y-4 relative z-10">
            <div>
                <span class="text-[10px] text-white/50">{{ __('Height') }}</span>
                <p class="text-lg font-bold">{{ $profile['height'] }} cm</p>
            </div>
            <div>
                <span class="text-[10px] text-white/50">{{ __('Current Weight') }}</span>
                <p class="text-lg font-bold">{{ $profile['weight'] }} kg</p>
            </div>
            <div>
                <span class="text-[10px] text-white/50">{{ __('Goal') }}</span>
                <p class="text-lg font-bold text-[#6E7A25]">{{ $profile['goal'] }}</p>
            </div>
            <div>
                <span class="text-[10px] text-white/50">{{ __('Delivery Zone') }}</span>
                <p class="text-sm font-bold">{{ $profile['zone'] }}</p>
            </div>
        </div>
    </div>
</div>

@endsection

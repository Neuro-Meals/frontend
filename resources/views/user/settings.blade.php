@extends('layouts.user')

@section('title', 'Settings - Nutrio Meals')
@section('page_title', 'Settings')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    {{-- Profile Info --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 lg:col-span-2">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Profile <span class="bg-gradient-to-r from-[#033133] to-[#259B00] bg-clip-text text-transparent">Information</span></h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="text-[10px] font-medium text-gray-400">Full Name</label>
                <input type="text" value="{{ $profile['name'] }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#259B00]/20 focus:border-[#259B00] outline-none transition-all" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">Email</label>
                <input type="email" value="{{ $profile['email'] }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#259B00]/20 focus:border-[#259B00] outline-none transition-all" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">Phone</label>
                <input type="text" value="{{ $profile['phone'] }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#259B00]/20 focus:border-[#259B00] outline-none transition-all" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">Date of Birth</label>
                <input type="text" value="{{ $profile['dob'] }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#259B00]/20 focus:border-[#259B00] outline-none transition-all" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">Gender</label>
                <input type="text" value="{{ $profile['gender'] }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#259B00]/20 focus:border-[#259B00] outline-none transition-all" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">Activity Level</label>
                <input type="text" value="{{ $profile['activity'] }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#259B00]/20 focus:border-[#259B00] outline-none transition-all" readonly>
            </div>
        </div>
        <div class="mt-5">
            <label class="text-[10px] font-medium text-gray-400">Delivery Address</label>
            <textarea class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#259B00]/20 focus:border-[#259B00] outline-none transition-all" rows="2" readonly>{{ $profile['address'] }}</textarea>
        </div>
        <button class="mt-5 px-4 py-2 text-xs font-bold text-white bg-gradient-to-r from-[#033133] to-[#259B00] rounded-lg hover:shadow-md transition-all">
            Save Changes
        </button>
    </div>

    {{-- Health Goals --}}
    <div class="bg-gradient-to-br from-[#033133] to-[#01241f] rounded-xl p-5 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-[#259B00]/10 rounded-full -mr-12 -mt-12 blur-2xl"></div>
        <h3 class="text-sm font-bold mb-4 relative z-10">Health <span class="text-[#259B00]">Goals</span></h3>
        <div class="space-y-4 relative z-10">
            <div>
                <span class="text-[10px] text-white/50">Height</span>
                <p class="text-lg font-bold">{{ $profile['height'] }} cm</p>
            </div>
            <div>
                <span class="text-[10px] text-white/50">Current Weight</span>
                <p class="text-lg font-bold">{{ $profile['weight'] }} kg</p>
            </div>
            <div>
                <span class="text-[10px] text-white/50">Goal</span>
                <p class="text-lg font-bold text-[#259B00]">{{ $profile['goal'] }}</p>
            </div>
            <div>
                <span class="text-[10px] text-white/50">Delivery Zone</span>
                <p class="text-sm font-bold">{{ $profile['zone'] }}</p>
            </div>
        </div>
    </div>
</div>

@endsection

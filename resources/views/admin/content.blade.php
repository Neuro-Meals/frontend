@extends('layouts.admin')

@section('title', 'Content - Nutrio Meals')
@section('page_title', 'Content Management')

@section('content')
{{-- Stats Row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">Total Pages</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['totalPages'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">Published</p>
        <p class="text-2xl font-bold text-green-600">{{ $stats['published'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">Draft</p>
        <p class="text-2xl font-bold text-amber-600">{{ $stats['draft'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">Total Views</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['totalViews']) }}</p>
    </div>
</div>

{{-- Action Bar --}}
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center bg-white rounded-lg px-3 py-2 border border-gray-100 shadow-sm flex-1 max-w-xs">
        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" placeholder="Search pages..." class="bg-transparent text-sm outline-none flex-1 text-gray-600 placeholder-gray-400">
    </div>
    <button class="px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-[#033133] to-[#259B00] rounded-lg shadow-sm hover:shadow-md transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Page
    </button>
</div>

{{-- Content Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($pages as $page)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#259B00]/10 to-[#033133]/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-[#259B00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold border {{ $page['status'] === 'published' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                {{ ucfirst($page['status']) }}
            </span>
        </div>
        <h3 class="text-sm font-bold text-gray-900 mb-1">{{ $page['title'] }}</h3>
        <p class="text-xs text-gray-400 mb-4">/{{ $page['slug'] }}</p>
        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span class="text-[10px] text-gray-400">{{ number_format($page['views']) }}</span>
                </div>
                <span class="text-[10px] text-gray-400">{{ date('M d, Y', strtotime($page['updated'])) }}</span>
            </div>
            <div class="flex items-center gap-2">
                <button class="text-xs font-medium text-[#259B00] hover:text-[#033133] transition-colors">Edit</button>
                <button class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

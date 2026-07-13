@extends('layouts.auth')

@section('title', __('Register') . ' - ' . __('Nutrio Meals'))

@section('content')
<div class="w-full max-w-md animate-simple-fade-in" x-data="registerForm()">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="bg-white px-8 py-8 text-center border-b border-gray-100">
            <div class="mx-auto mb-4 flex items-center justify-center">
                <img src="{{ asset('whitelogo.png') }}" alt="{{ config('app.name', 'Nitrio Meals') }}" class="h-20 w-auto object-contain">
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900">{{ __('Create Account') }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ __('Get started with') }} {{ config('app.name', 'Nitrio Meals') }}</p>
        </div>

        {{-- Form --}}
        <div class="p-8">

            {{-- Toast Notification --}}
            <div x-show="toast.show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="fixed top-5 left-1/2 -translate-x-1/2 z-50 max-w-sm w-full px-4" x-cloak>
                <div class="rounded-xl border shadow-xl p-4 flex items-start gap-3"
                     :class="toast.type === 'success' ? 'border-emerald-200 bg-white dark:bg-gray-900 dark:border-emerald-800/40' : 'border-red-200 bg-white dark:bg-gray-900 dark:border-red-800/40'">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                         :class="toast.type === 'success' ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-red-100 dark:bg-red-900/30'">
                        <svg x-show="toast.type === 'success'" class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <svg x-show="toast.type !== 'success'" class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="toast.title"></p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-0.5 break-words" x-text="toast.message"></p>
                    </div>
                    <button @click="toast.show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors flex-shrink-0" aria-label="Close">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form class="space-y-5" method="POST" action="{{ route('register') }}" @submit.prevent="submit">

                {{-- First Name & Last Name --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('First Name') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input id="first_name" type="text" name="first_name" x-model="form.first_name" required autocomplete="given-name" autofocus
                                class="w-full pl-11 pr-4 py-2.5 rounded-lg border outline-none transition-all text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                                :class="errors.first_name ? 'border-red-300 ring-2 ring-red-100' : 'border-gray-200'"
                                placeholder="{{ app()->getLocale() === 'ar' ? 'سعود' : 'Saud' }}">
                        </div>
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('Last Name') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input id="last_name" type="text" name="last_name" x-model="form.last_name" required autocomplete="family-name"
                                class="w-full pl-11 pr-4 py-2.5 rounded-lg border outline-none transition-all text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                                :class="errors.last_name ? 'border-red-300 ring-2 ring-red-100' : 'border-gray-200'"
                                placeholder="{{ app()->getLocale() === 'ar' ? 'عبدالرحمن' : 'Abdulrahman' }}">
                        </div>
                    </div>
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('Phone') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <input id="phone" type="tel" name="phone" x-model="form.phone" required autocomplete="tel" minlength="8"
                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border outline-none transition-all text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                            :class="errors.phone ? 'border-red-300 ring-2 ring-red-100' : 'border-gray-200'"
                            placeholder="+966 55 123 4567">
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('Email') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" x-model="form.email" required autocomplete="email"
                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border outline-none transition-all text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                            :class="errors.email || errors.general ? 'border-red-300 ring-2 ring-red-100' : 'border-gray-200'"
                            placeholder="name@example.com">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('Password') }}</label>
                    <div class="relative" x-data="{ showPassword: false }">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password" x-model="form.password" required autocomplete="new-password" minlength="6"
                            class="w-full pl-11 pr-11 py-2.5 rounded-lg border outline-none transition-all text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                            :class="errors.password ? 'border-red-300 ring-2 ring-red-100' : 'border-gray-200'"
                            placeholder="Min. 6 characters">
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-emerald-600 transition-colors focus:outline-none"
                            :aria-label="showPassword ? 'Hide password' : 'Show password'">
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a9.969 9.969 0 01-4.771 5.378M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password-confirm" class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('Confirm Password') }}</label>
                    <div class="relative" x-data="{ showPasswordConfirmation: false }">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <input id="password-confirm" :type="showPasswordConfirmation ? 'text' : 'password'" name="password_confirmation" x-model="form.password_confirmation" required autocomplete="new-password"
                            class="w-full pl-11 pr-11 py-2.5 rounded-lg border outline-none transition-all text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                            :class="errors.password_confirmation ? 'border-red-300 ring-2 ring-red-100' : 'border-gray-200'"
                            placeholder="Re-enter your password">
                        <button type="button" @click="showPasswordConfirmation = !showPasswordConfirmation"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-emerald-600 transition-colors focus:outline-none"
                            :aria-label="showPasswordConfirmation ? 'Hide password' : 'Show password'">
                            <svg x-show="!showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a9.969 9.969 0 01-4.771 5.378M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Location --}}
                <div class="relative" @click.away="locationOpen = false">
                    <label for="location" class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('Location') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <input id="location" type="text" name="location" x-model="form.location" required
                            class="w-full pl-11 pr-11 py-2.5 rounded-lg border outline-none transition-all text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                            :class="errors.location ? 'border-red-300 ring-2 ring-red-100' : 'border-gray-200'"
                            placeholder="{{ __('Select your city') }}">
                        <button type="button" @click="toggleLocationPicker()"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-emerald-600 transition-colors focus:outline-none"
                            :aria-label="locationOpen ? '{{ __('Close location picker') }}' : '{{ __('Open location picker') }}'"
                            title="{{ __('Choose location') }}">
                            <svg x-show="!locationLoading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <svg x-show="locationLoading" class="animate-spin w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Location Picker Dropdown --}}
                    <div x-show="locationOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" x-cloak
                        class="absolute z-50 mt-2 w-full max-w-md bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden"
                        style="left: 0; right: 0;">
                        <div class="p-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                            <h3 class="text-sm font-bold text-gray-900">{{ __('Choose your location') }}</h3>
                            <button type="button" @click="locationOpen = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="p-4 max-h-80 overflow-y-auto">
                            {{-- Region selector --}}
                            <div class="mb-3">
                                <label class="block text-xs font-semibold text-gray-500 mb-1.5">{{ __('Region') }}</label>
                                <select x-model="selectedRegion" @change="loadCities()"
                                    class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none bg-white">
                                    <option value="">{{ __('Select a region') }}</option>
                                    <template x-for="region in regions" :key="region.code">
                                        <option :value="region.code" x-text="region.name_en + (region.name_ar ? ' (' + region.name_ar + ')' : '')"></option>
                                    </template>
                                </select>
                            </div>

                            {{-- Cities list --}}
                            <div x-show="selectedRegion && cities.length" x-transition>
                                <label class="block text-xs font-semibold text-gray-500 mb-1.5">{{ __('City') }}</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <template x-for="city in cities" :key="city.code">
                                        <button type="button" @click="selectCity(city)"
                                            class="text-left px-3 py-2 rounded-lg border border-gray-100 hover:border-emerald-400 hover:bg-emerald-50 text-xs font-medium text-gray-700 transition-colors"
                                            :class="form.location === city.name_en ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : ''"
                                            x-text="city.name_en + (city.name_ar ? ' / ' + city.name_ar : '')">
                                        </button>
                                    </template>
                                </div>
                            </div>

                            {{-- Empty states --}}
                            <div x-show="selectedRegion && !cities.length && !locationLoading" class="text-center py-6 text-sm text-gray-400">
                                {{ __('No cities found for this region.') }}
                            </div>
                            <div x-show="!selectedRegion && regions.length && !locationLoading" class="text-center py-6 text-sm text-gray-400">
                                {{ __('Select a region to see cities.') }}
                            </div>
                            <div x-show="locationError" class="mt-3 p-3 rounded-lg bg-red-50 text-red-700 text-xs" x-text="locationError"></div>
                        </div>

                        <div class="p-3 border-t border-gray-100 bg-gray-50 text-center">
                            <span class="text-[10px] text-gray-400">{{ __('Locations provided by Nutrio Meals') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Address --}}
                <div>
                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('Address') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <input id="address" type="text" name="address" x-model="form.address" required
                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border outline-none transition-all text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                            :class="errors.address ? 'border-red-300 ring-2 ring-red-100' : 'border-gray-200'"
                            placeholder="e.g. King Fahd Road">
                    </div>
                </div>


                {{-- Submit --}}
                <button type="submit" :disabled="loading"
                    class="w-full py-3 text-sm font-bold text-white rounded-lg shadow-md transition-all flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                    :class="loading ? 'bg-gray-400' : 'bg-gradient-to-r from-brand-light to-brand-dark hover:from-brand-dark hover:to-brand-light hover:shadow-lg'">
                    <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <svg x-show="loading" class="animate-spin w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="loading ? pleaseWait : createAccount"></span>
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                <div class="relative flex justify-center text-sm"><span class="px-3 bg-white text-gray-400">or</span></div>
            </div>

            {{-- Login link --}}
            <p class="text-center text-sm text-gray-500">
                {{ __('Already have an account?') }}
                <a href="{{ route('login') }}" class="font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">{{ __('Login') }}</a>
            </p>
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-gray-300">&copy; {{ date('Y') }} {{ config('app.name', 'Nitrio Meals') }}. All rights reserved.</p>
</div>

@push('scripts')
<script>
    function registerForm() {
        return {
            loading: false,
            toast: { show: false, message: '', type: 'error', title: '' },
            errors: {},
            pleaseWait: @json(__('Please wait...')),
            createAccount: @json(__('Create Account')),
            registrationFailed: @json(__('Registration failed')),
            successTitle: @json(__('Success')),
            networkError: @json(__('Network error. Please try again.')),
            registerUrl: @json(route('register')),
            locationsUrl: @json(route('register.locations')),
            form: {
                first_name: '',
                last_name: '',
                phone: '',
                email: '',
                password: '',
                password_confirmation: '',
                location: '',
                address: ''
            },
            locationOpen: false,
            locationLoading: false,
            locationError: '',
            regions: [],
            cities: [],
            selectedRegion: '',
            toggleLocationPicker() {
                this.locationOpen = !this.locationOpen;
                if (this.locationOpen && this.regions.length === 0) {
                    this.loadRegions();
                }
            },
            async loadRegions() {
                this.locationLoading = true;
                this.locationError = '';
                try {
                    const response = await fetch(this.locationsUrl + '?type=regions');
                    const result = await response.json();
                    if (result.success && Array.isArray(result.data)) {
                        this.regions = result.data.sort((a, b) => a.name_en.localeCompare(b.name_en));
                    } else {
                        this.locationError = result.message || '{{ __('Unable to load regions.') }}';
                    }
                } catch (err) {
                    this.locationError = '{{ __('Network error. Please try again.') }}';
                } finally {
                    this.locationLoading = false;
                }
            },
            async loadCities() {
                this.cities = [];
                if (!this.selectedRegion) {
                    return;
                }
                this.locationLoading = true;
                this.locationError = '';
                try {
                    const response = await fetch(this.locationsUrl + '?type=cities&region_code=' + encodeURIComponent(this.selectedRegion));
                    const result = await response.json();
                    if (result.success && Array.isArray(result.data)) {
                        this.cities = result.data.sort((a, b) => a.name_en.localeCompare(b.name_en));
                    } else {
                        this.locationError = result.message || '{{ __('Unable to load cities.') }}';
                    }
                } catch (err) {
                    this.locationError = '{{ __('Network error. Please try again.') }}';
                } finally {
                    this.locationLoading = false;
                }
            },
            selectCity(city) {
                this.form.location = city.name_en;
                this.locationOpen = false;
            },
            showToast(message, type = 'error') {
                this.toast = {
                    show: true,
                    message: message,
                    type: type,
                    title: type === 'success' ? this.successTitle : this.registrationFailed
                };
                setTimeout(() => { this.toast.show = false }, 7000);
            },
            async submit() {
                this.loading = true;
                this.errors = {};
                this.toast.show = false;

                console.log('Register form data:', JSON.parse(JSON.stringify(this.form)));

                try {
                    const response = await fetch(this.registerUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(this.form)
                    });

                    const data = await response.json();
                    this.loading = false;
                    console.log('Register response:', data);

                    if (data.success) {
                        this.showToast(data.message, 'success');
                        if (data.requires_verification) {
                            setTimeout(() => {
                                window.location.href = data.redirect || (@json(route('verify.email')) + '?email=' + encodeURIComponent(this.form.email));
                            }, 1500);
                            return;
                        }
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                        return;
                    }

                    this.errors = data.errors || {};

                    const messages = [];
                    if (data.message) messages.push(data.message);
                    Object.values(this.errors).forEach(fieldErrors => {
                        if (Array.isArray(fieldErrors)) messages.push(...fieldErrors);
                        else messages.push(fieldErrors);
                    });

                    this.showToast(messages.length ? messages.join(' | ') : this.registrationFailed);
                } catch (error) {
                    this.loading = false;
                    this.showToast(error.message || this.networkError);
                }
            }
        };
    }
</script>
@endpush
@endsection

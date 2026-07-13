import re

path = r'f:\nitro\Nitromeals\resources\views\auth\register.blade.php'
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Add step indicator after header paragraph
header_p = '            <p class="text-gray-500 text-sm mt-1">{{ __(\'Get started with\') }} {{ config(\'app.name\', \'Nutrio Meals\') }}</p>\n        </div>'
step_indicator = '''            <p class="text-gray-500 text-sm mt-1">{{ __('Get started with') }} {{ config('app.name', 'Nutrio Meals') }}</p>

            {{-- Step Indicator --}}
            <div class="mt-6 flex items-center justify-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-colors"
                        :class="step >= 1 ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-500'">1</div>
                    <span class="text-xs font-medium" :class="step >= 1 ? 'text-emerald-700' : 'text-gray-400'">{{ __('Account') }}</span>
                </div>
                <div class="w-12 h-1 rounded-full transition-colors" :class="step >= 2 ? 'bg-emerald-600' : 'bg-gray-200'"></div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-colors"
                        :class="step >= 2 ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-500'">2</div>
                    <span class="text-xs font-medium" :class="step >= 2 ? 'text-emerald-700' : 'text-gray-400'">{{ __('Profile') }}</span>
                </div>
            </div>
        </div>'''
content = content.replace(header_p, step_indicator)

# 2. Wrap step 1 fields
form_open = '<form class="space-y-5" method="POST" action="{{ route(\'register\') }}" @submit.prevent="submit">'
step1_open = '''<form class="space-y-5" method="POST" action="{{ route('register') }}" @submit.prevent="submit">

                {{-- Step 1: Account Details --}}
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-4">'''
content = content.replace(form_open, step1_open)

# 3. Close step 1 and open step 2 before Location
loc_marker = '''                {{-- Location --}}
                <div x-data="locationPicker()" @click.away="open = false">'''
step1_close_step2_open = '''                    </div>
                </div>

                {{-- Step 2: Profile & Location --}}
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-4">

                {{-- Location --}}
                <div x-data="locationPicker()" @click.away="open = false">'''
content = content.replace(loc_marker, step1_close_step2_open)

# 4. Replace submit button with Next/Back/Submit buttons
old_submit = '''                {{-- Submit --}}
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
                </button>'''
new_buttons = '''                {{-- Step Actions --}}
                <div class="flex flex-col gap-3 pt-2" x-show="!loading">
                    <button type="button" x-show="step === 1" @click="nextStep()"
                        class="w-full py-3 text-sm font-bold text-white rounded-lg shadow-md transition-all flex items-center justify-center gap-2 bg-gradient-to-r from-brand-light to-brand-dark hover:from-brand-dark hover:to-brand-light hover:shadow-lg">
                        <span x-text="nextStepLabel"></span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    <div x-show="step === 2" class="flex flex-col gap-3">
                        <button type="submit" :disabled="loading"
                            class="w-full py-3 text-sm font-bold text-white rounded-lg shadow-md transition-all flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed bg-gradient-to-r from-brand-light to-brand-dark hover:from-brand-dark hover:to-brand-light hover:shadow-lg">
                            <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            <span x-text="createAccount"></span>
                        </button>
                        <button type="button" @click="prevStep()"
                            class="w-full py-3 text-sm font-bold text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            <span x-text="backStepLabel"></span>
                        </button>
                    </div>
                </div>

                {{-- Loading --}}
                <div x-show="loading" class="w-full py-3 text-sm font-bold text-white rounded-lg shadow-md bg-gray-400 flex items-center justify-center gap-2">
                    <svg class="animate-spin w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="pleaseWait"></span>
                </div>'''
content = content.replace(old_submit, new_buttons)

with open(path, 'w', encoding='utf-8') as f:
    f.write(content)

print('File updated successfully')

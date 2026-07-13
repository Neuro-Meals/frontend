path = r'f:\nitro\Nitromeals\resources\views\auth\register.blade.php'
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

old = '''            <h2 class="text-2xl font-extrabold text-gray-900">{{ __('Create Account') }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ __('Get started with') }} {{ config('app.name', 'Nutrio Meals') }}</p>
        </div>'''

new = '''            <h2 class="text-2xl font-extrabold text-gray-900">{{ __('Create Account') }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ __('Get started with') }} {{ config('app.name', 'Nutrio Meals') }}</p>

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

if old in content:
    content = content.replace(old, new)
    print('Step indicator added')
else:
    print('Old header block not found')

with open(path, 'w', encoding='utf-8') as f:
    f.write(content)

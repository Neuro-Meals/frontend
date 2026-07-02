<section id="calculator" class="py-20 lg:py-28 bg-gray-50 dark:bg-gray-950 transition-colors duration-300 relative overflow-hidden">
    {{-- Decorative --}}
    <div class="absolute top-1/4 -left-32 w-80 h-80 rounded-full bg-brand-light/5 blur-3xl"></div>
    <div class="absolute bottom-1/4 -right-32 w-80 h-80 rounded-full bg-[#173327]/5 blur-3xl"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        {{-- Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12 scroll-reveal">
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-light/10 text-brand-light text-xs font-bold uppercase tracking-wider mb-4">{{ __('Nutrition Tool') }}</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">{{ __('Calculate Your Nutrition Needs') }}</h2>
            <p class="text-gray-600 dark:text-gray-300 text-lg">{{ __('Get a quick estimate of your daily calorie and protein targets.') }}</p>
        </div>

        {{-- Calculator card --}}
        <div class="calc-card scroll-reveal relative rounded-3xl bg-white dark:bg-gray-800 shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            {{-- Top gradient bar --}}
            <div class="h-1.5 bg-gradient-to-r from-[#173327] to-[#6E7A25]"></div>

            <div class="p-6 sm:p-8 lg:p-10">
                {{-- Inputs --}}
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">{{ __('Age') }}</label>
                        <div class="relative">
                            <input type="number" id="calc-age" placeholder="25" min="1" max="120" class="calc-input w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:border-brand-light focus:ring-2 focus:ring-brand-light/20 outline-none transition-all text-sm font-medium">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">yrs</span>
                        </div>
                    </div>
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">{{ __('Weight') }}</label>
                        <div class="relative">
                            <input type="number" id="calc-weight" placeholder="70" min="1" max="300" class="calc-input w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:border-brand-light focus:ring-2 focus:ring-brand-light/20 outline-none transition-all text-sm font-medium">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">kg</span>
                        </div>
                    </div>
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">{{ __('Height') }}</label>
                        <div class="relative">
                            <input type="number" id="calc-height" placeholder="175" min="50" max="250" class="calc-input w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:border-brand-light focus:ring-2 focus:ring-brand-light/20 outline-none transition-all text-sm font-medium">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">cm</span>
                        </div>
                    </div>
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">{{ __('Goal') }}</label>
                        <select id="calc-goal" class="calc-input w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:border-brand-light focus:ring-2 focus:ring-brand-light/20 outline-none transition-all text-sm font-medium">
                            <option value="loss">{{ __('Weight Loss') }}</option>
                            <option value="gain">{{ __('Muscle Gain') }}</option>
                            <option value="maintain">{{ __('Maintenance') }}</option>
                        </select>
                    </div>
                </div>

                {{-- Gender toggle --}}
                <div class="flex items-center justify-center gap-3 mb-6">
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Gender') }}</span>
                    <div class="inline-flex rounded-xl border border-gray-200 dark:border-gray-600 overflow-hidden">
                        <button class="calc-gender px-5 py-2 text-xs font-bold transition-all bg-brand-light text-white" data-gender="male">{{ __('Male') }}</button>
                        <button class="calc-gender px-5 py-2 text-xs font-bold transition-all bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400" data-gender="female">{{ __('Female') }}</button>
                    </div>
                </div>

                {{-- Calculate button --}}
                <button id="calc-btn" class="w-full py-3.5 text-sm font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] hover:shadow-lg hover:shadow-brand-light/30 hover:-translate-y-0.5 rounded-xl transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    {{ __('Calculate My Plan') }}
                </button>

                {{-- Results --}}
                <div id="calc-results" class="mt-8 grid sm:grid-cols-3 gap-4" style="display: none;">
                    <div class="calc-result p-5 rounded-2xl bg-gradient-to-br from-brand-light/10 to-brand-light/5 border border-brand-light/20 text-center">
                        <div class="w-10 h-10 mx-auto rounded-full bg-brand-light/15 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-brand-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">{{ __('Daily Calories') }}</p>
                        <p id="result-calories" class="text-3xl font-extrabold text-brand-light">0</p>
                        <p class="text-xs text-gray-400 mt-1">{{ __('kcal / day') }}</p>
                    </div>
                    <div class="calc-result p-5 rounded-2xl bg-gradient-to-br from-[#173327]/10 to-[#173327]/5 border border-[#173327]/20 text-center">
                        <div class="w-10 h-10 mx-auto rounded-full bg-[#173327]/15 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-[#173327] dark:text-brand-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 11h.01M12 11h.01M16 11h.01M21 16v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2a4 4 0 004 4h10a4 4 0 004-4z"/></svg>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">{{ __('Protein Target') }}</p>
                        <p id="result-protein" class="text-3xl font-extrabold text-[#173327] dark:text-brand-light">0</p>
                        <p class="text-xs text-gray-400 mt-1">{{ __('grams / day') }}</p>
                    </div>
                    <div class="calc-result p-5 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800 border border-gray-200 dark:border-gray-600 text-center">
                        <div class="w-10 h-10 mx-auto rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">{{ __('Recommended Plan') }}</p>
                        <p id="result-plan" class="text-xl font-extrabold text-gray-900 dark:text-white">-</p>
                        <p class="text-xs text-gray-400 mt-1">{{ __('best match') }}</p>
                    </div>
                </div>

                {{-- Default placeholder results --}}
                <div id="calc-placeholder" class="mt-8 grid sm:grid-cols-3 gap-4">
                    <div class="p-5 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-dashed border-gray-200 dark:border-gray-700 text-center">
                        <div class="w-10 h-10 mx-auto rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mb-1 uppercase tracking-wider">{{ __('Daily Calories') }}</p>
                        <p class="text-3xl font-extrabold text-gray-300 dark:text-gray-700">---</p>
                        <p class="text-xs text-gray-400 mt-1">{{ __('kcal / day') }}</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-dashed border-gray-200 dark:border-gray-700 text-center">
                        <div class="w-10 h-10 mx-auto rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 11h.01M12 11h.01M16 11h.01M21 16v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2a4 4 0 004 4h10a4 4 0 004-4z"/></svg>
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mb-1 uppercase tracking-wider">{{ __('Protein Target') }}</p>
                        <p class="text-3xl font-extrabold text-gray-300 dark:text-gray-700">---</p>
                        <p class="text-xs text-gray-400 mt-1">{{ __('grams / day') }}</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-dashed border-gray-200 dark:border-gray-700 text-center">
                        <div class="w-10 h-10 mx-auto rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mb-1 uppercase tracking-wider">{{ __('Recommended Plan') }}</p>
                        <p class="text-xl font-extrabold text-gray-300 dark:text-gray-700">---</p>
                        <p class="text-xs text-gray-400 mt-1">{{ __('best match') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    (function() {
        let selectedGender = 'male';

        document.querySelectorAll('.calc-gender').forEach(btn => {
            btn.addEventListener('click', function() {
                selectedGender = this.dataset.gender;
                document.querySelectorAll('.calc-gender').forEach(b => {
                    b.classList.remove('bg-brand-light', 'text-white');
                    b.classList.add('bg-gray-50', 'dark:bg-gray-900', 'text-gray-500', 'dark:text-gray-400');
                });
                this.classList.remove('bg-gray-50', 'dark:bg-gray-900', 'text-gray-500', 'dark:text-gray-400');
                this.classList.add('bg-brand-light', 'text-white');
            });
        });

        document.getElementById('calc-btn').addEventListener('click', function() {
            const age = parseFloat(document.getElementById('calc-age').value) || 0;
            const weight = parseFloat(document.getElementById('calc-weight').value) || 0;
            const height = parseFloat(document.getElementById('calc-height').value) || 0;
            const goal = document.getElementById('calc-goal').value;

            if (!age || !weight || !height) {
                document.querySelectorAll('.calc-input').forEach(el => {
                    if (!el.value && el.tagName !== 'SELECT') {
                        el.classList.add('border-red-400', 'ring-2', 'ring-red-200');
                        setTimeout(() => el.classList.remove('border-red-400', 'ring-2', 'ring-red-200'), 2000);
                    }
                });
                return;
            }

            let bmr;
            if (selectedGender === 'male') {
                bmr = 10 * weight + 6.25 * height - 5 * age + 5;
            } else {
                bmr = 10 * weight + 6.25 * height - 5 * age - 161;
            }

            let calories, protein, plan;
            if (goal === 'loss') {
                calories = Math.round(bmr * 1.3 * 0.8);
                protein = Math.round(weight * 2.0);
                plan = 'Weight Loss';
            } else if (goal === 'gain') {
                calories = Math.round(bmr * 1.5 * 1.1);
                protein = Math.round(weight * 2.2);
                plan = 'Muscle Gain';
            } else {
                calories = Math.round(bmr * 1.4);
                protein = Math.round(weight * 1.6);
                plan = 'Maintenance';
            }

            document.getElementById('calc-placeholder').style.display = 'none';
            const results = document.getElementById('calc-results');
            results.style.display = 'grid';

            animateNumber('result-calories', calories);
            animateNumber('result-protein', protein);
            document.getElementById('result-plan').textContent = plan;

            results.querySelectorAll('.calc-result').forEach((el, i) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(15px)';
                setTimeout(() => {
                    el.style.transition = 'all 0.5s ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, i * 150);
            });
        });

        function animateNumber(id, target) {
            const el = document.getElementById(id);
            let current = 0;
            const steps = 30;
            const increment = target / steps;
            let step = 0;
            const timer = setInterval(() => {
                step++;
                current += increment;
                if (step >= steps) {
                    clearInterval(timer);
                    el.textContent = target.toLocaleString();
                } else {
                    el.textContent = Math.round(current).toLocaleString();
                }
            }, 25);
        }
    })();
</script>

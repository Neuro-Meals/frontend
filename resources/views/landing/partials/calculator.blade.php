<section id="calculator" class="py-20 lg:py-28 bg-gray-50 dark:bg-gray-950 transition-colors duration-300 relative overflow-hidden">
    {{-- Decorative --}}
    <div class="absolute top-1/4 -left-32 w-80 h-80 rounded-full bg-brand-light/5 blur-3xl"></div>
    <div class="absolute bottom-1/4 -right-32 w-80 h-80 rounded-full bg-[#173327]/5 blur-3xl"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        {{-- Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12 scroll-reveal">
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-light/10 text-brand-light text-xs font-bold uppercase tracking-wider mb-4">{{ __('Nutrition Tool') }}</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">{{ __('Calculate Your Nutrition Needs') }}</h2>
            <p class="text-gray-600 dark:text-gray-300 text-lg">{{ __('Get a quick estimate of your daily calorie and macro targets.') }}</p>
        </div>

        {{-- Calculator card --}}
        <div class="calc-card scroll-reveal relative rounded-3xl bg-white dark:bg-gray-800 shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            {{-- Top gradient bar --}}
            <div class="h-1.5 bg-gradient-to-r from-[#173327] to-[#6E7A25]"></div>

            <div class="p-6 sm:p-8 lg:p-10">
                {{-- Inputs --}}
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">{{ __('Age') }}</label>
                        <div class="relative">
                            <input type="number" id="calc-age" placeholder="25" min="10" max="120" class="calc-input w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:border-brand-light focus:ring-2 focus:ring-brand-light/20 outline-none transition-all text-sm font-medium">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">yrs</span>
                        </div>
                    </div>
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">{{ __('Weight') }}</label>
                        <div class="relative">
                            <input type="number" id="calc-weight" placeholder="70" min="20" max="300" class="calc-input w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:border-brand-light focus:ring-2 focus:ring-brand-light/20 outline-none transition-all text-sm font-medium">
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
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">{{ __('Gender') }}</label>
                        <div class="inline-flex rounded-xl border border-gray-200 dark:border-gray-600 overflow-hidden w-full">
                            <button class="calc-gender flex-1 px-4 py-3 text-xs font-bold transition-all bg-brand-light text-white" data-gender="male">{{ __('Male') }}</button>
                            <button class="calc-gender flex-1 px-4 py-3 text-xs font-bold transition-all bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400" data-gender="female">{{ __('Female') }}</button>
                        </div>
                    </div>
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">{{ __('Body Fat') }} <span class="normal-case text-gray-400 font-medium">({{ __('optional') }})</span></label>
                        <div class="relative">
                            <input type="number" id="calc-bodyfat" placeholder="20" min="1" max="60" step="0.1" class="calc-input w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:border-brand-light focus:ring-2 focus:ring-brand-light/20 outline-none transition-all text-sm font-medium">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">%</span>
                        </div>
                    </div>
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">{{ __('Daily Steps') }}</label>
                        <div class="relative">
                            <input type="number" id="calc-steps" placeholder="8000" min="0" max="50000" class="calc-input w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:border-brand-light focus:ring-2 focus:ring-brand-light/20 outline-none transition-all text-sm font-medium">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">steps</span>
                        </div>
                    </div>
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">{{ __('Goal') }}</label>
                        <select id="calc-goal" class="calc-input w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:border-brand-light focus:ring-2 focus:ring-brand-light/20 outline-none transition-all text-sm font-medium">
                            <option value="loss">{{ __('Weight Loss') }}</option>
                            <option value="cut">{{ __('Cutting') }}</option>
                            <option value="maintain">{{ __('Maintenance') }}</option>
                            <option value="skinnyfat">{{ __('Skinny Fat Recomp') }}</option>
                            <option value="gain">{{ __('Weight Gain') }}</option>
                            <option value="bulk">{{ __('Muscle Gain') }}</option>
                        </select>
                    </div>
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">{{ __('Experience') }}</label>
                        <select id="calc-subgoal" class="calc-input w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:border-brand-light focus:ring-2 focus:ring-brand-light/20 outline-none transition-all text-sm font-medium">
                            <option value="beginner">{{ __('Beginner') }}</option>
                            <option value="advanced">{{ __('Advanced') }}</option>
                        </select>
                    </div>
                </div>

                {{-- Calculate button --}}
                <button id="calc-btn" class="w-full py-3.5 text-sm font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] hover:shadow-lg hover:shadow-brand-light/30 hover:-translate-y-0.5 rounded-xl transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    {{ __('Calculate My Plan') }}
                </button>

                {{-- Results --}}
                <div id="calc-results" class="mt-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4" style="display: none;">
                    <div class="calc-result p-4 rounded-2xl bg-gradient-to-br from-brand-light/10 to-brand-light/5 border border-brand-light/20 text-center">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">{{ __('Maintenance') }}</p>
                        <p id="result-maintenance" class="text-2xl font-extrabold text-brand-light">0</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ __('kcal') }}</p>
                    </div>
                    <div class="calc-result p-4 rounded-2xl bg-gradient-to-br from-[#173327]/10 to-[#173327]/5 border border-[#173327]/20 text-center">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">{{ __('Goal Calories') }}</p>
                        <p id="result-calories" class="text-2xl font-extrabold text-[#173327] dark:text-brand-light">0</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ __('kcal') }}</p>
                    </div>
                    <div class="calc-result p-4 rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-center">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">{{ __('Protein') }}</p>
                        <p id="result-protein" class="text-2xl font-extrabold text-gray-900 dark:text-white">0</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ __('g') }}</p>
                    </div>
                    <div class="calc-result p-4 rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-center">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">{{ __('Fat') }}</p>
                        <p id="result-fat" class="text-2xl font-extrabold text-gray-900 dark:text-white">0</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ __('g') }}</p>
                    </div>
                    <div class="calc-result p-4 rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-center">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">{{ __('Carbs') }}</p>
                        <p id="result-carbs" class="text-2xl font-extrabold text-gray-900 dark:text-white">0</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ __('g') }}</p>
                    </div>
                    <div class="calc-result p-4 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800 border border-gray-200 dark:border-gray-600 text-center">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">{{ __('Recommended Plan') }}</p>
                        <p id="result-plan" class="text-lg font-extrabold text-gray-900 dark:text-white leading-tight">-</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ __('best match') }}</p>
                    </div>
                </div>

                {{-- Default placeholder results --}}
                <div id="calc-placeholder" class="mt-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-dashed border-gray-200 dark:border-gray-700 text-center">
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-1 uppercase tracking-wider">{{ __('Maintenance') }}</p>
                        <p class="text-2xl font-extrabold text-gray-300 dark:text-gray-700">---</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ __('kcal') }}</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-dashed border-gray-200 dark:border-gray-700 text-center">
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-1 uppercase tracking-wider">{{ __('Goal Calories') }}</p>
                        <p class="text-2xl font-extrabold text-gray-300 dark:text-gray-700">---</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ __('kcal') }}</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-dashed border-gray-200 dark:border-gray-700 text-center">
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-1 uppercase tracking-wider">{{ __('Protein') }}</p>
                        <p class="text-2xl font-extrabold text-gray-300 dark:text-gray-700">---</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ __('g') }}</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-dashed border-gray-200 dark:border-gray-700 text-center">
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-1 uppercase tracking-wider">{{ __('Fat') }}</p>
                        <p class="text-2xl font-extrabold text-gray-300 dark:text-gray-700">---</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ __('g') }}</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-dashed border-gray-200 dark:border-gray-700 text-center">
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-1 uppercase tracking-wider">{{ __('Carbs') }}</p>
                        <p class="text-2xl font-extrabold text-gray-300 dark:text-gray-700">---</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ __('g') }}</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-dashed border-gray-200 dark:border-gray-700 text-center">
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-1 uppercase tracking-wider">{{ __('Recommended Plan') }}</p>
                        <p class="text-lg font-extrabold text-gray-300 dark:text-gray-700">---</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ __('best match') }}</p>
                    </div>
                </div>

                <p class="text-center text-xs text-gray-400 mt-6">{{ __('Results are an estimate based on the Saudi Fit methodology; body fat percentage gives the most accurate result.') }}</p>
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

        const CONFIG = {
            stepTiers: [
                { max: 3999, factor: 1.2 },
                { max: 7999, factor: 1.375 },
                { max: 11999, factor: 1.55 },
                { max: 14999, factor: 1.725 },
                { max: Infinity, factor: 1.9 },
            ],
            goalAdjust: {
                gain: { beginner: 0.10, advanced: 0.08 },
                bulk: { beginner: 0.15, advanced: 0.10 },
                loss: { beginner: -0.20, advanced: -0.20 },
                cut: { beginner: -0.15, advanced: -0.20 },
                maintain: { beginner: 0.00, advanced: 0.00 },
                skinnyfat: { beginner: -0.10, advanced: -0.10 },
            },
            proteinPerKgLBM: 2.2,
            proteinPerKgBW: 1.8,
            fatPctOfCalories: 0.25,
            minFatPerKg: 0.6,
        };

        function stepFactor(steps) {
            return CONFIG.stepTiers.find(t => steps <= t.max).factor;
        }

        function calculateNutrition({ gender, age, weight, height, bodyFat, steps, goal, subGoal }) {
            const hasBodyFat = bodyFat != null && bodyFat > 0;
            let bmr, lbm = null;
            if (hasBodyFat) {
                lbm = weight * (1 - bodyFat / 100);
                bmr = 370 + 21.6 * lbm;
            } else {
                bmr = gender === 'male'
                    ? 10 * weight + 6.25 * height - 5 * age + 5
                    : 10 * weight + 6.25 * height - 5 * age - 161;
            }

            const maintenance = bmr * stepFactor(steps);
            const goalCalories = maintenance * (1 + CONFIG.goalAdjust[goal][subGoal]);

            const protein = hasBodyFat
                ? lbm * CONFIG.proteinPerKgLBM
                : weight * CONFIG.proteinPerKgBW;

            const fat = Math.max(
                (goalCalories * CONFIG.fatPctOfCalories) / 9,
                weight * CONFIG.minFatPerKg
            );

            const carbs = Math.max((goalCalories - protein * 4 - fat * 9) / 4, 0);

            return {
                maintenance: Math.round(maintenance),
                goalCalories: Math.round(goalCalories),
                protein: Math.round(protein),
                fat: Math.round(fat),
                carbs: Math.round(carbs),
            };
        }

        const planLabels = {
            loss: '{{ __('Weight Loss') }}',
            cut: '{{ __('Cutting') }}',
            maintain: '{{ __('Maintenance') }}',
            skinnyfat: '{{ __('Skinny Fat Recomp') }}',
            gain: '{{ __('Weight Gain') }}',
            bulk: '{{ __('Muscle Gain') }}',
        };

        document.getElementById('calc-btn').addEventListener('click', function() {
            const age = parseFloat(document.getElementById('calc-age').value) || 0;
            const weight = parseFloat(document.getElementById('calc-weight').value) || 0;
            const height = parseFloat(document.getElementById('calc-height').value) || 0;
            const bodyFat = parseFloat(document.getElementById('calc-bodyfat').value) || null;
            const steps = parseFloat(document.getElementById('calc-steps').value) || 0;
            const goal = document.getElementById('calc-goal').value;
            const subGoal = document.getElementById('calc-subgoal').value;

            if (!age || !weight || !height || !steps) {
                document.querySelectorAll('.calc-input').forEach(el => {
                    if (!el.value && el.tagName !== 'SELECT') {
                        el.classList.add('border-red-400', 'ring-2', 'ring-red-200');
                        setTimeout(() => el.classList.remove('border-red-400', 'ring-2', 'ring-red-200'), 2000);
                    }
                });
                return;
            }

            const result = calculateNutrition({
                gender: selectedGender,
                age,
                weight,
                height,
                bodyFat,
                steps,
                goal,
                subGoal,
            });

            document.getElementById('calc-placeholder').style.display = 'none';
            const results = document.getElementById('calc-results');
            results.style.display = 'grid';

            animateNumber('result-maintenance', result.maintenance);
            animateNumber('result-calories', result.goalCalories);
            animateNumber('result-protein', result.protein);
            animateNumber('result-fat', result.fat);
            animateNumber('result-carbs', result.carbs);
            document.getElementById('result-plan').textContent = planLabels[goal] || goal;

            results.querySelectorAll('.calc-result').forEach((el, i) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(15px)';
                setTimeout(() => {
                    el.style.transition = 'all 0.5s ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, i * 100);
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

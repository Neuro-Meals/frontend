<section id="how-it-works" class="py-20 lg:py-28 bg-gray-50 dark:bg-gray-950 transition-colors duration-300 relative overflow-hidden">
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-light/30 to-transparent"></div>
    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-light/30 to-transparent"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12 scroll-reveal">
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-light/10 text-brand-light text-xs font-bold uppercase tracking-wider mb-4">Process / Simple Workflow</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">How It Works</h2>
            <p class="text-gray-600 dark:text-gray-300 text-lg">Your journey to healthier eating starts in four simple steps. Choose your path below.</p>
        </div>

        {{-- Role Switcher --}}
        <div class="flex justify-center mb-12 scroll-reveal">
            <div class="inline-flex gap-2 p-1.5 bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700">
                <button class="hiw-role-btn active px-6 py-3 text-sm font-bold rounded-xl transition-all duration-300" data-target="subscriber">Subscriber</button>
                <button class="hiw-role-btn px-6 py-3 text-sm font-bold rounded-xl transition-all duration-300" data-target="fitness">Fitness Enthusiast</button>
            </div>
        </div>

        {{-- Subscriber Workflow --}}
        <div class="hiw-workflow active" id="workflow-subscriber">
            <div class="flex flex-col sm:flex-row sm:flex-wrap lg:flex-nowrap gap-6 lg:gap-0 items-center lg:items-stretch">
                @php
                    $subscriberSteps = [
                        ['num' => '1', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'title' => 'Choose Your Goal', 'text' => 'Select Weight Loss, Muscle Gain, or Maintenance based on your body targets.'],
                        ['num' => '2', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'title' => 'Select Your Plan', 'text' => 'Pick a daily or monthly subscription that fits your schedule and budget.'],
                        ['num' => '3', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'title' => 'Receive Fresh Meals', 'text' => 'Meals prepared fresh every morning and delivered to your doorstep on time.'],
                        ['num' => '4', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Track Progress', 'text' => 'Monitor calories, macros, and fitness goals through your dashboard.'],
                    ];
                @endphp

                @foreach ($subscriberSteps as $index => $step)
                    <div class="hiw-step-card scroll-reveal relative w-full sm:w-[calc(50%-12px)] lg:w-full lg:flex-1" style="transition-delay: {{ $index * 100 }}ms;">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-brand-light/30 dark:hover:border-brand-light/30 hover:-translate-y-1 transition-all duration-300 h-full">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="relative flex-shrink-0">
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#173327] to-[#6E7A25] text-white flex items-center justify-center shadow-lg">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"></path></svg>
                                    </div>
                                    <div class="absolute -top-2 -right-2 w-7 h-7 rounded-full bg-white dark:bg-gray-900 border-2 border-brand-light text-brand-light flex items-center justify-center text-xs font-extrabold shadow-md">{{ $step['num'] }}</div>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $step['title'] }}</h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ $step['text'] }}</p>
                        </div>
                    </div>
                    @if (!$loop->last)
                        <div class="hidden lg:flex items-center justify-center flex-shrink-0 w-10 scroll-reveal" style="transition-delay: {{ $index * 100 + 50 }}ms;">
                            <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 border-2 border-brand-light/30 flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-brand-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Fitness Enthusiast Workflow --}}
        <div class="hiw-workflow hidden" id="workflow-fitness">
            <div class="flex flex-col sm:flex-row sm:flex-wrap lg:flex-nowrap gap-6 lg:gap-0 items-center lg:items-stretch">
                @php
                    $fitnessSteps = [
                        ['num' => '1', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'title' => 'Calculate Macros', 'text' => 'Use our health calculator to determine your daily calorie and macro needs.'],
                        ['num' => '2', 'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4', 'title' => 'Customize Meals', 'text' => 'Adjust protein, carbs, and fat ratios to match your workout and recovery needs.'],
                        ['num' => '3', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Stay Consistent', 'text' => 'Never miss a meal. Daily delivery ensures you fuel your body on schedule.'],
                        ['num' => '4', 'icon' => 'M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z', 'title' => 'Achieve Results', 'text' => 'Watch your performance improve with consistent, calculated nutrition.'],
                    ];
                @endphp

                @foreach ($fitnessSteps as $index => $step)
                    <div class="hiw-step-card scroll-reveal relative w-full sm:w-[calc(50%-12px)] lg:w-full lg:flex-1" style="transition-delay: {{ $index * 100 }}ms;">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-brand-light/30 dark:hover:border-brand-light/30 hover:-translate-y-1 transition-all duration-300 h-full">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="relative flex-shrink-0">
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#173327] to-[#6E7A25] text-white flex items-center justify-center shadow-lg">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"></path></svg>
                                    </div>
                                    <div class="absolute -top-2 -right-2 w-7 h-7 rounded-full bg-white dark:bg-gray-900 border-2 border-brand-light text-brand-light flex items-center justify-center text-xs font-extrabold shadow-md">{{ $step['num'] }}</div>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $step['title'] }}</h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ $step['text'] }}</p>
                        </div>
                    </div>
                    @if (!$loop->last)
                        <div class="hidden lg:flex items-center justify-center flex-shrink-0 w-10 scroll-reveal" style="transition-delay: {{ $index * 100 + 50 }}ms;">
                            <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 border-2 border-brand-light/30 flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-brand-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
    (function() {
        const btns = document.querySelectorAll('.hiw-role-btn');
        const workflows = document.querySelectorAll('.hiw-workflow');

        btns.forEach(btn => {
            btn.addEventListener('click', function() {
                const target = this.dataset.target;

                btns.forEach(b => {
                    b.classList.remove('active', 'bg-gradient-to-r', 'from-[#173327]', 'to-[#6E7A25]', 'text-white', 'shadow-md');
                    b.classList.add('text-gray-600', 'dark:text-gray-300');
                });
                this.classList.add('active', 'bg-gradient-to-r', 'from-[#173327]', 'to-[#6E7A25]', 'text-white', 'shadow-md');
                this.classList.remove('text-gray-600', 'dark:text-gray-300');

                workflows.forEach(w => {
                    w.classList.add('hidden');
                    w.classList.remove('active');
                });
                const targetEl = document.getElementById('workflow-' + target);
                targetEl.classList.remove('hidden');
                targetEl.classList.add('active');

                {{-- Re-trigger scroll animations --}}
                targetEl.querySelectorAll('.hiw-step-card').forEach((card, i) => {
                    card.classList.remove('is-visible');
                    setTimeout(() => card.classList.add('is-visible'), 50 + i * 100);
                });
            });
        });

        {{-- Set initial active button style --}}
        const firstBtn = document.querySelector('.hiw-role-btn.active');
        if (firstBtn) {
            firstBtn.classList.add('bg-gradient-to-r', 'from-[#173327]', 'to-[#6E7A25]', 'text-white', 'shadow-md');
            firstBtn.classList.remove('text-gray-600', 'dark:text-gray-300');
        }
    })();
</script>

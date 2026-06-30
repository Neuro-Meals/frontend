<section id="how-it-works" class="py-20 lg:py-28 bg-gray-50 dark:bg-gray-950 transition-colors duration-300 relative overflow-hidden">
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-light/30 to-transparent"></div>
    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-light/30 to-transparent"></div>

    {{-- Decorative blurs --}}
    <div class="absolute top-1/3 -left-32 w-72 h-72 rounded-full bg-brand-light/5 blur-3xl"></div>
    <div class="absolute bottom-1/3 -right-32 w-72 h-72 rounded-full bg-brand-light/5 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        {{-- Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12 scroll-reveal">
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-light/10 text-brand-light text-xs font-bold uppercase tracking-wider mb-4">Process / Simple Workflow</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">How It Works</h2>
            <p class="text-gray-600 dark:text-gray-300 text-lg">Your journey to healthier eating starts in four simple steps. Choose your path below.</p>
        </div>

        {{-- Role Switcher --}}
        <div class="flex justify-center mb-10 scroll-reveal">
            <div class="inline-flex gap-2 p-1.5 bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700">
                <button class="hiw-role-btn active px-6 py-3 text-sm font-bold rounded-xl transition-all duration-300" data-target="subscriber">Subscriber</button>
                <button class="hiw-role-btn px-6 py-3 text-sm font-bold rounded-xl transition-all duration-300" data-target="fitness">Fitness Enthusiast</button>
            </div>
        </div>

        {{-- Subscriber Workflow --}}
        <div class="hiw-workflow active" id="workflow-subscriber">
            <div class="hiw-carousel relative scroll-reveal">
                <div class="hiw-track flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-4 -mx-4 px-4" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <style>.hiw-track::-webkit-scrollbar { display: none; }</style>

                    @php
                        $subscriberSteps = [
                            ['num' => '01', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'title' => 'Choose Your Goal', 'text' => 'Select Weight Loss, Muscle Gain, or Maintenance based on your body targets and fitness aspirations.', 'color' => 'from-blue-500 to-cyan-500'],
                            ['num' => '02', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'title' => 'Select Your Plan', 'text' => 'Pick a daily or monthly subscription that fits your schedule, budget, and dietary preferences.', 'color' => 'from-[#033133] to-[#259B00]'],
                            ['num' => '03', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'title' => 'Receive Fresh Meals', 'text' => 'Meals prepared fresh every morning and delivered to your doorstep on time, every time.', 'color' => 'from-amber-500 to-orange-500'],
                            ['num' => '04', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Track Progress', 'text' => 'Monitor calories, macros, and fitness goals through your personalized dashboard in real-time.', 'color' => 'from-purple-500 to-pink-500'],
                        ];
                    @endphp

                    @foreach ($subscriberSteps as $index => $step)
                        <div class="hiw-card flex-shrink-0 w-[85%] sm:w-[60%] lg:w-[31.5%] snap-center">
                            <div class="relative h-full rounded-3xl overflow-hidden bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-lg hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 group">
                                {{-- Top gradient bar --}}
                                <div class="h-1.5 bg-gradient-to-r {{ $step['color'] }}"></div>

                                <div class="p-8">
                                    {{-- Big icon --}}
                                    <div class="relative mb-6">
                                        <div class="w-20 h-20 rounded-3xl bg-gradient-to-br {{ $step['color'] }} text-white flex items-center justify-center shadow-xl group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $step['icon'] }}"></path></svg>
                                        </div>
                                        {{-- Number badge --}}
                                        <div class="absolute -top-3 -right-3 w-10 h-10 rounded-full bg-white dark:bg-gray-900 border-2 border-brand-light text-brand-light flex items-center justify-center text-sm font-extrabold shadow-lg">{{ $step['num'] }}</div>
                                    </div>

                                    {{-- Title --}}
                                    <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-3">{{ $step['title'] }}</h3>

                                    {{-- Text --}}
                                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-5">{{ $step['text'] }}</p>

                                    {{-- Step indicator dots --}}
                                    <div class="flex items-center gap-1.5">
                                        @for ($i = 0; $i < 4; $i++)
                                            <div class="h-1.5 rounded-full transition-all {{ $i === $index ? 'w-8 bg-brand-light' : 'w-1.5 bg-gray-200 dark:bg-gray-600' }}"></div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Nav arrows --}}
                <button class="hiw-prev absolute top-1/2 -left-3 -translate-y-1/2 z-10 w-11 h-11 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-brand-light hover:border-brand-light/30 transition-all hidden lg:flex">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button class="hiw-next absolute top-1/2 -right-3 -translate-y-1/2 z-10 w-11 h-11 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-brand-light hover:border-brand-light/30 transition-all hidden lg:flex">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>

                {{-- Dots --}}
                <div class="hiw-dots flex justify-center gap-2 mt-6"></div>
            </div>
        </div>

        {{-- Fitness Enthusiast Workflow --}}
        <div class="hiw-workflow hidden" id="workflow-fitness">
            <div class="hiw-carousel relative scroll-reveal">
                <div class="hiw-track flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-4 -mx-4 px-4" style="scrollbar-width: none; -ms-overflow-style: none;">
                    @php
                        $fitnessSteps = [
                            ['num' => '01', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'title' => 'Calculate Macros', 'text' => 'Use our health calculator to determine your daily calorie and macro needs based on your goals.', 'color' => 'from-red-500 to-rose-600'],
                            ['num' => '02', 'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4', 'title' => 'Customize Meals', 'text' => 'Adjust protein, carbs, and fat ratios to match your workout intensity and recovery needs.', 'color' => 'from-[#033133] to-[#259B00]'],
                            ['num' => '03', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Stay Consistent', 'text' => 'Never miss a meal. Daily delivery ensures you fuel your body on schedule, every single day.', 'color' => 'from-amber-500 to-orange-500'],
                            ['num' => '04', 'icon' => 'M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z', 'title' => 'Achieve Results', 'text' => 'Watch your performance improve with consistent, calculated nutrition tailored to your training.', 'color' => 'from-teal-500 to-emerald-600'],
                        ];
                    @endphp

                    @foreach ($fitnessSteps as $index => $step)
                        <div class="hiw-card flex-shrink-0 w-[85%] sm:w-[60%] lg:w-[31.5%] snap-center">
                            <div class="relative h-full rounded-3xl overflow-hidden bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-lg hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 group">
                                <div class="h-1.5 bg-gradient-to-r {{ $step['color'] }}"></div>

                                <div class="p-8">
                                    <div class="relative mb-6">
                                        <div class="w-20 h-20 rounded-3xl bg-gradient-to-br {{ $step['color'] }} text-white flex items-center justify-center shadow-xl group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $step['icon'] }}"></path></svg>
                                        </div>
                                        <div class="absolute -top-3 -right-3 w-10 h-10 rounded-full bg-white dark:bg-gray-900 border-2 border-brand-light text-brand-light flex items-center justify-center text-sm font-extrabold shadow-lg">{{ $step['num'] }}</div>
                                    </div>

                                    <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-3">{{ $step['title'] }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-5">{{ $step['text'] }}</p>

                                    <div class="flex items-center gap-1.5">
                                        @for ($i = 0; $i < 4; $i++)
                                            <div class="h-1.5 rounded-full transition-all {{ $i === $index ? 'w-8 bg-brand-light' : 'w-1.5 bg-gray-200 dark:bg-gray-600' }}"></div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="hiw-prev absolute top-1/2 -left-3 -translate-y-1/2 z-10 w-11 h-11 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-brand-light hover:border-brand-light/30 transition-all hidden lg:flex">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button class="hiw-next absolute top-1/2 -right-3 -translate-y-1/2 z-10 w-11 h-11 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-brand-light hover:border-brand-light/30 transition-all hidden lg:flex">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>

                <div class="hiw-dots flex justify-center gap-2 mt-6"></div>
            </div>
        </div>
    </div>
</section>

<script>
    (function() {
        const btns = document.querySelectorAll('.hiw-role-btn');
        const workflows = document.querySelectorAll('.hiw-workflow');
        let autoScrollTimers = {};

        function initCarousel(workflowEl) {
            const track = workflowEl.querySelector('.hiw-track');
            const prevBtn = workflowEl.querySelector('.hiw-prev');
            const nextBtn = workflowEl.querySelector('.hiw-next');
            const dotsContainer = workflowEl.querySelector('.hiw-dots');
            if (!track) return;

            const cards = track.querySelectorAll('.hiw-card');
            const cardWidth = () => cards[0].offsetWidth + 24;
            const workflowId = workflowEl.id;

            function scrollBy(dir) {
                track.scrollBy({ left: dir * cardWidth(), behavior: 'smooth' });
            }

            if (prevBtn) prevBtn.onclick = () => { scrollBy(-1); resetAutoScroll(); };
            if (nextBtn) nextBtn.onclick = () => { scrollBy(1); resetAutoScroll(); };

            function updateDots() {
                const maxScroll = track.scrollWidth - track.clientWidth;
                if (maxScroll <= 10) {
                    dotsContainer.style.display = 'none';
                    if (prevBtn) prevBtn.style.display = 'none';
                    if (nextBtn) nextBtn.style.display = 'none';
                    return;
                }
                const scrollPercent = maxScroll > 0 ? track.scrollLeft / maxScroll : 0;
                const totalDots = Math.ceil(maxScroll / cardWidth()) + 1;
                dotsContainer.innerHTML = '';
                for (let i = 0; i < totalDots; i++) {
                    const dot = document.createElement('button');
                    dot.className = 'hiw-dot w-2 h-2 rounded-full transition-all duration-300';
                    const activeThreshold = i / totalDots;
                    const nextThreshold = (i + 1) / totalDots;
                    if (scrollPercent >= activeThreshold && scrollPercent < nextThreshold) {
                        dot.classList.add('bg-brand-light', 'w-6');
                    } else {
                        dot.classList.add('bg-gray-300', 'dark:bg-gray-600');
                    }
                    dot.addEventListener('click', () => {
                        track.scrollTo({ left: i * cardWidth(), behavior: 'smooth' });
                        resetAutoScroll();
                    });
                    dotsContainer.appendChild(dot);
                }
            }

            function autoScroll() {
                const maxScroll = track.scrollWidth - track.clientWidth;
                if (maxScroll <= 10) return;
                const currentScroll = track.scrollLeft;
                if (currentScroll >= maxScroll - 10) {
                    track.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    track.scrollBy({ left: cardWidth(), behavior: 'smooth' });
                }
            }

            function startAutoScroll() {
                stopAutoScroll();
                autoScrollTimers[workflowId] = setInterval(autoScroll, 3500);
            }

            function stopAutoScroll() {
                if (autoScrollTimers[workflowId]) {
                    clearInterval(autoScrollTimers[workflowId]);
                    delete autoScrollTimers[workflowId];
                }
            }

            function resetAutoScroll() {
                stopAutoScroll();
                startAutoScroll();
            }

            track.addEventListener('scroll', updateDots);
            track.addEventListener('mouseenter', stopAutoScroll);
            track.addEventListener('mouseleave', startAutoScroll);
            window.addEventListener('resize', updateDots);
            updateDots();
            startAutoScroll();
        }

        btns.forEach(btn => {
            btn.addEventListener('click', function() {
                const target = this.dataset.target;

                btns.forEach(b => {
                    b.classList.remove('active', 'bg-gradient-to-r', 'from-[#033133]', 'to-[#259B00]', 'text-white', 'shadow-md');
                    b.classList.add('text-gray-600', 'dark:text-gray-300');
                });
                this.classList.add('active', 'bg-gradient-to-r', 'from-[#033133]', 'to-[#259B00]', 'text-white', 'shadow-md');
                this.classList.remove('text-gray-600', 'dark:text-gray-300');

                workflows.forEach(w => {
                    w.classList.add('hidden');
                    w.classList.remove('active');
                    const wId = w.id;
                    if (autoScrollTimers[wId]) {
                        clearInterval(autoScrollTimers[wId]);
                        delete autoScrollTimers[wId];
                    }
                });
                const targetEl = document.getElementById('workflow-' + target);
                targetEl.classList.remove('hidden');
                targetEl.classList.add('active');
                initCarousel(targetEl);
            });
        });

        const firstBtn = document.querySelector('.hiw-role-btn.active');
        if (firstBtn) {
            firstBtn.classList.add('bg-gradient-to-r', 'from-[#033133]', 'to-[#259B00]', 'text-white', 'shadow-md');
            firstBtn.classList.remove('text-gray-600', 'dark:text-gray-300');
        }

        workflows.forEach(w => {
            if (!w.classList.contains('hidden')) {
                initCarousel(w);
            }
        });
    })();
</script>

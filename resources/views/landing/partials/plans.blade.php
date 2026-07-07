<section id="plans" class="py-20 lg:py-28 bg-white dark:bg-gray-900 transition-colors duration-300 relative overflow-hidden">
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-light/30 to-transparent"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12 scroll-reveal">
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-light/10 text-brand-light text-xs font-bold uppercase tracking-wider mb-4">{{ __('Pricing Plans') }}</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">{{ __('Choose Your Perfect Plan') }}</h2>
            <p class="text-gray-600 dark:text-gray-300 text-lg">{{ __('Nutrition plans tailored to your body and lifestyle. Cancel anytime.') }}</p>
        </div>

        {{-- Carousel --}}
        <div class="plans-carousel relative scroll-reveal">
            {{-- Track --}}
            <div class="plans-track flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-4 -mx-4 px-4" style="scrollbar-width: none; -ms-overflow-style: none;">
                <style>.plans-track::-webkit-scrollbar { display: none; }</style>

                @forelse (($plans ?? []) as $index => $plan)
                    @php
                        $planFeatures = $plan['features'] ?? [];
                        if (!is_array($planFeatures) || empty($planFeatures)) {
                            $planFeatures = [
                                (!empty($plan['duration']) ? __('Duration: ') . $plan['duration'] : __('Flexible duration')),
                                (!empty($plan['meals']) ? $plan['meals'] . ' ' . __('meals included') : __('Daily meal coverage')),
                                (!empty($plan['calories']) ? $plan['calories'] . ' ' . __('kcal target') : __('Calorie-optimized')),
                                (!empty($plan['subscribers']) ? $plan['subscribers'] . ' ' . __('active subscribers') : __('Join our members')),
                            ];
                        }
                        $isPopular = $plan['popular'] ?? ($index === 0);
                        $accentColor = $plan['color'] ?? '#6E7A25';
                    @endphp
                    <div class="plan-card flex-shrink-0 w-[85%] sm:w-[45%] lg:w-[31.5%] snap-center">
                        <div class="relative h-full rounded-3xl overflow-hidden {{ $isPopular ? 'shadow-2xl ring-2' : 'shadow-lg' }} bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
                             style="{{ $isPopular ? 'ring-color: ' . $accentColor : '' }}">
                            {{-- Popular badge --}}
                            @if ($isPopular)
                                <div class="absolute top-0 left-0 right-0 text-white text-center py-2 text-xs font-bold uppercase tracking-wider" style="background: linear-gradient(135deg, #173327, {{ $accentColor }});">
                                    {{ __('Most Popular') }}
                                </div>
                            @endif

                            <div class="p-8 {{ $isPopular ? 'pt-14' : '' }}">
                                {{-- Name --}}
                                <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $plan['name'] ?? __('Plan') }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ $plan['description'] ?? $plan['desc'] ?? '' }}</p>

                                {{-- Price --}}
                                <div class="flex items-baseline gap-1 mb-6">
                                    <span class="text-4xl font-extrabold {{ $isPopular ? 'text-[#6E7A25]' : 'text-gray-900 dark:text-white' }}" style="{{ $isPopular ? 'color: ' . $accentColor . ' !important;' : '' }}">{{ number_format($plan['price'] ?? 0, 0) }}</span>
                                    <span class="text-lg font-medium text-gray-500 dark:text-gray-400">SAR / {{ $plan['duration'] ?? __('period') }}</span>
                                </div>

                                {{-- Divider --}}
                                <div class="h-px bg-gradient-to-r from-transparent via-gray-200 dark:via-gray-700 to-transparent mb-6"></div>

                                {{-- Features --}}
                                <ul class="space-y-3 mb-8">
                                    @foreach ($planFeatures as $feature)
                                        <li class="flex items-start gap-3 text-sm text-gray-600 dark:text-gray-300">
                                            <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5" style="background: {{ $accentColor }}1a;">
                                                <svg class="w-3 h-3" style="color: {{ $accentColor }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>

                                {{-- CTA --}}
                                <a href="{{ route('register') }}" class="block w-full py-3.5 text-center text-sm font-bold rounded-xl transition-all duration-300 {{ $isPopular ? 'text-white hover:shadow-lg hover:-translate-y-0.5' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-600' }}"
                                   style="{{ $isPopular ? 'background: linear-gradient(135deg, #173327, ' . $accentColor . ');' : '' }}">
                                    {{ __('Subscribe Now') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="w-full text-center py-12">
                        <p class="text-gray-500 dark:text-gray-400">{{ __('No plans available at the moment. Please check back soon.') }}</p>
                        <a href="{{ route('register') }}" class="mt-4 inline-block px-6 py-3 text-sm font-bold text-white rounded-xl" style="background: linear-gradient(135deg, #173327, #6E7A25);">{{ __('Get Started') }}</a>
                    </div>
                @endforelse
            </div>

            {{-- Navigation arrows --}}
            <button class="plans-nav-prev absolute top-1/2 -left-3 -translate-y-1/2 z-10 w-11 h-11 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-brand-light hover:border-brand-light/30 transition-all hidden lg:flex">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button class="plans-nav-next absolute top-1/2 -right-3 -translate-y-1/2 z-10 w-11 h-11 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-brand-light hover:border-brand-light/30 transition-all hidden lg:flex">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>

            {{-- Dot indicators --}}
            <div class="plans-dots flex justify-center gap-2 mt-6"></div>
        </div>
    </div>
</section>

<script>
    (function() {
        const track = document.querySelector('.plans-track');
        const prevBtn = document.querySelector('.plans-nav-prev');
        const nextBtn = document.querySelector('.plans-nav-next');
        const dotsContainer = document.querySelector('.plans-dots');
        if (!track) return;

        const cards = track.querySelectorAll('.plan-card');
        const cardWidth = () => cards[0].offsetWidth + 24;
        let autoScrollTimer = null;
        let isHovered = false;

        function scrollBy(dir) {
            track.scrollBy({ left: dir * cardWidth(), behavior: 'smooth' });
        }

        if (prevBtn) prevBtn.addEventListener('click', () => scrollBy(-1));
        if (nextBtn) nextBtn.addEventListener('click', () => scrollBy(1));

        function updateDots() {
            const maxScroll = track.scrollWidth - track.clientWidth;
            if (maxScroll <= 10) {
                dotsContainer.style.display = 'none';
                if (prevBtn) prevBtn.style.display = 'none';
                if (nextBtn) nextBtn.style.display = 'none';
                return;
            }
            const scrollPercent = track.scrollLeft / maxScroll;
            const totalDots = Math.ceil(maxScroll / cardWidth()) + 1;
            dotsContainer.innerHTML = '';
            for (let i = 0; i < totalDots; i++) {
                const dot = document.createElement('button');
                dot.className = 'plans-dot w-2 h-2 rounded-full transition-all duration-300';
                const activeThreshold = i / totalDots;
                const nextThreshold = (i + 1) / totalDots;
                if (scrollPercent >= activeThreshold && scrollPercent < nextThreshold) {
                    dot.classList.add('bg-brand-light', 'w-6');
                } else {
                    dot.classList.add('bg-gray-300', 'dark:bg-gray-600');
                }
                dot.addEventListener('click', () => {
                    track.scrollTo({ left: i * cardWidth(), behavior: 'smooth' });
                });
                dotsContainer.appendChild(dot);
            }
        }

        function autoScroll() {
            if (isHovered) return;
            const maxScroll = track.scrollWidth - track.clientWidth;
            if (maxScroll <= 10) return;
            const currentScroll = track.scrollLeft;
            if (currentScroll >= maxScroll - 5) {
                track.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                scrollBy(1);
            }
        }

        function startAutoScroll() {
            stopAutoScroll();
            autoScrollTimer = setInterval(autoScroll, 3500);
        }

        function stopAutoScroll() {
            if (autoScrollTimer) {
                clearInterval(autoScrollTimer);
                autoScrollTimer = null;
            }
        }

        track.addEventListener('scroll', updateDots);
        window.addEventListener('resize', updateDots);

        const carousel = document.querySelector('.plans-carousel');
        if (carousel) {
            carousel.addEventListener('mouseenter', () => { isHovered = true; stopAutoScroll(); });
            carousel.addEventListener('mouseleave', () => { isHovered = false; startAutoScroll(); });
        }

        updateDots();
        startAutoScroll();
    })();
</script>

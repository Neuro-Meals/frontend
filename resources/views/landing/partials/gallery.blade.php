<section id="gallery" class="py-20 lg:py-28 bg-gray-50 dark:bg-gray-950 transition-colors duration-300 relative overflow-hidden">
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-light/30 to-transparent"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <div class="text-center max-w-3xl mx-auto scroll-reveal">
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-light/10 text-brand-light text-xs font-bold uppercase tracking-wider mb-4">{{ __('Our Menu') }}</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">{{ __('Fresh Meals Every Day') }}</h2>
            <p class="text-gray-600 dark:text-gray-300 text-lg">{{ __('Breakfast, lunch, dinner, and snacks prepared by expert chefs with the finest ingredients.') }}</p>
        </div>
    </div>

    {{-- Carousel --}}
    <div class="meals-carousel relative scroll-reveal">
        {{-- Track --}}
        <div class="meals-track flex gap-5 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-4 -mx-4 px-4" style="scrollbar-width: none; -ms-overflow-style: none;">
            <style>.meals-track::-webkit-scrollbar { display: none; }</style>

            @php
                $meals = [
                    ['img' => 'meals/protein/chicken/IMG_3964.JPG', 'name' => __('Chicken'), 'cat' => __('Chicken')],
                    ['img' => 'meals/protein/seafood/IMG_4065.JPG', 'name' => __('Seafood'), 'cat' => __('Seafood')],
                    ['img' => 'meals/protein/meat/IMG_4044.JPG', 'name' => __('Meat'), 'cat' => __('Meat')],
                    ['img' => 'meals/protein/chicken/IMG_3984.JPG', 'name' => __('Chicken'), 'cat' => __('Chicken')],
                    ['img' => 'meals/protein/seafood/IMG_4067.JPG', 'name' => __('Seafood'), 'cat' => __('Seafood')],
                    ['img' => 'meals/protein/chicken/IMG_4002.JPG', 'name' => __('Chicken'), 'cat' => __('Chicken')],
                    ['img' => 'meals/protein/meat/IMG_4047.JPG', 'name' => __('Meat'), 'cat' => __('Meat')],
                    ['img' => 'meals/protein/chicken/IMG_4013.JPG', 'name' => __('Chicken'), 'cat' => __('Chicken')],
                    ['img' => 'meals/protein/seafood/IMG_4068.JPG', 'name' => __('Seafood'), 'cat' => __('Seafood')],
                    ['img' => 'meals/protein/chicken/IMG_4051.JPG', 'name' => __('Chicken'), 'cat' => __('Chicken')],
                    ['img' => 'meals/protein/meat/IMG_4050.JPG', 'name' => __('Meat'), 'cat' => __('Meat')],
                    ['img' => 'meals/protein/chicken/IMG_4072.JPG', 'name' => __('Chicken'), 'cat' => __('Chicken')],
                    ['img' => 'meals/protein/seafood/IMG_4069.JPG', 'name' => __('Seafood'), 'cat' => __('Seafood')],
                    ['img' => 'meals/protein/chicken/IMG_4076.JPG', 'name' => __('Chicken'), 'cat' => __('Chicken')],
                    ['img' => 'meals/protein/seafood/IMG_4071.JPG', 'name' => __('Seafood'), 'cat' => __('Seafood')],
                    ['img' => 'meals/protein/chicken/IMG_4081.JPG', 'name' => __('Chicken'), 'cat' => __('Chicken')],
                ];
            @endphp

            @foreach ($meals as $meal)
                <div class="meal-card flex-shrink-0 w-[45%] sm:w-[22%] lg:w-[14%] snap-center">
                    <div class="relative rounded-2xl overflow-hidden shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group aspect-square">
                        <img src="{{ asset('images/' . $meal['img']) }}" alt="{{ $meal['name'] }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        {{-- Category badge --}}
                        <div class="absolute bottom-2 {{ app()->getLocale() === 'ar' ? 'right-2' : 'left-2' }}">
                            <span class="inline-block px-2.5 py-1 rounded-full bg-black/50 backdrop-blur-sm text-white text-[10px] font-bold">{{ $meal['cat'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Navigation arrows --}}
        <button class="meals-nav-prev absolute top-[40%] -left-3 -translate-y-1/2 z-10 w-11 h-11 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-brand-light hover:border-brand-light/30 transition-all hidden lg:flex">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <button class="meals-nav-next absolute top-[40%] -right-3 -translate-y-1/2 z-10 w-11 h-11 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-brand-light hover:border-brand-light/30 transition-all hidden lg:flex">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>

        {{-- Dot indicators --}}
        <div class="meals-dots flex justify-center gap-2 mt-6"></div>
    </div>
</section>

<script>
    (function() {
        const track = document.querySelector('.meals-track');
        const prevBtn = document.querySelector('.meals-nav-prev');
        const nextBtn = document.querySelector('.meals-nav-next');
        const dotsContainer = document.querySelector('.meals-dots');
        if (!track) return;

        const cards = track.querySelectorAll('.meal-card');
        const cardWidth = () => cards[0].offsetWidth + 20;
        const visibleCards = () => Math.round(track.clientWidth / cardWidth());
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
                dot.className = 'meals-dot w-2 h-2 rounded-full transition-all duration-300';
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
            autoScrollTimer = setInterval(autoScroll, 3000);
        }

        function stopAutoScroll() {
            if (autoScrollTimer) {
                clearInterval(autoScrollTimer);
                autoScrollTimer = null;
            }
        }

        track.addEventListener('scroll', updateDots);
        window.addEventListener('resize', updateDots);

        const carousel = document.querySelector('.meals-carousel');
        if (carousel) {
            carousel.addEventListener('mouseenter', () => { isHovered = true; stopAutoScroll(); });
            carousel.addEventListener('mouseleave', () => { isHovered = false; startAutoScroll(); });
        }

        updateDots();
        startAutoScroll();
    })();
</script>

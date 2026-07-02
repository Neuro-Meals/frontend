<section class="relative min-h-screen flex items-center pt-28 pb-20 overflow-hidden">
    {{-- Rotating background images --}}
    <div class="absolute inset-0">
        @foreach (['236.jpg', '10293.jpg', '2148903563.jpg', '2151186402.jpg', '2151186417.jpg'] as $img)
            <img src="{{ asset('images/' . $img) }}" alt="Healthy meals" class="hero-bg-image absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 {{ $loop->first ? 'opacity-100' : 'opacity-0' }}" data-index="{{ $loop->index }}">
        @endforeach
    </div>

    {{-- Gradient fade overlay - white on left (behind text) fading to transparent on right --}}
    <div class="absolute inset-0 bg-gradient-to-r from-white via-white/70 to-transparent dark:from-gray-900 dark:via-gray-900/70 dark:to-transparent"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-white/60 via-transparent to-transparent dark:from-gray-900/60 dark:via-transparent dark:to-transparent"></div>

    {{-- Subtle dot pattern --}}
    <div class="absolute inset-0 opacity-5 dark:opacity-10" style="background-image: radial-gradient(rgba(110,122,37,0.3) 1px, transparent 1px); background-size: 32px 32px;"></div>
    <canvas id="hero-particles" class="absolute inset-0 w-full h-full opacity-40 dark:opacity-25 pointer-events-none"></canvas>

    {{-- Content - text on left with backdrop --}}
    <div class="relative max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="flex justify-start">
            <div class="w-full lg:max-w-2xl text-left scroll-reveal relative">
                {{-- Backdrop behind text for readability --}}
                <div class="absolute -inset-6 rounded-3xl bg-white/40 dark:bg-gray-900/40 backdrop-blur-md border border-white/20 dark:border-gray-700/20"></div>

                <div class="relative">
                    <a href="#plans" class="inline-flex justify-between items-center py-1 px-1 pr-4 mb-7 text-sm text-gray-700 bg-gray-100/90 backdrop-blur-sm rounded-full dark:bg-gray-800/90 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        <span class="text-xs bg-brand-light rounded-full text-white px-4 py-1.5 mr-3">New</span>
                        <span class="text-sm font-medium">Nutrio Plans are live in Riyadh</span>
                        <svg class="ml-2 w-5 h-5 text-gray-700 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    </a>

                    <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white drop-shadow-lg">
                        Healthy Meals Designed<br>
                        <span class="text-brand-light" id="hero-rotating-text">For Your Goals</span>
                    </h1>
                    <p class="mb-8 text-lg font-normal text-gray-700 dark:text-gray-200 max-w-lg drop-shadow">
                        Premium Saudi meal subscriptions with perfectly calculated macros delivered to your door.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 mb-10">
                        <a href="#plans" class="inline-flex justify-center items-center py-4 px-8 text-lg font-bold text-center text-white rounded-xl bg-gradient-to-r from-[#173327] to-[#6E7A25] hover:from-[#025C5F] hover:to-[#1E8A00] focus:ring-4 focus:ring-[#6E7A25]/40 dark:focus:ring-[#6E7A25]/60 shadow-lg shadow-brand-light/30 hover:shadow-xl hover:shadow-brand-light/40 hover:-translate-y-0.5 transition-all duration-300">
                            Start Your Plan
                            <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </a>
                        <a href="#gallery" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-gray-900 rounded-lg border border-gray-300 bg-white/80 backdrop-blur-sm hover:bg-white focus:ring-4 focus:ring-gray-200 dark:text-white dark:border-gray-700 dark:bg-gray-800/80 dark:hover:bg-gray-700 dark:focus:ring-gray-800 transition-colors">
                            <svg class="mr-2 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path></svg>
                            View Meals
                        </a>
                    </div>

                    <div class="flex flex-wrap items-center gap-6 text-gray-600 dark:text-gray-300">
                        <span class="font-semibold uppercase tracking-wider text-xs">Trusted Partners</span>
                        @foreach (['MacroFit', 'FitFuel', 'DailyFresh'] as $partner)
                            <span class="flex items-center gap-1.5 text-sm font-semibold hover:text-brand-light dark:hover:text-brand-light transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                {{ $partner }}
                            </span>
                        @endforeach
                    </div>

                    {{-- Image dots indicator --}}
                    <div class="flex items-center gap-2 mt-8">
                        @foreach (['236.jpg', '10293.jpg', '2148903563.jpg', '2151186402.jpg', '2151186417.jpg'] as $img)
                            <button class="hero-dot w-2 h-2 rounded-full bg-gray-400/60 dark:bg-gray-500/60 hover:bg-brand-light transition-colors {{ $loop->first ? 'hero-dot-active bg-brand-light w-6' : '' }}" data-index="{{ $loop->index }}"></button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Rotating text + background image animation script --}}
<script>
    (function() {
        {{-- Rotating text --}}
        const words = ['For Your Goals', 'For Your Body', 'For Your Health', 'For Your Lifestyle'];
        const textEl = document.getElementById('hero-rotating-text');
        if (textEl) {
            let textIndex = 0;
            function rotateText() {
                textEl.style.opacity = '0';
                textEl.style.transform = 'translateY(10px)';
                textEl.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                setTimeout(() => {
                    textIndex = (textIndex + 1) % words.length;
                    textEl.textContent = words[textIndex];
                    textEl.style.opacity = '1';
                    textEl.style.transform = 'translateY(0)';
                }, 300);
            }
            setInterval(rotateText, 3000);
        }

        {{-- Rotating background images --}}
        const images = document.querySelectorAll('.hero-bg-image');
        const dots = document.querySelectorAll('.hero-dot');
        let imgIndex = 0;

        function rotateImage() {
            images.forEach(img => img.classList.remove('opacity-100'));
            images.forEach(img => img.classList.add('opacity-0'));

            dots.forEach(dot => {
                dot.classList.remove('hero-dot-active', 'bg-brand-light', 'w-6');
                dot.classList.add('bg-gray-400/60', 'w-2');
            });

            imgIndex = (imgIndex + 1) % images.length;
            images[imgIndex].classList.remove('opacity-0');
            images[imgIndex].classList.add('opacity-100');

            dots[imgIndex].classList.remove('bg-gray-400/60', 'w-2');
            dots[imgIndex].classList.add('hero-dot-active', 'bg-brand-light', 'w-6');
        }

        setInterval(rotateImage, 4000);

        {{-- Click on dots to jump to image --}}
        dots.forEach(dot => {
            dot.addEventListener('click', function() {
                const idx = parseInt(this.dataset.index);
                images.forEach(img => {
                    img.classList.remove('opacity-100');
                    img.classList.add('opacity-0');
                });
                dots.forEach(d => {
                    d.classList.remove('hero-dot-active', 'bg-brand-light', 'w-6');
                    d.classList.add('bg-gray-400/60', 'w-2');
                });
                images[idx].classList.remove('opacity-0');
                images[idx].classList.add('opacity-100');
                dots[idx].classList.remove('bg-gray-400/60', 'w-2');
                dots[idx].classList.add('hero-dot-active', 'bg-brand-light', 'w-6');
                imgIndex = idx;
            });
        });
    })();
</script>

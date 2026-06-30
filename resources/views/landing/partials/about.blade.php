<section id="about" class="relative py-20 lg:py-28 bg-white dark:bg-gray-900 transition-colors duration-300 overflow-hidden">
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-light/30 to-transparent"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            {{-- Image Gallery --}}
            <div class="scroll-reveal relative order-2 lg:order-1">
                <div class="absolute -inset-4 bg-gradient-to-br from-brand-light/20 to-brand-dark/20 rounded-3xl blur-2xl"></div>
                <div class="relative grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="col-span-2 md:col-span-2 row-span-2">
                        <img src="{{ asset('images/236.jpg') }}" alt="Fresh healthy meal" class="w-full h-full min-h-[280px] object-cover rounded-2xl shadow-xl hover:scale-[1.02] transition-transform duration-500">
                    </div>
                    <img src="{{ asset('images/10293.jpg') }}" alt="Prepared meal" class="w-full h-40 md:h-44 object-cover rounded-2xl shadow-lg hover:scale-[1.02] transition-transform duration-500">
                    <img src="{{ asset('images/2148903563.jpg') }}" alt="Nutritious dish" class="w-full h-40 md:h-44 object-cover rounded-2xl shadow-lg hover:scale-[1.02] transition-transform duration-500">
                    <img src="{{ asset('images/2151186402.jpg') }}" alt="Healthy food" class="w-full h-40 md:h-44 object-cover rounded-2xl shadow-lg hover:scale-[1.02] transition-transform duration-500">
                    <img src="{{ asset('images/2151186417.jpg') }}" alt="Gourmet meal" class="w-full h-40 md:h-44 object-cover rounded-2xl shadow-lg hover:scale-[1.02] transition-transform duration-500">
                </div>
            </div>

            {{-- Text --}}
            <div class="scroll-reveal scroll-reveal-delay-1 order-1 lg:order-2">
                <span class="inline-block px-4 py-1.5 rounded-full bg-brand-light/10 text-brand-light text-xs font-bold uppercase tracking-wider mb-4">About Us</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight mb-6">
                    Fueling Saudi Arabia With <span class="text-brand-light">Purposeful Nutrition</span>
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
                    Nutrio Meals was built for people who take their health seriously. We combine fresh, locally sourced ingredients with precise macro calculations to deliver meals that help you lose weight, build muscle, or maintain a balanced lifestyle.
                </p>
                <p class="text-gray-600 dark:text-gray-300 mb-8">
                    Whether you are an athlete, busy professional, or simply want to eat better, our nutrition team and chefs work together to make healthy eating effortless, delicious, and consistent.
                </p>

                <div class="grid sm:grid-cols-2 gap-4 mb-8">
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-lg bg-brand-light/10 text-brand-light">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">Fresh Daily</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Prepared every morning in Riyadh</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-lg bg-brand-light/10 text-brand-light">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">Macro Tracked</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Calories & nutrients calculated</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-lg bg-brand-light/10 text-brand-light">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">Saudi Made</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Locally sourced ingredients</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-lg bg-brand-light/10 text-brand-light">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">On Time</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Reliable delivery schedule</p>
                        </div>
                    </div>
                </div>

                <a href="#plans" class="inline-flex items-center gap-2 px-8 py-3.5 text-sm font-bold text-white bg-gradient-to-r from-brand-light to-brand-dark hover:from-brand-dark hover:to-brand-light rounded-xl shadow-lg hover:shadow-xl transition-all">
                    Explore Our Plans
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="absolute inset-0 opacity-5 dark:opacity-10" style="background-image: radial-gradient(rgba(37,155,0,0.3) 1px, transparent 1px); background-size: 32px 32px;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            {{-- Text --}}
            <div class="scroll-reveal">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-light/10 text-brand-light text-sm font-semibold mb-6">
                    <span class="w-2 h-2 rounded-full bg-brand-light animate-pulse"></span>
                    Premium Healthy Meals in Saudi Arabia
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 dark:text-white leading-tight mb-6">
                    Healthy Meals Designed <span class="text-brand-light">For Your Goals</span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-8 max-w-lg">
                    Premium Saudi meal subscriptions with perfectly calculated macros delivered to your door.
                </p>
                <div class="flex flex-wrap gap-4 mb-10">
                    <a href="#plans" class="px-8 py-3.5 text-sm font-bold text-white bg-gradient-to-r from-brand-light to-brand-dark hover:from-brand-dark hover:to-brand-light rounded-xl shadow-lg hover:shadow-xl transition-all">Start Your Plan</a>
                    <a href="#gallery" class="px-8 py-3.5 text-sm font-bold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-brand-light dark:hover:border-brand-light rounded-xl shadow-sm transition-all">View Meal Plans</a>
                </div>

                {{-- Macro badges --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 scroll-reveal scroll-reveal-delay-1">
                    @foreach (['Calories', 'Protein', 'Carbs', 'Fat'] as $macro)
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $macro }}</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">Tracked</p>
                        </div>
                    @endforeach
                </div>

                <p class="mt-6 text-sm text-gray-500 dark:text-gray-400">200+ Subscribers Expected | Fresh Daily Meals</p>
            </div>

            {{-- Image --}}
            <div class="scroll-reveal scroll-reveal-delay-2 relative">
                <div class="absolute -inset-4 bg-gradient-to-tr from-brand-light/20 to-brand-dark/20 rounded-3xl blur-2xl"></div>
                <img src="{{ asset('flat-abstract-background-pattern-vector_822782-866.jpg') }}" alt="Healthy meals" class="relative rounded-3xl shadow-2xl w-full object-cover h-[400px] lg:h-[500px]">
            </div>
        </div>
    </div>
</section>

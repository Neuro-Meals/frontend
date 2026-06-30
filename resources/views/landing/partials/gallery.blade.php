<section id="gallery" class="py-20 bg-gray-50 dark:bg-gray-800/50 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 scroll-reveal">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-4">Fresh Meals Every Day</h2>
            <p class="text-gray-600 dark:text-gray-300">Breakfast, lunch, dinner, and snacks prepared by expert chefs.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach (['Breakfast', 'Lunch', 'Dinner', 'Snacks'] as $index => $meal)
                <div class="scroll-reveal scroll-reveal-delay-{{ ($index % 4) + 1 }} group relative rounded-2xl overflow-hidden shadow-lg aspect-[4/3]">
                    <img src="{{ asset('flat-abstract-background-pattern-vector_822782-866.jpg') }}" alt="{{ $meal }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6">
                        <h3 class="text-xl font-bold text-white">{{ $meal }}</h3>
                        <p class="text-sm text-white/80">Fresh & balanced</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

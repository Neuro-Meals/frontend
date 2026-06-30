<section id="testimonials" class="py-20 bg-gray-50 dark:bg-gray-800/50 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 scroll-reveal">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-4">What Our Customers Say</h2>
            <p class="text-gray-600 dark:text-gray-300">Real results from real people in Saudi Arabia.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @php
                $reviews = [
                    ['name' => 'Ahmed Al-Farsi', 'role' => 'Fitness Coach', 'text' => 'The macro tracking and meal quality are exactly what I needed for my clients. Highly recommended.', 'stars' => 5],
                    ['name' => 'Sara Mohammed', 'role' => 'Working Professional', 'text' => 'Fresh meals delivered daily save me so much time. The taste is amazing and the portions are perfect.', 'stars' => 5],
                    ['name' => 'Khalid Nasser', 'role' => 'Gym Enthusiast', 'text' => 'Finally a meal plan that helps me gain muscle without eating boring food. Nutrio Meals is a game changer.', 'stars' => 5],
                ];
            @endphp

            @foreach ($reviews as $index => $review)
                <div class="scroll-reveal scroll-reveal-delay-{{ ($index % 3) + 1 }} p-8 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="flex gap-1 mb-4">
                        @for ($i = 0; $i < $review['stars']; $i++)
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">"{{ $review['text'] }}"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-brand-light/10 flex items-center justify-center text-brand-light font-bold">{{ substr($review['name'], 0, 1) }}</div>
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $review['name'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $review['role'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

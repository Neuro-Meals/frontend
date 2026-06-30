<section id="how-it-works" class="py-20 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 scroll-reveal">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-4">How It Works</h2>
            <p class="text-gray-600 dark:text-gray-300">Your journey to healthier eating starts in four simple steps.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $steps = [
                    ['num' => '01', 'title' => 'Choose Your Goal', 'text' => 'Weight Loss / Muscle Gain / Maintenance'],
                    ['num' => '02', 'title' => 'Select Your Meal Plan', 'text' => 'Daily or Monthly Subscription'],
                    ['num' => '03', 'title' => 'Receive Fresh Meals', 'text' => 'Delivered to your location'],
                    ['num' => '04', 'title' => 'Track Your Progress', 'text' => 'Monitor your nutrition goals'],
                ];
            @endphp

            @foreach ($steps as $index => $step)
                <div class="scroll-reveal scroll-reveal-delay-{{ ($index % 4) + 1 }} relative">
                    <div class="text-6xl font-extrabold text-brand-light/20 absolute -top-4 -left-2">{{ $step['num'] }}</div>
                    <div class="relative pt-8 pl-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-brand-light to-brand-dark text-white flex items-center justify-center font-bold text-lg mb-4 shadow-lg">
                            {{ $step['num'] }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $step['title'] }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $step['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

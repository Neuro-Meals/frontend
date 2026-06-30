<section id="plans" class="py-20 bg-gray-50 dark:bg-gray-800/50 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 scroll-reveal">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-4">Choose Your Perfect Plan</h2>
            <p class="text-gray-600 dark:text-gray-300">Nutrition plans tailored to your body and lifestyle.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @php
                $plans = [
                    ['name' => 'Weight Loss Plan', 'price' => 'From 299 SAR', 'features' => ['Low calorie meals', 'High protein', 'Fat burning support'], 'popular' => false],
                    ['name' => 'Muscle Gain Plan', 'price' => 'From 399 SAR', 'features' => ['High protein', 'Higher calories', 'Performance meals'], 'popular' => true],
                    ['name' => 'Maintenance Plan', 'price' => 'From 349 SAR', 'features' => ['Balanced nutrition', 'Healthy lifestyle', 'Flexible portions'], 'popular' => false],
                ];
            @endphp

            @foreach ($plans as $index => $plan)
                <div class="scroll-reveal scroll-reveal-delay-{{ ($index % 3) + 1 }} relative p-8 rounded-2xl {{ $plan['popular'] ? 'bg-gradient-to-br from-brand-light to-brand-dark text-white shadow-2xl scale-105' : 'bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 shadow-sm' }} transition-all hover:shadow-xl">
                    @if ($plan['popular'])
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 bg-white text-brand-dark text-xs font-bold rounded-full shadow">Most Popular</div>
                    @endif
                    <h3 class="text-2xl font-extrabold {{ $plan['popular'] ? 'text-white' : 'text-gray-900 dark:text-white' }} mb-2">{{ $plan['name'] }}</h3>
                    <p class="text-3xl font-bold {{ $plan['popular'] ? 'text-white/90' : 'text-brand-light' }} mb-6">{{ $plan['price'] }}</p>
                    <ul class="space-y-3 mb-8">
                        @foreach ($plan['features'] as $feature)
                            <li class="flex items-center gap-3 text-sm {{ $plan['popular'] ? 'text-white/90' : 'text-gray-600 dark:text-gray-300' }}">
                                <svg class="w-5 h-5 flex-shrink-0 {{ $plan['popular'] ? 'text-white' : 'text-brand-light' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                    <a href="#" class="block w-full py-3 text-center text-sm font-bold rounded-xl transition-all {{ $plan['popular'] ? 'bg-white text-brand-dark hover:bg-gray-100' : 'text-white bg-gradient-to-r from-brand-light to-brand-dark hover:from-brand-dark hover:to-brand-light' }}">Subscribe Now</a>
                </div>
            @endforeach
        </div>
    </div>
</section>

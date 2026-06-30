<section id="testimonials" class="bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-6">
        <div class="mx-auto max-w-screen-sm scroll-reveal">
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-light/10 text-brand-light text-xs font-bold uppercase tracking-wider mb-4">Testimonials</span>
            <h2 class="mb-4 text-3xl sm:text-4xl lg:text-5xl tracking-tight font-extrabold text-gray-900 dark:text-white">What Our Customers Say</h2>
            <p class="mb-8 font-light text-gray-500 lg:mb-16 sm:text-xl dark:text-gray-400">Real stories from real people across Saudi Arabia who transformed their lifestyle with Nutrio Meals.</p>
        </div>

        <div class="grid mb-8 lg:mb-12 lg:grid-cols-2">
            @php
                $testimonials = [
                    [
                        'title' => 'Best decision for my fitness journey',
                        'text' => "I've been using Nutrio Meals for 3 months now and the results are incredible. The macro tracking is spot on, and every meal is fresh, delicious, and perfectly portioned. It's like having a personal nutritionist and chef combined.",
                        'text2' => "The delivery is always on time, and the variety keeps me from getting bored. I've lost 8kg while still enjoying my food. Highly recommend to anyone serious about their health.",
                        'name' => 'Ahmed Al-Farsi',
                        'role' => 'Fitness Coach, Riyadh',
                        'initials' => 'AA',
                    ],
                    [
                        'title' => 'Saved me hours every single week',
                        'text' => "As a busy professional working 10+ hour days, cooking was always a struggle. Nutrio Meals completely changed that. I come home to fresh, healthy meals every day without lifting a finger.",
                        'text2' => "The subscription is flexible and the quality is consistently excellent. It's been a game-changer for my work-life balance and my health.",
                        'name' => 'Sara Mohammed',
                        'role' => 'Marketing Manager, Jeddah',
                        'initials' => 'SM',
                    ],
                    [
                        'title' => 'Perfect for muscle gain goals',
                        'text' => "I've tried multiple meal plans but none matched my protein needs like Nutrio Meals. The fitness plan gives me exactly the macros I need for building muscle, and the food actually tastes great.",
                        'text2' => "The dashboard makes it so easy to track my progress. I can see my calorie intake, macro breakdown, and even adjust my plan based on my workout schedule.",
                        'text3' => "This is the best investment I've made for my fitness journey.",
                        'name' => 'Khalid Nasser',
                        'role' => 'Gym Enthusiast, Dammam',
                        'initials' => 'KN',
                    ],
                    [
                        'title' => 'Quality that you can taste',
                        'text' => "What impressed me most is the quality of ingredients. Everything is fresh, locally sourced, and you can taste the difference. The meals feel like they're from a premium restaurant.",
                        'text2' => "Their customer service is outstanding too. Any questions about my plan or delivery are answered immediately. Truly a five-star experience from start to finish.",
                        'name' => 'Noura Abdullah',
                        'role' => 'Health Blogger, Riyadh',
                        'initials' => 'NA',
                    ],
                ];
            @endphp

            @foreach ($testimonials as $index => $t)
                <figure class="scroll-reveal flex flex-col justify-center items-center p-8 text-center bg-gray-50 border-b border-gray-200 {{ $index === 0 ? 'lg:border-r' : '' }} {{ $index === 2 ? 'lg:border-b-0 lg:border-r' : '' }} {{ $index === 3 ? 'border-b-0' : '' }} md:p-12 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100/50 dark:hover:bg-gray-800/50 transition-colors duration-300">
                    <blockquote class="mx-auto mb-8 max-w-2xl text-gray-500 dark:text-gray-400">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ $t['title'] }}</h3>
                        <p class="my-4">"{{ $t['text'] }}"</p>
                        @if (isset($t['text2']))
                            <p class="my-4">"{{ $t['text2'] }}"</p>
                        @endif
                        @if (isset($t['text3']))
                            <p class="my-4">"{{ $t['text3'] }}"</p>
                        @endif
                    </blockquote>
                    <figcaption class="flex justify-center items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#033133] to-[#259B00] text-white flex items-center justify-center text-sm font-bold shadow-md">{{ $t['initials'] }}</div>
                        <div class="space-y-0.5 font-medium dark:text-white text-left">
                            <div>{{ $t['name'] }}</div>
                            <div class="text-sm font-light text-gray-500 dark:text-gray-400">{{ $t['role'] }}</div>
                        </div>
                    </figcaption>
                </figure>
            @endforeach
        </div>

        <div class="text-center scroll-reveal">
            <a href="#" class="inline-flex items-center gap-2 py-2.5 px-6 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-xl border border-gray-200 hover:bg-gray-100 hover:text-brand-light focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 transition-colors">
                Show more...
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </a>
        </div>
    </div>
</section>

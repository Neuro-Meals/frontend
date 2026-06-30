<section id="calculator" class="py-20 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12 scroll-reveal">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-4">Calculate Your Nutrition Needs</h2>
            <p class="text-gray-600 dark:text-gray-300">Get a quick estimate of your daily calorie and protein targets.</p>
        </div>

        <div class="scroll-reveal p-8 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <input type="number" placeholder="Age" class="px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-brand-light focus:ring-2 focus:ring-brand-light/20 outline-none transition-all">
                <input type="number" placeholder="Weight (kg)" class="px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-brand-light focus:ring-2 focus:ring-brand-light/20 outline-none transition-all">
                <input type="number" placeholder="Height (cm)" class="px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-brand-light focus:ring-2 focus:ring-brand-light/20 outline-none transition-all">
                <select class="px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-brand-light focus:ring-2 focus:ring-brand-light/20 outline-none transition-all">
                    <option>Weight Loss</option>
                    <option>Muscle Gain</option>
                    <option>Maintenance</option>
                </select>
            </div>
            <button class="w-full py-3.5 text-sm font-bold text-white bg-gradient-to-r from-brand-light to-brand-dark hover:from-brand-dark hover:to-brand-light rounded-xl shadow-lg transition-all">Calculate My Plan</button>

            <div class="mt-8 grid sm:grid-cols-3 gap-4">
                <div class="p-4 rounded-xl bg-white dark:bg-gray-900 text-center border border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Calories</p>
                    <p class="text-2xl font-bold text-brand-light">2,100</p>
                </div>
                <div class="p-4 rounded-xl bg-white dark:bg-gray-900 text-center border border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Protein target</p>
                    <p class="text-2xl font-bold text-brand-light">160g</p>
                </div>
                <div class="p-4 rounded-xl bg-white dark:bg-gray-900 text-center border border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Recommended plan</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">Maintenance</p>
                </div>
            </div>
        </div>
    </div>
</section>

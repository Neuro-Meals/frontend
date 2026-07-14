@extends('layouts.landing')

@section('title', __('Terms & Conditions') . ' - ' . __('Nutrio Meals'))

@section('content')
@include('landing.partials.header')

{{-- Hero Section --}}
@include('pages.partials.hero', ['title' => __('Terms & Conditions'), 'description' => __('Please read these terms carefully before subscribing to our meal services.')])

@php
    $isAr = app()->getLocale() === 'ar';
    $steps = [
        [
            'num' => '01',
            'title' => $isAr ? 'إنشاء الحساب' : 'Account Setup',
            'desc' => $isAr ? 'تسجيل آمن وتوفير العنوان الصحيح والقيود الغذائية.' : 'Create your profile, provide delivery address and dietary needs.',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>',
        ],
        [
            'num' => '02',
            'title' => $isAr ? 'اختيار باقتك' : 'Choose Plan',
            'desc' => $isAr ? 'اختر خطة وجبات مصممة خصيصًا لأهدافك الرياضية.' : 'Select a customized healthy meal plan fitting your exact goals.',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>',
        ],
        [
            'num' => '03',
            'title' => $isAr ? 'دفع آمن' : 'Secure Payment',
            'desc' => $isAr ? 'معالجة مشفرة وآمنة بالكامل عبر بوابة Tap Payments.' : 'Processed securely and fully encrypted via Tap Payments gateway.',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-16.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
        ],
        [
            'num' => '04',
            'title' => $isAr ? 'توصيل مرن' : 'Fresh Delivery',
            'desc' => $isAr ? 'توصيل يومي طازج لباب منزلك مع إمكانية تتبع السائق.' : 'Daily fresh delivery right to your door with real-time tracking.',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
        ],
        [
            'num' => '05',
            'title' => $isAr ? 'إيقاف مرن' : 'Pause & Control',
            'desc' => $isAr ? 'تحكم كامل لإيقاف اشتراكك أو تعديل الوجبات في أي وقت.' : 'Full control to pause your subscription or change meals anytime.',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>',
        ],
    ];

    $sections = [
        [
            'id' => 'general-provisions',
            'title_en' => '1. General Provisions & Acceptance',
            'title_ar' => '1. الأحكام العامة وقبول الشروط',
            'points_en' => [
                'NutrioMeals operates an online platform providing premium meal subscription services in the Kingdom of Saudi Arabia.',
                'By accessing, registering, browsing, or buying subscriptions from NutrioMeals, you unconditionally agree to these Terms & Conditions, which constitute a binding legal agreement between you and NutrioMeals.',
                'These terms apply to all visitors, users, and customers of our platform. If you do not agree, you must immediately cease using the services.',
                'NutrioMeals reserves the right to modify, amend, or replace these Terms at any time without prior individual notice. Updated terms will automatically show an updated effective date and become active upon publishing.',
            ],
            'points_ar' => [
                'تدير نوتريو ميلز (NutrioMeals) منصة إلكترونية تقدم خدمات اشتراكات الوجبات الصحية الفاخرة في المملكة العربية السعودية.',
                'بمجرد وصولك إلى المنصة، أو التسجيل فيها، أو تصفحها، أو شراء الاشتراكات، فإنك توافق دون قيد أو شرط على هذه الشروط والأحكام، والتي تشكل اتفاقية قانونية ملزمة بينك وبين نوتريو ميلز.',
                'تسري هذه الشروط على جميع زوار ومستخدمي وعملاء منصتنا. إذا كنت لا توافق عليها، يجب عليك التوقف فورًا عن استخدام الخدمات.',
                'تحتفظ نوتريو ميلز بالحق في تعديل هذه الشروط أو تغييرها أو استبدالها in any time دون إشعار مسبق. ستظهر الشروط المحدثة تاريخ سريان جديد وتصبح فعالة فور نشرها.',
            ],
        ],
        [
            'id' => 'account-security',
            'title_en' => '2. User Accounts, Verification & Security',
            'title_ar' => '2. حسابات المستخدمين والتحقق والأمان',
            'points_en' => [
                'To purchase meal plans, you must create a personal account providing accurate, complete, and current information, including full name, phone number, email, and exact delivery address.',
                'You must verify your email address via the One-Time Password (OTP) verification system during registration.',
                'You are solely responsible for maintaining the confidentiality of your account credentials, login password, and security codes.',
                'Any activities occurring under your account are your sole legal responsibility. You must immediately notify NutrioMeals support of any unauthorized use or security breach.',
                'Accounts are strictly personal and non-transferable. Sharing account access with third parties is strictly prohibited and may result in immediate termination of service.',
            ],
            'points_ar' => [
                'لشراء خطط الوجبات، يجب عليك إنشاء حساب شخصي وتقديم معلومات دقيقة وكاملة ومحدثة، بما في ذلك الاسم الكامل ورقم الجوال والبريد الإلكتروني وعنوان التوصيل الدقيق.',
                'يجب عليك التحقق من بريدك الإلكتروني عبر نظام رمز التحقق لمرة واحدة (OTP) أثناء التسجيل.',
                'أنت مسؤول بمفردك عن الحفاظ على سرية بيانات اعتماد حسابك، وكلمة المرور الخاصة بك، ورموز الأمان.',
                'أي أنشطة تحدث تحت حسابك تقع على عاتق مسؤوليتك القانونية الكاملة. يجب عليك إبلاغ دعم نوتريو ميلز فورًا بأي استخدام غير مصرح به أو خرق أمني.',
                'الحسابات شخصية تمامًا وغير قابلة للتحويل. يمنع منعًا باتًا مشاركة الوصول للحساب مع أطراف ثالثة، وقد يؤدي ذلك لإنهاء الخدمة فورًا.',
            ],
        ],
        [
            'id' => 'subscriptions-billing',
            'title_en' => '3. Subscriptions, Billing & Tap Payments',
            'title_ar' => '3. الاشتراكات والفواتير ومدفوعات تاب',
            'points_en' => [
                'NutrioMeals offers goal-based healthy meal plans (Weight Loss, Muscle Gain, Maintenance) with subscription options of One, Two, or Three Months.',
                'Subscription fees must be paid in full in advance. No meals will be prepared or scheduled for delivery until payment is verified and confirmed.',
                'All payment card transactions are securely integrated and processed using Tap Payments, a fully certified PCI-DSS compliant gateway. NutrioMeals does not capture, store, or have access to your raw credit or debit card data.',
                'Subscription pricing is subject to change at our discretion. Any pricing modifications will only apply to new subscription cycles or renewals, never to an active, pre-paid subscription period.',
            ],
            'points_ar' => [
                'تقدم نوتريو ميلز خطط وجبات صحية مبنية على الأهداف (خسارة الوزن، زيادة العضلات، المحافظة على الوزن) مع خيارات اشتراك لمدة شهر، شهرين، أو ثلاثة أشهر.',
                'يجب دفع رسوم الاشتراك بالكامل مقدمًا. لن يتم تحضير أي وجبات أو جدولتها للتوصيل حتى يتم التحقق من عملية الدفع وتأكيدها.',
                'يتم دمج ومعالجة جميع معاملات بطاقات الدفع بأمان باستخدام بوابة مدفوعات تاب (Tap Payments)، وهي بوابة معتمدة بالكامل ومتوافقة مع معايير PCI-DSS. لا تقوم نوتريو ميلز بالتقاط أو تخزين أو الوصول إلى بيانات بطاقتك الائتمانية أو بطاقة الخصم.',
                'تخضع أسعار الاشتراكات للتغيير وفقًا لتقديرنا. ستنطبق أي تعديلات في الأسعار فقط على دورات الاشتراك الجديدة أو التجديدات، ولن تنطبق أبدًا على فترات الاشتراك النشطة المدفوعة مسبقًا.',
            ],
        ],
        [
            'id' => 'allergies-disclaimer',
            'title_en' => '4. Allergies, Health & Medical Disclaimer',
            'title_ar' => '4. الحساسية وإخلاء المسؤولية الصحية والطبية',
            'points_en' => [
                'NutrioMeals is a meal delivery provider and does not offer medical advice, diagnosis, or clinical treatment plans. Our food descriptions and macro estimates are for educational purposes.',
                'Customers MUST explicitly declare any food allergies, ingredients intolerance, or medical dietary restrictions in their profile and review meal details carefully before selecting.',
                'Our central kitchen handles a variety of ingredients, including nuts, dairy, soy, wheat, and gluten. While we enforce strict hygiene and cross-contamination guidelines, we cannot guarantee a 100% allergen-free environment.',
                'If you suffer from severe, life-threatening food allergies or medical conditions (e.g. chronic kidney disease, severe diabetes, celiac disease), you are advised to consult a qualified physician before subscribing.',
            ],
            'points_ar' => [
                'نوتريو ميلز هي شركة لتقديم وتوصيل الوجبات ولا تقدم نصائح طبية أو تشخيصات أو خطط علاج سريرية. توصيف الأطعمة وتقديرات السعرات الحرارية هي لأغراض تثقيفية وتوجيهية فقط.',
                'يجب على العملاء الإعلان بوضوح عن أي حساسية غذائية، أو عدم تحمل للمكونات، أو قيود غذائية طبية في ملفهم الشخصي ومراجعة تفاصيل الوجبات بعناية قبل اختيارها.',
                'يتعامل مطبخنا المركزي مع مجموعة متنوعة من المكونات، بما في ذلك المكسرات ومنتجات الألبان والصويا والقمح والجلوتين. على الرغم من تطبيقنا لإرشادات صارمة لمنع التلوث المتبادل، إلا أننا لا نضمن بيئة خالية تمامًا من مسببات الحساسية بنسبة 100٪.',
                'إذا كنت تعاني من حساسية غذائية شديدة مهددة للحياة أو حالات طبية حرجة (مثل الفشل الكلوي المزمن، السكري الشديد، السلياك)، ننصحك باستشارة طبيب مؤهل قبل الاشتراك.',
            ],
        ],
        [
            'id' => 'conduct-termination',
            'title_en' => '5. Platform Misuse, Abuse & Termination',
            'title_ar' => '5. إساءة استخدام المنصة والإنهاء',
            'points_en' => [
                'You agree to use our website, application, and services only for legitimate purposes. Prohibited actions include hacking, system scraping, introducing malicious code, or inputting false registration details.',
                'Fraudulent activities, payment chargebacks without basis, harassment of customer support or delivery drivers, or violation of these terms will lead to immediate account suspension or permanent termination.',
                'In the event of account suspension due to violation of conduct policies, NutrioMeals is not obligated to issue refunds for any outstanding subscription days.',
            ],
            'points_ar' => [
                'أنت توافق على استخدام موقعنا الإلكتروني وتطبيقنا وخدماتنا للأغراض المشروعة فقط. تشمل الإجراءات المحظورة القرصنة، أو كشط بيانات النظام، أو إدخال برامج ضارة، أو إدخال تفاصيل تسجيل كاذبة.',
                'الأنشطة الاحتيالية، أو استرداد المدفوعات دون وجه حق، أو مضايقة فريق الدعم أو سائقي التوصيل، أو انتهاك هذه الشروط سيؤدي إلى تعليق الحساب فورًا أو إنهائه نهائيًا.',
                'في حال تعليق الحساب بسبب انتهاك سياسات السلوك، فإن نوتريو ميلز غير ملزمة بإصدار أي استرداد مالي لأيام الاشتراك المتبقية.',
            ],
        ],
        [
            'id' => 'governing-law',
            'title_en' => '6. Governing Law & Jurisdiction',
            'title_ar' => '6. القانون المعمول به والاختصاص القضائي',
            'points_en' => [
                'These Terms and Conditions shall be governed by, interpreted, and construed in accordance with the laws and regulations of the Kingdom of Saudi Arabia.',
                'Any dispute, controversy, or claim arising out of or relating to these Terms, including validity, invalidity, breach, or termination, shall be subject to the exclusive jurisdiction of the competent courts in Riyadh, Saudi Arabia.',
            ],
            'points_ar' => [
                'تخضع هذه الشروط والأحكام وتُفسر وتُطبق وفقًا للقوانين والأنظمة المعمول بها في المملكة العربية السعودية.',
                'أي نزاع أو خلاف أو مطالبة تنشأ عن هذه الشروط أو تتعلق بها، بما في ذلك صحتها أو بطلانها أو خرقها أو إنهائها، تخضع للاختصاص القضائي الحصري للمحاكم المختصة في مدينة الرياض، المملكة العربية السعودية.',
            ],
        ],
    ];
@endphp

{{-- CSS Animations --}}
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeInUp {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
    .hover-card-trigger:hover .hover-card-target {
        transform: translateY(-5px);
        border-color: #6E7A25;
        box-shadow: 0 10px 25px -5px rgba(110, 122, 37, 0.15);
    }
</style>

{{-- Policy Process Stepper --}}
<section class="py-10 bg-gray-50 dark:bg-gray-800/40 border-y border-gray-100 dark:border-gray-700/50 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-center text-xs font-black uppercase tracking-widest text-[#6E7A25] mb-10">{{ $isAr ? 'رحلة الخدمة والالتزام بمدفوعات آمنة' : 'Service Journey & Secure Payment Standards' }}</h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-6">
            @foreach($steps as $index => $step)
            <div class="hover-card-trigger relative bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-850 hover-card-target transition-all duration-350 ease-out animate-fadeInUp" style="animation-delay: {{ $index * 0.1 }}s">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-3xl font-black text-[#6E7A25]/20 dark:text-[#6E7A25]/10 font-mono">{{ $step['num'] }}</span>
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 flex items-center justify-center text-[#6E7A25] shadow-sm">
                        {!! $step['icon'] !!}
                    </div>
                </div>
                <h4 class="font-bold text-sm text-gray-900 dark:text-white mb-2">{{ $step['title'] }}</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium">{{ $step['desc'] }}</p>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-[#6E7A25]/10 to-transparent rounded-b-full"></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Detailed Policy Content Layout --}}
<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            {{-- Desktop Table of Contents Sidebar --}}
            <aside class="hidden lg:block lg:col-span-3">
                <div class="sticky top-28 bg-gray-50 dark:bg-gray-800/30 rounded-3xl p-6 border border-gray-100 dark:border-gray-800/80 shadow-sm">
                    <h4 class="font-extrabold text-xs text-gray-400 dark:text-gray-500 mb-5 uppercase tracking-widest">{{ $isAr ? 'فهرس الشروط' : 'Terms Index' }}</h4>
                    <nav class="flex flex-col gap-3">
                        @foreach($sections as $sec)
                        <a href="#{{ $sec['id'] }}" class="text-xs font-bold text-gray-500 dark:text-gray-400 hover:text-[#6E7A25] dark:hover:text-white transition-all py-1.5 px-3 block rounded-lg hover:bg-gray-100/50 dark:hover:bg-gray-800/50 border-l-2 border-transparent hover:border-[#6E7A25] rtl:border-l-0 rtl:border-r-2 rtl:hover:border-[#6E7A25] tracking-wide">
                            {{ $isAr ? $sec['title_ar'] : $sec['title_en'] }}
                        </a>
                        @endforeach
                    </nav>
                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-850 text-center">
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-extrabold tracking-wider text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-3 py-1.5 rounded-full uppercase">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-16.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            {{ $isAr ? 'بوابة دفع مرخصة' : 'Secure Tap Payment' }}
                        </span>
                    </div>
                </div>
            </aside>

            {{-- Policy Sections --}}
            <div class="lg:col-span-9 space-y-8">
                @foreach($sections as $sec)
                <div id="{{ $sec['id'] }}" class="scroll-mt-28 bg-gray-50/30 dark:bg-gray-800/10 rounded-3xl p-6 sm:p-10 border border-gray-100 dark:border-gray-850 hover:border-gray-200 dark:hover:border-gray-800 transition-all duration-300">
                    <h3 class="text-base sm:text-lg font-black text-gray-900 dark:text-white mb-6 pb-4 border-b border-gray-100 dark:border-gray-850 flex items-center gap-3">
                        <span class="w-1.5 h-6 rounded-full bg-[#6E7A25]"></span>
                        {{ $isAr ? $sec['title_ar'] : $sec['title_en'] }}
                    </h3>
                    <ul class="space-y-4">
                        @php $points = $isAr ? $sec['points_ar'] : $sec['points_en']; @endphp
                        @foreach($points as $point)
                        <li class="flex items-start gap-4 text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-medium">
                            <div class="w-6 h-6 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/50 mt-0.5 shadow-sm">
                                <svg class="w-3.5 h-3.5 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span>{{ $point }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</section>

{{-- Contact Section --}}
<section class="py-16 bg-gray-50 dark:bg-gray-850 border-t border-gray-100 dark:border-gray-800/60 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-4">{{ __('Need Legal Clarification?') }}</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-8 max-w-2xl mx-auto font-medium">{{ __('For questions regarding these terms, please contact NutrioMeals Customer Support through the official website or application.') }}</p>
        <a href="{{ route('page.show', 'contact-support') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl bg-gradient-to-r from-[#173327] to-[#6E7A25] text-sm font-bold text-white shadow-lg shadow-[#6E7A25]/20 hover:shadow-xl hover:shadow-[#6E7A25]/30 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
            {{ __('Contact Support') }}
            <svg class="w-4.5 h-4.5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>
</section>

@include('landing.partials.footer')
@endsection

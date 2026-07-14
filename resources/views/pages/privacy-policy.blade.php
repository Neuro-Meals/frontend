@extends('layouts.landing')

@section('title', __('Privacy Policy') . ' - ' . __('Nutrio Meals'))

@section('content')
@include('landing.partials.header')

{{-- Hero Section --}}
@include('pages.partials.hero', ['title' => __('Privacy Policy'), 'description' => __('Learn how we collect, use, process, and protect your personal and payment data.')])

@php
    $isAr = app()->getLocale() === 'ar';
    $steps = [
        [
            'num' => '01',
            'title' => $isAr ? 'جمع البيانات' : 'Collection',
            'desc' => $isAr ? 'نجمع البيانات اللازمة فقط للاشتراك والتوصيل والتحسين.' : 'We collect only what is essential for subscriptions and delivery.',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>',
        ],
        [
            'num' => '02',
            'title' => $isAr ? 'دفع آمن ومحمي' : 'Safe Payments',
            'desc' => $isAr ? 'تتم المعالجة عبر Tap Payments. نحن لا نخزن بيانات بطاقتك.' : 'Processed via Tap Payments. We never store raw card details.',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>',
        ],
        [
            'num' => '03',
            'title' => $isAr ? 'حماية مشفرة' : 'Security',
            'desc' => $isAr ? 'تشفير كامل للبيانات لمنع الاختراق وحماية خصوصيتك.' : 'Full encryption and active firewalls to safeguard customer records.',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
        ],
        [
            'num' => '04',
            'title' => $isAr ? 'شركاء موثوقون' : 'Trusted Sharing',
            'desc' => $isAr ? 'تتم مشاركة العنوان مع السائق فقط لتأمين التوصيل.' : 'Share delivery address with drivers to ensure successful delivery.',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
        ],
        [
            'num' => '05',
            'title' => $isAr ? 'حقوقك الكاملة' : 'Your Rights',
            'desc' => $isAr ? 'تحكم كامل ببياناتك لتحديثها أو حذفها وفق نظام حماية البيانات.' : 'Full control over your data, update or delete whenever you request.',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-16.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
        ],
    ];

    $sections = [
        [
            'id' => 'data-collection',
            'title_en' => '1. Personal Information We Collect',
            'title_ar' => '1. المعلومات الشخصية التي نجمعها',
            'points_en' => [
                'Account Registration Details: Your full name, verified email address, active mobile number, secure account password, and localized profile inputs.',
                'Fulfillment & Logistics Data: Your exact geographical coordinates, delivery address, city, district, building access details, and specific delivery instructions.',
                'Fitness & Profile Metrics: Age, gender, height, current body weight, workout levels, nutrition goals, declared food allergies, and custom meal selection preferences.',
                'Technical Device Information: Anonymized IP addresses, browser types, log-in timestamps, cookie identifiers, and device operating system parameters.',
            ],
            'points_ar' => [
                'تفاصيل تسجيل الحساب: اسمك الكامل، عنوان بريدك الإلكتروني المعتمد، رقم هاتفك الجوال النشط، كلمة مرور حسابك الآمنة، ومعلومات ملفك الشخصي.',
                'بيانات التلبية واللوجستيات: إحداثيات موقعك الجغرافي الدقيق، عنوان التوصيل، المدينة، الحي، تفاصيل الدخول للمبنى، وإرشادات التوصيل المخصصة.',
                'المقاييس الرياضية وملف الجسم: العمر، الجنس، الطول، وزن الجسم الحالي، مستويات التمرين، أهداف التغذية، الحساسية الغذائية المعلنة، وتفضيلات اختيار الوجبات.',
                'معلومات الجهاز الفنية: عناوين بروتوكول الإنترنت (IP) المجهولة، أنواع المتصفحات، طوابع تسجيل الدخول الزمنية، معرفات ملفات تعريف الارتباط، ومعلمات نظام تشغيل الجهاز.',
            ],
        ],
        [
            'id' => 'payment-data',
            'title_en' => '2. Certified Secure Payment Processing (Tap Payments)',
            'title_ar' => '2. معالجة معتمدة وآمنة للمدفوعات (مدفوعات تاب)',
            'points_en' => [
                'NutrioMeals takes payment security extremely seriously and adheres strictly to international payment standards.',
                'All payment operations are handled through our certified secure partner, Tap Payments (https://www.tap.company), a fully compliant PCI-DSS billing gateway.',
                'We do not capture, record, store, or process any payment card raw numbers, CVV codes, or card expiration details. All details are encrypted in transit using Secure Socket Layer (SSL) protocols directly to Tap.',
                'Your payment details are protected by advanced fraud-detection and multi-factor secure authentication mechanisms verified by Tap Payments.',
            ],
            'points_ar' => [
                'تتعامل نوتريو ميلز مع أمن المدفوعات بجدية بالغة وتلتزم بصرامة بمعايير الدفع الدولية والمحلية.',
                'تتم معالجة جميع عمليات الدفع بالكامل من خلال شريكنا الآمن المعتمد، تاب للمدفوعات (Tap Payments)، وهو بوابة فواتير متوافقة تمامًا مع معايير PCI-DSS.',
                'نحن لا نلتقط، ولا نسجل، ولا نخزن، ولا نعالج أي أرقام بطاقات دفع خام، أو رموز أمان (CVV)، أو تواريخ انتهاء صلاحية البطاقات. يتم تشفير جميع التفاصيل أثناء النقل باستخدام بروتوكولات (SSL) مباشرة إلى خوادم تاب.',
                'تفاصيل الدفع الخاصة بك محمية بواسطة آليات متطورة للكشف عن الاحتيال والتحقق الآمن متعدد العوامل المعتمد من تاب للمدفوعات.',
            ],
        ],
        [
            'id' => 'data-usage',
            'title_en' => '3. Purpose & How We Use Your Information',
            'title_ar' => '3. الغرض وكيفية استخدامنا لمعلوماتك',
            'points_en' => [
                'Order Fulfillment: Processing subscription billing, preparing meals according to dietary preferences, and routing drivers to your address.',
                'Customer Support: Communicating active delivery statuses, resolving queries, handling pauses/resumes, and executing refund procedures.',
                'Optimization & Metrics: Personalizing macro recommendations using the Saudi Fit calculator, optimizing localized menus, and improving application interfaces.',
                'Security Assurance: Verifying registered accounts via email OTPs, preventing fraudulent billing actions, and keeping the platform safe for all.',
            ],
            'points_ar' => [
                'تلبية الطلبات: معالجة فواتير الاشتراك، إعداد الوجبات وفقًا للتفضيلات الغذائية، وتوجيه سائقي التوصيل إلى عنوانك بدقة.',
                'دعم العملاء: إرسال تحديثات حالة التوصيل النشطة، حل الاستفسارات، معالجة طلبات الإيقاف والاستئناف، وتنفيذ إجراءات الاسترداد المالي.',
                'التحسين والقياسات: تخصيص توصيات السعرات الحرارية والعناصر الغذائية باستخدام حاسبة سعودي فت، تحسين القوائم المحلية، وتحسين واجهات التطبيق.',
                'ضمان الأمان: التحقق من الحسابات المسجلة عبر رموز التحقق (OTP) للبريد الإلكتروني، منع عمليات الفوترة الاحتيالية، والحفاظ على المنصة آمنة للجميع.',
            ],
        ],
        [
            'id' => 'data-sharing',
            'title_en' => '4. Data Sharing & Third-Party Disclosure',
            'title_ar' => '4. مشاركة البيانات والإفصاح لأطراف ثالثة',
            'points_en' => [
                'Under no circumstances does NutrioMeals sell, trade, rent, or lease your personal identity records to third-party marketing companies.',
                'We disclose details only to trusted service partners when mandatory to deliver our services, including: Payment Processors (Tap Payments for secure billing), Logistics and Delivery Drivers (only name, phone, and address pin for routing), and Cloud Hosting Infrastructure (secure AWS/certified servers).',
                'We may disclose customer personal records only when strictly forced by applicable judicial orders, regulations, or statutory laws in Saudi Arabia.',
            ],
            'points_ar' => [
                'تحت تحت أي ظرف من الظروف، لا تقوم نوتريو ميلز ببيع، أو تداول، أو تأجير، أو إعارة سجلات هويتك الشخصية لشركات التسويق التابعة لجهات خارجية.',
                'نحن نفصح عن التفاصيل فقط لشركاء الخدمة الموثوق بهم عندما يكون ذلك إلزاميًا لتقديم خدماتنا، وهم: معالجو الدفع (بوابة تاب للمدفوعات الآمنة)، سائقو الخدمات اللوجستية والتوصيل (الاسم ورقم الجوال وتحديد الموقع الجغرافي فقط للتوصيل)، والبنية التحتية للاستضافة السحابية الآمنة.',
                'قد نفصح عن السجلات الشخصية للعميل فقط عندما نكون مجبرين بصرامة بموجب الأوامر القضائية المعمول بها، أو الأنظمة، أو القوانين الرسمية في المملكة العربية السعودية.',
            ],
        ],
        [
            'id' => 'customer-rights',
            'title_en' => '5. Your Rights & Data Control',
            'title_ar' => '5. حقوقك والتحكم ببياناتك الشخصية',
            'points_en' => [
                'In accordance with the Saudi Personal Data Protection Law (PDPL), you hold full legal rights over your personal records on NutrioMeals.',
                'Right to Access: You can request detailed transcripts of the personal files and historical activity records we store for your account.',
                'Right to Correct: You can update, correct, or refine outdated addresses, phone numbers, or allergy declarations in your profile.',
                'Right to Delete: You can request full, permanent deletion of your profile, account data, and historical data from our active databases, subject to billing audit retention laws.',
            ],
            'points_ar' => [
                'وفقًا لنظام حماية البيانات الشخصية (PDPL) في المملكة العربية السعودية، فإنك تمتلك حقوقًا قانونية كاملة على سجلاتك الشخصية في نوتريو ميلز.',
                'حق الوصول: يمكنك طلب تفاصيل كاملة عن الملفات الشخصية وسجلات الأنشطة التاريخية التي نخزنها لحسابك.',
                'حق التصحيح: يمكنك تحديث أو تصحيح العناوين القديمة، أو أرقام الجوال، أو إعلانات الحساسية في ملفك الشخصي في أي وقت.',
                'حق الحذف: يمكنك طلب حذف كامل ودائم لملفك الشخصي وبيانات حسابك من قواعد البيانات النشطة لدينا، مع مراعاة قوانين الاحتفاظ بالبيانات المالية لأغراض التدقيق.',
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
        <h3 class="text-center text-xs font-black uppercase tracking-widest text-[#6E7A25] mb-10">{{ $isAr ? 'معايير حماية البيانات والخصوصية' : 'Data Protection & Privacy Standards' }}</h3>
        
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
                    <h4 class="font-extrabold text-xs text-gray-400 dark:text-gray-500 mb-5 uppercase tracking-widest">{{ $isAr ? 'فهرس الخصوصية' : 'Privacy Index' }}</h4>
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
                            {{ $isAr ? 'حماية بيانات معتمدة' : 'Compliant PDPL Standard' }}
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

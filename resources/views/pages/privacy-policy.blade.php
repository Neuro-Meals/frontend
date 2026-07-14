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
        ],
        [
            'num' => '02',
            'title' => $isAr ? 'دفع آمن ومحمي' : 'Safe Payments',
            'desc' => $isAr ? 'تتم المعالجة عبر Tap Payments. نحن لا نخزن بيانات بطاقتك.' : 'Processed via Tap Payments. We never store raw card details.',
        ],
        [
            'num' => '03',
            'title' => $isAr ? 'حماية مشفرة' : 'Security',
            'desc' => $isAr ? 'تشفير كامل للبيانات لمنع الاختراق وحماية خصوصيتك.' : 'Full encryption and active firewalls to safeguard customer records.',
        ],
        [
            'num' => '04',
            'title' => $isAr ? 'شركاء موثوقون' : 'Trusted Sharing',
            'desc' => $isAr ? 'تتم مشاركة العنوان مع السائق فقط لتأمين التوصيل.' : 'Share delivery address with drivers to ensure successful delivery.',
        ],
        [
            'num' => '05',
            'title' => $isAr ? 'حقوقك الكاملة' : 'Your Rights',
            'desc' => $isAr ? 'تحكم كامل ببياناتك لتحديثها أو حذفها وفق نظام حماية البيانات.' : 'Full control over your data, update or delete whenever you request.',
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

{{-- Policy Process Stepper --}}
<section class="py-8 bg-gray-50 dark:bg-gray-800/50 border-y border-gray-100 dark:border-gray-700/50 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-center text-xs font-bold uppercase tracking-wider text-brand-light mb-8">{{ $isAr ? 'معايير حماية البيانات والخصوصية' : 'Data Protection & Privacy Standards' }}</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            @foreach($steps as $step)
            <div class="relative bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-800 transition-all hover:shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-2xl font-black text-brand-light/30">{{ $step['num'] }}</span>
                    <div class="w-2 h-2 rounded-full bg-brand-light"></div>
                </div>
                <h4 class="font-bold text-sm text-gray-900 dark:text-white mb-1.5">{{ $step['title'] }}</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">{{ $step['desc'] }}</p>
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
                <div class="sticky top-28 bg-gray-50 dark:bg-gray-800/30 rounded-2xl p-6 border border-gray-100 dark:border-gray-800/80">
                    <h4 class="font-bold text-sm text-gray-900 dark:text-white mb-4 uppercase tracking-wider">{{ $isAr ? 'فهرس الخصوصية' : 'Privacy Index' }}</h4>
                    <nav class="flex flex-col gap-2.5">
                        @foreach($sections as $sec)
                        <a href="#{{ $sec['id'] }}" class="text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-brand-light dark:hover:text-brand-light transition-colors py-1 block border-l-2 border-transparent hover:border-brand-light pl-3 rtl:pl-0 rtl:pr-3 rtl:border-l-0 rtl:border-r-2">
                            {{ $isAr ? $sec['title_ar'] : $sec['title_en'] }}
                        </a>
                        @endforeach
                    </nav>
                    <div class="mt-6 pt-5 border-t border-gray-200/60 dark:border-gray-700/60 text-center">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 px-2 py-1 rounded-full">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.9c.74-.75 2.045-.75 2.784 0l8.43 8.544a1 1 0 11-1.414 1.414L3.58 6.314a1 1 0 010-1.414zm10.73 1.414l5.688 5.76a2.001 2.001 0 11-2.83 2.828l-5.687-5.76a1 1 0 011.414-1.414l1.414 1.414V6.314a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                            {{ $isAr ? 'حماية بيانات معتمدة' : 'Compliant PDPL Standard' }}
                        </span>
                    </div>
                </div>
            </aside>

            {{-- Policy Sections --}}
            <div class="lg:col-span-9 space-y-12">
                @foreach($sections as $sec)
                <div id="{{ $sec['id'] }}" class="scroll-mt-28 bg-gray-50/50 dark:bg-gray-800/10 rounded-2xl p-6 sm:p-8 border border-gray-100/80 dark:border-gray-800/50 transition-all">
                    <h3 class="text-lg sm:text-xl font-black text-gray-900 dark:text-white mb-6 pb-3 border-b border-gray-100 dark:border-gray-800">
                        {{ $isAr ? $sec['title_ar'] : $sec['title_en'] }}
                    </h3>
                    <ul class="space-y-4">
                        @php $points = $isAr ? $sec['points_ar'] : $sec['points_en']; @endphp
                        @foreach($points as $point)
                        <li class="flex items-start gap-4 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            <div class="w-5 h-5 rounded-full bg-emerald-50 dark:bg-emerald-950/50 flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-800 mt-0.5">
                                <svg class="w-3 h-3 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg>
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
<section class="py-12 bg-gray-50 dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700/50 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white mb-3">{{ __('Need Legal Clarification?') }}</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-6">{{ __('For questions regarding these terms, please contact NutrioMeals Customer Support through the official website or application.') }}</p>
        <a href="{{ route('page.show', 'contact-support') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-[#173327] to-[#6E7A25] text-sm font-bold text-white shadow-md hover:shadow-lg transition-all hover:-translate-y-0.5">
            {{ __('Contact Support') }}
            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>
</section>

@include('landing.partials.footer')
@endsection

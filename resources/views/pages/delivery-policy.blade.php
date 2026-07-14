@extends('layouts.landing')

@section('title', __('Delivery Policy') . ' - ' . __('Nutrio Meals'))

@section('content')
@include('landing.partials.header')

{{-- Hero Section --}}
@include('pages.partials.hero', ['title' => __('Delivery Policy'), 'description' => __('Our policy for delivery addresses, timing, zones, and exceptions.')])

@php
    $isAr = app()->getLocale() === 'ar';
    $steps = [
        [
            'num' => '01',
            'title' => $isAr ? 'تحديد الموقع' : 'Location Pin',
            'desc' => $isAr ? 'ثبت موقعك بدقة على الخريطة وقدم عنوانًا واضحًا ورقمًا نشطًا.' : 'Pin your accurate address on the map during subscription or profile setup.',
        ],
        [
            'num' => '02',
            'title' => $isAr ? 'تحضير الوجبات' : 'Meal Preparation',
            'desc' => $isAr ? 'تُحضر وجباتك يوميًا طازجة ومغلفة بإحكام لسلامة غذائك.' : 'Meals are prepared fresh daily under strict food safety and packaging standards.',
        ],
        [
            'num' => '03',
            'title' => $isAr ? 'انطلاق السائق' : 'Driver Dispatch',
            'desc' => $isAr ? 'توجيه السائقين المخصصين لتوصيل وجباتك في الوقت المحدد.' : 'Assigned drivers depart early morning to deliver fresh meals smoothly.',
        ],
        [
            'num' => '04',
            'title' => $isAr ? 'توصيل لبابك' : 'Doorstep Delivery',
            'desc' => $isAr ? 'توصيل الوجبات مباشرة إلى باب منزلك أو مكتبك بمرونة.' : 'Your meals are delivered directly to your doorstep or building reception.',
        ],
        [
            'num' => '05',
            'title' => $isAr ? 'تحديثات الحالة' : 'Live Tracking',
            'desc' => $isAr ? 'تلقي إشعارات فورية وتتبع حالة طلبك التوصيلية مباشرة.' : 'Receive instant SMS or application status updates as your driver approaches.',
        ],
    ];

    $sections = [
        [
            'id' => 'coverage-zones',
            'title_en' => '1. Delivery Coverage & Geographical Zones',
            'title_ar' => '1. تغطية التوصيل والمناطق الجغرافية',
            'points_en' => [
                'NutrioMeals delivers healthy meals to verified districts within three major regions in Saudi Arabia: Riyadh, Jeddah, and Dammam.',
                'Coverage Boundaries: Specific distant zones, desert boundaries, or highly restricted closed security compounds may fall outside our standard routing.',
                'If your address pin falls outside our verified delivery zones during registration, our team will contact you to coordinate a custom dropping point or cancel with a full refund.',
            ],
            'points_ar' => [
                'تقدم نوتريو ميلز خدمات توصيل الوجبات الصحية للأحياء المعتمدة داخل ثلاث مدن رئيسية في المملكة العربية السعودية: الرياض، جدة، والدمام.',
                'حدود التغطية: قد تقع بعض المناطق النائية جدًا، أو الحدود الصحراوية، أو المجمعات السكنية المغلقة ذات القيود الأمنية العالية خارج نطاق التوصيل القياسي لدينا.',
                'إذا وقع تحديد موقعك الجغرافي خارج مناطق التوصيل المعتمدة لدينا أثناء التسجيل، سيتصل بك فريقنا لتنسيق نقطة إنزال مخصصة أو الإلغاء مع استرداد كامل المبلغ.',
            ],
        ],
        [
            'id' => 'delivery-schedules',
            'title_en' => '2. Delivery Timings & Daily Schedules',
            'title_ar' => '2. أوقات التوصيل والجداول اليومية',
            'points_en' => [
                'Delivery Window: Fresh meals are scheduled and delivered daily between 6:00 AM and 10:00 AM.',
                'Estimated Timings: While we assign dedicated routes to maximize efficiency, actual drop-off times may vary due to traffic conditions, weather exceptions, or checkpoint delays.',
                'We do not guarantee a specific exact minute for delivery, but we guarantee that your meal box will arrive fresh, chilled, and ready for your day within the morning delivery window.',
            ],
            'points_ar' => [
                'نافذة التوصيل: يتم جدولة وتوصيل الوجبات الطازجة يوميًا بين الساعة 6:00 صباحًا و10:00 صباحًا.',
                'الأوقات التقديرية: على الرغم من أننا نخصص مسارات محددة لزيادة الكفاءة، إلا أن أوقات التوصيل الفعلية قد تختلف بسبب ظروف حركة المرور، أو الطقس الاستثنائي، أو التأخير عند نقاط التفتيش.',
                'نحن لا نضمن دقيقة محددة بدقة للتوصيل، ولكننا نضمن وصول علبة وجباتك طازجة ومبردة وجاهزة ليومك ضمن نافذة التوصيل الصباحية.',
            ],
        ],
        [
            'id' => 'customer-obligations',
            'title_en' => '3. Customer Responsibilities & Access Codes',
            'title_ar' => '3. مسؤوليات العميل ورموز الدخول',
            'points_en' => [
                'Accurate Pinning: You are responsible for pinning your exact coordinates and writing the full building, floor, and apartment number in your profile.',
                'Active Contact: Customers must maintain an active mobile number. Our drivers or support team will call or WhatsApp you in case of compound gate restrictions or address confirmation.',
                'Building Access: You must provide necessary access codes, compound permissions, or instruct security guards to allow our drivers to enter or drop the meal box at the reception.',
            ],
            'points_ar' => [
                'تحديد الموقع الدقيق: أنت مسؤول عن تحديد إحداثيات موقعك الجغرافي بدقة وكتابة اسم المبنى الكامل، ورقم الدور والشقة في ملفك الشخصي.',
                'التواصل النشط: يجب على العملاء الحفاظ على رقم هاتف جوال نشط. سيتصل بك سائقونا أو فريق الدعم عبر الهاتف أو واتساب في حالة وجود قيود على بوابات المجمعات أو لتأكيد العنوان.',
                'الدخول للمباني: يجب عليك تقديم رموز الدخول اللازمة، أو تصاريح المجمعات السكنية، أو توجيه حراس الأمن للسماح لسائقينا بالدخول أو إيداع صندوق الوجبات في الاستقبال.',
            ],
        ],
        [
            'id' => 'unsuccessful-deliveries',
            'title_en' => '4. Unsuccessful Attempts & Re-delivery Protocols',
            'title_ar' => '4. محاولات التوصيل الفاشلة وبروتوكولات إعادة التوصيل',
            'points_en' => [
                'If our driver reaches your address and cannot contact you, or is refused access by security guards, the driver will make reasonable attempts to wait up to 10 minutes.',
                'Safe Drop: If we cannot reach you, we may leave the meal box at your doorstep, security gate, or building reception. In such cases, NutrioMeals is not responsible for any subsequent theft, spoilage, or damage.',
                'Failed Delivery: If a safe drop is not possible and the driver must leave, that day\'s meal is considered delivered due to customer unavailability, and no refund or replacement will be issued.',
            ],
            'points_ar' => [
                'إذا وصل سائقنا إلى عنوانك وتعذر عليه الاتصال بك، أو رفض حراس الأمن دخوله، فسيقوم السائق بمحاولات معقولة للانتظار لمدة تصل إلى 10 دقائق.',
                'الإنزال الآمن: إذا لم نتمكن من الوصول إليك، فقد نترك صندوق الوجبات عند باب منزلك، أو بوابة الأمن، أو استقبال المبنى. في مثل هذه الحالات، لا تكون نوتريو ميلز مسؤولة عن أي سرقة لاحقة، أو تلف، أو فساد للأغذية.',
                'التوصيل الفاشل: إذا لم يكن الإنزال الآمن ممكنًا واضطر السائق للمغادرة، تُعتبر وجبة ذلك اليوم مسلمة بسبب عدم توفر العميل، ولن يتم إصدار أي استرداد مالي أو استبدال للوجبة.',
            ],
        ],
    ];
@endphp

{{-- Policy Process Stepper --}}
<section class="py-8 bg-gray-50 dark:bg-gray-800/50 border-y border-gray-100 dark:border-gray-700/50 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-center text-xs font-bold uppercase tracking-wider text-brand-light mb-8">{{ $isAr ? 'سير عمل التوصيل والخدمات اللوجستية' : 'Delivery Workflow & Logistics Standards' }}</h3>
        
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
                    <h4 class="font-bold text-sm text-gray-900 dark:text-white mb-4 uppercase tracking-wider">{{ $isAr ? 'فهرس التوصيل' : 'Delivery Index' }}</h4>
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
                            {{ $isAr ? 'توصيل مبرد طازج يومياً' : 'Daily Fresh Cold Chain' }}
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

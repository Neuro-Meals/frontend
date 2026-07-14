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
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
        ],
        [
            'num' => '02',
            'title' => $isAr ? 'تحضير الوجبات' : 'Meal Preparation',
            'desc' => $isAr ? 'تُحضر وجباتك يوميًا طازجة ومغلفة بإحكام لسلامة غذائك.' : 'Meals are prepared fresh daily under strict food safety and packaging standards.',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>',
        ],
        [
            'num' => '03',
            'title' => $isAr ? 'انطلاق السائق' : 'Driver Dispatch',
            'desc' => $isAr ? 'توجيه السائقين المخصصين لتوصيل وجباتك في الوقت المحدد.' : 'Assigned drivers depart early morning to deliver fresh meals smoothly.',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>',
        ],
        [
            'num' => '04',
            'title' => $isAr ? 'توصيل لبابك' : 'Doorstep Delivery',
            'desc' => $isAr ? 'توصيل الوجبات مباشرة إلى باب منزلك أو مكتبك بمرونة.' : 'Your meals are delivered directly to your doorstep or building reception.',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
        ],
        [
            'num' => '05',
            'title' => $isAr ? 'تحديثات الحالة' : 'Live Tracking',
            'desc' => $isAr ? 'تلقي إشعارات فورية وتتبع حالة طلبك التوصيلية مباشرة.' : 'Receive instant SMS or application status updates as your driver approaches.',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>',
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
        <h3 class="text-center text-xs font-black uppercase tracking-widest text-[#6E7A25] mb-10">{{ $isAr ? 'سير عمل التوصيل والخدمات اللوجستية' : 'Delivery Workflow & Logistics Standards' }}</h3>
        
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
                    <h4 class="font-extrabold text-xs text-gray-400 dark:text-gray-500 mb-5 uppercase tracking-widest">{{ $isAr ? 'فهرس التوصيل' : 'Delivery Index' }}</h4>
                    <nav class="flex flex-col gap-3">
                        @foreach($sections as $sec)
                        <a href="#{{ $sec['id'] }}" class="text-xs font-bold text-gray-500 dark:text-gray-400 hover:text-[#6E7A25] dark:hover:text-white transition-all py-1.5 px-3 block rounded-lg hover:bg-gray-100/50 dark:hover:bg-gray-800/50 border-l-2 border-transparent hover:border-[#6E7A25] rtl:border-l-0 rtl:border-r-2 rtl:hover:border-[#6E7A25] tracking-wide">
                            {{ $isAr ? $sec['title_ar'] : $sec['title_en'] }}
                        </a>
                        @endforeach
                    </nav>
                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-850 text-center">
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-extrabold tracking-wider text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-3 py-1.5 rounded-full uppercase">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            {{ $isAr ? 'توصيل مبرد طازج يومياً' : 'Daily Fresh Cold Chain' }}
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

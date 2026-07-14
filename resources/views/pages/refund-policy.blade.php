@extends('layouts.landing')

@section('title', __('Return & Refund Policy') . ' - ' . __('Nutrio Meals'))

@section('content')
@include('landing.partials.header')

{{-- Hero Section --}}
@include('pages.partials.hero', ['title' => __('Return & Refund Policy'), 'description' => __('Our policy for returns, refunds, pauses, and cancellations.')])

@php
    $isAr = app()->getLocale() === 'ar';
    $steps = [
        [
            'num' => '01',
            'title' => $isAr ? 'الإيقاف والاستئناف' : 'Pause & Resume',
            'desc' => $isAr ? 'قم بإيقاف اشتراكك لحفظ أيامك المتبقية بدلاً من طلب الإلغاء.' : 'Pause subscription days to preserve your prepaid meal credits.',
        ],
        [
            'num' => '02',
            'title' => $isAr ? 'الإبلاغ عن المشكلات' : 'Report Issue',
            'desc' => $isAr ? 'أبلغ عن أي طلب تالف أو خاطئ خلال 24 ساعة مع إرفاق دليل.' : 'Report a damaged or incorrect meal within 24 hours with evidence.',
        ],
        [
            'num' => '03',
            'title' => $isAr ? 'طلب الترقية' : 'Up/Downgrade',
            'desc' => $isAr ? 'قم بترقية باقتك ودفع الفارق، أو خفضها واسترداد الرصيد.' : 'Upgrade/downgrade plans easily with calculated price adjustment.',
        ],
        [
            'num' => '04',
            'title' => $isAr ? 'الموافقة على الاسترداد' : 'Refund Approval',
            'desc' => $isAr ? 'مراجعة الطلب والموافقة عليه في حال تأثر الخدمة أو تعذر التلبية.' : 'Review of request and approval in case of failure to fulfill.',
        ],
        [
            'num' => '05',
            'title' => $isAr ? 'استرداد آمن لتاب' : 'Tap Card Reversal',
            'desc' => $isAr ? 'إرجاع المبالغ مباشرة إلى البطاقة الأصلية عبر بوابة Tap Payments.' : 'Directly refund to your payment card via secure Tap gateway.',
        ],
    ];

    $sections = [
        [
            'id' => 'food-nature',
            'title_en' => '1. Fresh Prepared Food & Return Policy',
            'title_ar' => '1. طبيعة الأغذية الطازجة وسياسة الإرجاع',
            'points_en' => [
                'Because NutrioMeals products consist of freshly prepared, custom-portioned food products with highly perishable timelines, physical meals cannot be returned once delivered.',
                'For food safety and hygiene regulations, delivered meals cannot be reused, restocked, or returned to our central kitchen facility.',
                'If you receive an incorrect, damaged, or spoiled meal box, do not consume it. Please contact customer support immediately for an instant resolution.',
            ],
            'points_ar' => [
                'نظرًا لأن منتجات نوتريو ميلز تتكون من وجبات طازجة ومحضرة مسبقًا بحصص مخصصة وتلف سريع، فلا يمكن إرجاع الوجبات فعليًا بمجرد تسليمها.',
                'لأسباب تتعلق بسلامة الغذاء والصحة العامة، لا يمكن إعادة استخدام الوجبات المسلمة، أو إعادة تخزينها، أو إعادتها إلى مطبخنا المركزي.',
                'إذا استلمت علبة وجبة غير صحيحة، أو تالفة، أو تالفة جزئيًا، فلا تقم باستهلاكها. يرجى الاتصال بخدمة العملاء فورًا للحصول على حل فوري.',
            ],
        ],
        [
            'id' => 'refund-conditions',
            'title_en' => '2. Eligible Refund Conditions & Photo Proof',
            'title_ar' => '2. شروط الاسترداد المؤهلة وإثبات الصور',
            'points_en' => [
                'Fulfillment Failures: If NutrioMeals is unable to prepare or deliver your meals due to unexpected kitchen shutdowns or severe logistical failures.',
                'Incorrect Billing: In the rare event of double billing errors, system payment mistakes, or accidental overcharges verified on Tap Payments.',
                'Damaged or Wrong Orders: If we deliver meals that significantly mismatch your selected plan ingredients or menu, or if containers are severely broken during transport.',
                'Reporting Timeline: Any eligible refund requests must be filed with support within 24 hours of the delivery date, accompanied by clear photograph proof of the container, label, or issue.',
            ],
            'points_ar' => [
                'أخطاء التلبية: إذا لم تكن نوتريو ميلز قادرة على إعداد أو توصيل وجباتك بسبب إغلاق غير متوقع للمطبخ أو إخفاقات لوجستية شديدة.',
                'الفوترة غير الصحيحة: في حالة حدوث أخطاء فوترة مزدوجة نادرة، أو أخطاء في نظام الدفع، أو فرض رسوم زائدة تم التحقق منها على بوابة تاب.',
                'الطلبات التالفة أو الخاطئة: إذا قمنا بتسليم وجبات تختلف بشكل كبير عن مكونات خطتك المحددة، أو إذا كانت الحاويات مكسورة أثناء النقل.',
                'الجدول الزمني للإبلاغ: يجب تقديم أي طلبات استرداد مؤهلة للدعم في غضون 24 ساعة من تاريخ التوصيل، مرفقة بصور فوتوغرافية واضحة للعلبة أو المشكلة.',
            ],
        ],
        [
            'id' => 'tap-reversal',
            'title_en' => '3. Approved Refund Processing & Tap Payments Reversals',
            'title_ar' => '3. معالجة الاسترداد المعتمد واسترداد مدفوعات تاب',
            'points_en' => [
                'Once a refund request is reviewed and approved by the audit team, it is authorized immediately on our systems.',
                'All approved refunds are reversed directly back to the original payment card (Mada, Visa, MasterCard) used during checkout through Tap Payments.',
                'NutrioMeals cannot refund cash, transfers, or alternate accounts for payments made via cards. This is a strict anti-fraud audit requirement by Saudi Central Bank and Tap Payments policies.',
                'Refund credit timelines depend entirely on your local bank and card issuer. Approved credits typically reflect in your account within 5 to 14 business days.',
            ],
            'points_ar' => [
                'بمجرد مراجعة طلب الاسترداد والموافقة عليه من قبل فريق التدقيق، يتم ترخيصه على الفور في أنظمتنا.',
                'تتم إعادة جميع المبالغ المستردة المعتمدة مباشرة إلى بطاقة الدفع الأصلية (مدى، فيزا، ماستركارد) المستخدمة أثناء الشراء عبر بوابة تاب (Tap Payments).',
                'لا يمكن لنوتريو ميلز استرداد الأموال نقدًا أو عبر حوالات بديلة للمدفوعات التي تمت عبر البطاقات. هذا متطلب تدقيق صارم لمكافحة الاحتيال من البنك المركزي السعودي وبوابة تاب.',
                'تعتمد الجداول الزمنية لرصيد الاسترداد بالكامل على البنك المحلي وجهة إصدار بطاقتك. عادةً ما تظهر المبالغ المستردة المعتمدة في حسابك في غضون 5 إلى 14 يوم عمل.',
            ],
        ],
        [
            'id' => 'pause-resume',
            'title_en' => '4. Pause Subscription Feature & Retention of Days',
            'title_ar' => '4. ميزة إيقاف الاشتراك والاحتفاظ بالأيام',
            'points_en' => [
                'If you are traveling, undergoing medical treatments, or wish to temporarily stop meals, you are encouraged to use the Pause Subscription feature instead of canceling.',
                'Pausable periods allow you to freeze your subscription days completely. Your paid credits and remaining delivery days are saved on your profile.',
                'When you click Resume on your dashboard, your subscription restarts, and delivery continues smoothly from where you stopped.',
                'Subscription pause requests must be submitted through your dashboard before the daily system cut-off time (typically 24 hours prior to the next scheduled meal preparation cycle).',
            ],
            'points_ar' => [
                'إذا كنت مسافرًا، أو تخضع لعلاجات طبية، أو ترغب في إيقاف الوجبات مؤقتًا، فنحن نشجعك على استخدام ميزة إيقاف الاشتراك مؤقتًا بدلاً من الإلغاء.',
                'تسمح لك فترات الإيقاف بتجميد أيام اشتراكك بالكامل. يتم حفظ أرصدتك المدفوعة وأيام التوصيل المتبقية في ملفك الشخصي.',
                'عند النقر فوق استئناف (Resume) في لوحة التحكم الخاصة بك، يتم إعادة تشغيل اشتراكك، ويستمر التوصيل بسلاسة من حيث توقفت.',
                'يجب تقديم طلبات إيقاف الاشتراك من خلال لوحة التحكم الخاصة بك قبل وقت الإغلاق اليومي للنظام (عادةً قبل 24 ساعة من دورة إعداد الوجبة التالية).',
            ],
        ],
        [
            'id' => 'changes-cancellations',
            'title_en' => '5. Plan Transitions, Upgrades & Cancellations',
            'title_ar' => '5. انتقال الخطط، الترقيات والإلغاء',
            'points_en' => [
                'Plan Changes: Customers can request to upgrade (e.g. from 2 meals to 3 meals) or downgrade their subscription tier. Price differences are calculated pro-rata.',
                'Upgrades take effect immediately once the price difference is paid successfully through Tap Payments.',
                'Downgrades will result in calculated prorated credits added to your profile balance, which can be applied to future renewals.',
                'Cancellation cutoff: Cancellations requested before any meal preparation has started may qualify for a full refund review. Cancellations submitted after subscription meals have begun prep/delivery do not qualify for a refund.',
            ],
            'points_ar' => [
                'تغييرات الخطة: يمكن للعملاء طلب ترقية باقتهم (مثل من وجبتين إلى 3 وجبات) أو خفض فئة الاشتراك. يتم حساب فروق الأسعار بالتناسب مع الأيام المتبقية.',
                'تسري الترقيات فورًا بمجرد دفع فرق السعر بنجاح من خلال بوابة تاب للمدفوعات.',
                'تؤدي التنزيلات إلى إضافة أرصدة تناسبية محسوبة إلى رصيد ملفك الشخصي، والتي يمكن تطبيقها على التجديدات المستقبلية.',
                'حد الإلغاء: قد تؤهل طلبات الإلغاء المقدمة قبل بدء تحضير أي وجبات لمراجعة استرداد كاملة. الطلبات المقدمة بعد بدء إعداد وجبات الاشتراك وتوصيلها لا تؤهل لاسترداد الأموال.',
            ],
        ],
    ];
@endphp

{{-- Policy Process Stepper --}}
<section class="py-8 bg-gray-50 dark:bg-gray-800/50 border-y border-gray-100 dark:border-gray-700/50 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-center text-xs font-bold uppercase tracking-wider text-brand-light mb-8">{{ $isAr ? 'رحلة الاسترداد والإيقاف والتعديل' : 'Refund, Pause & Change Processes' }}</h3>
        
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
                    <h4 class="font-bold text-sm text-gray-900 dark:text-white mb-4 uppercase tracking-wider">{{ $isAr ? 'فهرس الاسترداد' : 'Refund Index' }}</h4>
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
                            {{ $isAr ? 'تدقيق واسترداد عبر تاب' : 'Audited Tap Refund Reversals' }}
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

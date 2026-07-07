<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ChatController extends Controller
{
    /**
     * Public landing-page AI chat: general questions, no user context.
     */
    public function landing(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $message = strtolower(trim($request->input('message')));
        $locale = app()->getLocale();

        $reply = $this->matchLandingReply($message, $locale);

        return response()->json([
            'success' => true,
            'context' => 'landing',
            'reply' => $reply,
        ]);
    }

    /**
     * Authenticated customer dashboard AI chat: personalized responses.
     */
    public function customer(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $message = strtolower(trim($request->input('message')));
        $locale = app()->getLocale();
        $user = session('api_user') ?? [];
        $firstName = $user['first_name'] ?? __('there');

        $reply = $this->matchCustomerReply($message, $locale, $firstName);

        return response()->json([
            'success' => true,
            'context' => 'customer',
            'reply' => $reply,
        ]);
    }

    private function matchLandingReply(string $message, string $locale): string
    {
        $replies = [
            'en' => [
                'meal' => 'I can help you choose a meal plan based on your goals. Try asking about "weight loss", "muscle gain", or "healthy lifestyle" plans.',
                'plan' => 'We offer weekly and monthly meal plans tailored to different goals. Visit the Plans section to see details.',
                'nutrition' => 'Good nutrition is 80% of results. Ask me about calories, macros, or balanced meals.',
                'delivery' => 'Deliveries are scheduled between 09:00 and 10:00 daily across Riyadh.',
                'order' => 'You can subscribe to a plan and we handle your weekly meals automatically.',
                'price' => 'Plan prices start from 295 SAR per week. Visit the Plans page for full pricing.',
                'calorie' => 'Your daily calorie target depends on your goal and body metrics. Start a plan to get personalized targets.',
                'contact' => 'You can reach support via WhatsApp, live chat, or email at support@nutriomeals.com.',
                'hi' => 'Hello! Welcome to Nutrio Meals. How can I help you today?',
                'hello' => 'Hello! Welcome to Nutrio Meals. How can I help you today?',
            ],
            'ar' => [
                'meal' => 'يمكنني مساعدتك في اختيار خطة وجبات بناءً على أهدافك. اسأل عن خطط "فقدان الوزن" أو "زيادة العضلات" أو "أسلوب حياة صحي".',
                'plan' => 'نقدم خطط وجبات أسبوعية وشهرية مخصصة لأهداف مختلفة. قم بزيارة قسم الخطط لمزيد من التفاصيل.',
                'nutrition' => 'التغذية الجيدة هي 80٪ من النتائج. اسألني عن السعرات الحرارية أو العناصر الغذائية أو الوجبات المتوازنة.',
                'delivery' => 'يتم جدولة التوصيلات يوميًا بين الساعة 9:00 و10:00 في جميع أنحاء الرياض.',
                'order' => 'يمكنك الاشتراك في خطة وسنقوم بإدارة وجباتك الأسبوعية تلقائيًا.',
                'price' => 'أسعار الخطط تبدأ من 295 ريالًا في الأسبوع. قم بزيارة صفحة الخطط للاطلاع على الأسعار الكاملة.',
                'calorie' => 'يستهدف السعرات الحرارية اليومية على هدفك ومؤشرات جسمك. ابدأ خطة للحصول على أهداف مخصصة.',
                'contact' => 'يمكنك التواصل مع الدعم عبر واتساب أو الدردشة المباشرة أو البريد الإلكتروني: support@nutriomeals.com.',
                'hi' => 'مرحبًا! أهلاً بك في نوتريو ميلز. كيف يمكنني مساعدتك اليوم؟',
                'hello' => 'مرحبًا! أهلاً بك في نوتريو ميلز. كيف يمكنني مساعدتك اليوم؟',
            ],
        ];

        $map = $replies[$locale] ?? $replies['en'];
        $default = $locale === 'ar'
            ? 'أنا هنا للمساعدة في الوجبات والخطط والتغذية والطلبات. هل يمكنك توضيح سؤالك؟'
            : 'I\'m here to help with meals, plans, nutrition, and orders. Could you clarify your question?';

        foreach ($map as $keyword => $text) {
            if (str_contains($message, $keyword)) {
                return $text;
            }
        }

        return $default;
    }

    private function matchCustomerReply(string $message, string $locale, string $firstName): string
    {
        $replies = [
            'en' => [
                'meal' => "Hi {$firstName}, I can suggest today's meals based on your active plan. Head to the Meals page to see what's scheduled.",
                'plan' => "Hi {$firstName}, your active plan details are on the Subscriptions page. You can change or pause your plan anytime.",
                'nutrition' => "Hi {$firstName}, check the Nutrition page for your personalized calorie and macro targets for today.",
                'delivery' => "Hi {$firstName}, your next delivery is shown in the Delivery section. You can also track its status there.",
                'order' => "Hi {$firstName}, you can create a new order or manage existing ones from the Orders page.",
                'weight' => "Hi {$firstName}, log your weight in the Nutrition page to track your progress over time.",
                'calorie' => "Hi {$firstName}, your daily calorie target is calculated from your profile and goal. See the Nutrition page.",
                'subscription' => "Hi {$firstName}, manage or renew your subscription from the Subscriptions page.",
                'hi' => "Hi {$firstName}! How can I help with your nutrition journey today?",
                'hello' => "Hi {$firstName}! How can I help with your nutrition journey today?",
            ],
            'ar' => [
                'meal' => "مرحبًا {$firstName}، يمكنني اقتراح وجبات اليوم بناءً على خطتك النشطة. انتقل إلى صفحة الوجبات لمعرفة ما هو مجدول.",
                'plan' => "مرحبًا {$firstName}، تفاصيل خطتك النشطة متاحة في صفحة الاشتراكات. يمكنك تغيير خطتك أو إيقافها مؤقتًا في أي وقت.",
                'nutrition' => "مرحبًا {$firstName}، راجع صفحة التغذية لمعرفة أهداف السعرات الحرارية والعناصر الغذائية المخصصة لك اليوم.",
                'delivery' => "مرحبًا {$firstName}، يتم عرض توصيلك التالي في قسم التوصيل. يمكنك أيضًا تتبع حالته هناك.",
                'order' => "مرحبًا {$firstName}، يمكنك إنشاء طلب جديد أو إدارة الطلبات الحالية من صفحة الطلبات.",
                'weight' => "مرحبًا {$firstName}، سجل وزنك في صفحة التغذية لمتابعة تقدمك مع الوقت.",
                'calorie' => "مرحبًا {$firstName}، يتم حساب هدف السعرات الحرارية اليومية بناءً على ملفك الشخصي وهدفك. راجع صفحة التغذية.",
                'subscription' => "مرحبًا {$firstName}، يمكنك إدارة اشتراكك أو تجديده من صفحة الاشتراكات.",
                'hi' => "مرحبًا {$firstName}! كيف يمكنني مساعدتك في رحلتك الغذائية اليوم؟",
                'hello' => "مرحبًا {$firstName}! كيف يمكنني مساعدتك في رحلتك الغذائية اليوم؟",
            ],
        ];

        $map = $replies[$locale] ?? $replies['en'];
        $default = $locale === 'ar'
            ? "مرحبًا {$firstName}، أنا هنا لمساعدتك في وجباتك وخطتك والتغذية. هل يمكنك توضيح سؤالك؟"
            : "Hi {$firstName}, I\'m here to help with your meals, plan, and nutrition. Could you clarify your question?";

        foreach ($map as $keyword => $text) {
            if (str_contains($message, $keyword)) {
                return $text;
            }
        }

        return $default;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ChatController extends Controller
{
    private const CONTEXT_LANDING = 'landing';
    private const CONTEXT_CUSTOMER = 'customer';
    private const HISTORY_LIMIT = 6;

    /**
     * Public landing-page AI chat: general questions, no user context.
     */
    public function landing(Request $request)
    {
        return $this->handleChat($request, self::CONTEXT_LANDING);
    }

    /**
     * Authenticated customer dashboard AI chat: personalized responses.
     */
    public function customer(Request $request)
    {
        return $this->handleChat($request, self::CONTEXT_CUSTOMER);
    }

    private function handleChat(Request $request, string $context)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $rawMessage = $request->input('message');
        $locale = app()->getLocale();
        $user = session('api_user') ?? [];
        $firstName = $user['first_name'] ?? null;
        $detectedLang = $this->detectLanguage($rawMessage);
        $lang = $detectedLang ?: $locale;

        $history = $this->getHistory($context);
        $history[] = ['role' => 'user', 'message' => $rawMessage];
        $history = array_slice($history, -self::HISTORY_LIMIT);

        $reply = $this->buildReply($rawMessage, $context, $lang, $firstName, $history);

        $history[] = ['role' => 'bot', 'message' => $reply];
        $this->saveHistory($context, $history);

        return response()->json([
            'success' => true,
            'context' => $context,
            'reply' => $reply,
        ]);
    }

    /**
     * Detect whether the message is written in Arabic script.
     */
    private function detectLanguage(string $message): ?string
    {
        if (preg_match('/[\x{0600}-\x{06FF}]/u', $message)) {
            return 'ar';
        }
        if (preg_match('/[a-zA-Z]/', $message)) {
            return 'en';
        }
        return null;
    }

    private function getHistory(string $context): array
    {
        return Session::get("ai_chat_history_{$context}", []);
    }

    private function saveHistory(string $context, array $history): void
    {
        Session::put("ai_chat_history_{$context}", array_slice($history, -self::HISTORY_LIMIT));
    }

    private function buildReply(string $rawMessage, string $context, string $lang, ?string $firstName, array $history): string
    {
        $message = mb_strtolower(trim($rawMessage));
        $greeting = $this->isGreeting($message);

        if ($greeting) {
            return $this->answer('greeting', $lang, $firstName);
        }

        // Capture any height/weight/age/activity values shared by the user.
        $this->extractProfileFromMessage($rawMessage, $context);
        $profile = $this->getProfile($context);

        // Weight-loss / goal intent: give a contextual recommendation.
        if ($this->matches($message, ['lose weight', 'weight loss', 'lose fat', 'تخفيف', 'إنقاص', 'فقدان الوزن', 'أريد أن أنحف', 'أريد خسارة'])) {
            return $this->weightLossReply($lang, $profile, $context);
        }

        if ($this->matches($message, ['gain muscle', 'muscle gain', 'build muscle', 'أبني عضلات', 'زيادة العضلات', 'أريد عضلات'])) {
            return $this->muscleGainReply($lang, $profile, $context);
        }

        if ($this->matches($message, ['maintain', 'maintenance', 'حفظ الوزن', 'الحفاظ', 'maintain weight'])) {
            return $this->maintenanceReply($lang, $profile, $context);
        }

        // Plan / subscription duration intent.
        if ($this->matches($message, ['plan', 'plans', 'subscription', 'subscribe', 'اشتراك', 'خطة', 'خطط', 'باقة', 'باقات'])) {
            return $this->answer('plans', $lang, $firstName);
        }

        // Pricing intent: only provide verified range if we have it, otherwise direct to plans page.
        if ($this->matches($message, ['price', 'cost', 'how much', 'سعر', 'تكلفة', 'كم', ' prices'])) {
            return $this->answer('pricing', $lang, $firstName);
        }

        // Delivery intent.
        if ($this->matches($message, ['delivery', 'deliver', 'ship', 'توصيل', 'توصيلات', 'يسلمون', 'السائق'])) {
            return $this->answer('delivery', $lang, $firstName);
        }

        // Contact / support intent.
        if ($this->matches($message, ['contact', 'support', 'whatsapp', 'email', 'call', 'تواصل', 'دعم', 'واتساب', 'اتصال', 'مساعد'])) {
            return $this->answer('contact', $lang, $firstName);
        }

        // Meal / menu intent.
        if ($this->matches($message, ['meal', 'meals', 'menu', 'food', 'وجبة', 'وجبات', 'طعام', 'أكل'])) {
            return $this->answer('meals', $lang, $firstName);
        }

        // Nutrition / calories / macros intent.
        if ($this->matches($message, ['calorie', 'calories', 'macro', 'macros', 'nutrition', 'سعرات', 'سعرة', 'عناصر غذائية', 'تغذية'])) {
            return $this->answer('nutrition', $lang, $firstName);
        }

        // Order intent.
        if ($this->matches($message, ['order', 'orders', 'طلب', 'طلبات', 'Order'])) {
            return $this->answer('orders', $lang, $firstName);
        }

        // Account / registration intent.
        if ($this->matches($message, ['account', 'register', 'sign up', 'create account', 'حساب', 'تسجيل', 'اشتراك جديد'])) {
            return $this->answer('account', $lang, $firstName);
        }

        // Dietary preference field removed: answer gracefully if the user asks about it.
        if ($this->matches($message, ['dietary preference', 'vegetarian', 'vegan', 'keto', 'paleo', 'gluten', 'تفضيل غذائي', 'نباتي', 'باليو', 'كيتو', 'جلوتين'])) {
            return $this->answer('dietary_removed', $lang, $firstName);
        }

        // If the user is just giving profile data without a question, acknowledge it and ask what they need help with.
        if ($this->hasProfileData($profile) && $this->looksLikeDataOnly($message)) {
            return $this->answer('data_ack', $lang, $firstName);
        }

        return $this->answer('default', $lang, $firstName);
    }

    private function isGreeting(string $message): bool
    {
        return $this->matches($message, [
            'hi', 'hello', 'hey', 'good morning', 'good afternoon', 'good evening',
            'مرحبا', 'مرحبًا', 'أهلا', 'أهلاً', 'سلام', 'صباح', 'مساء', 'هاي', 'هلا'
        ]);
    }

    private function matches(string $message, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($message, mb_strtolower($needle))) {
                return true;
            }
        }
        return false;
    }

    private function looksLikeDataOnly(string $message): bool
    {
        // If the message contains only numbers and short words (e.g. "30 70 175") it's likely data.
        return preg_match('/^(\d+(\s+|\.|,|cm|kg|years?|yrs?|\w+)?)+$/i', trim($message)) === 1;
    }

    private function hasProfileData(array $profile): bool
    {
        return !empty($profile['age']) || !empty($profile['weight']) || !empty($profile['height']) || !empty($profile['activity']);
    }

    private function getProfile(string $context): array
    {
        return Session::get("ai_chat_profile_{$context}", []);
    }

    private function extractProfileFromMessage(string $message, string $context): void
    {
        $profile = $this->getProfile($context);
        $message = preg_replace('/[^\d\s\w]/u', ' ', $message);

        // Extract age
        if (preg_match('/(\d{2,3})\s*(?:years?|yrs?|عام|سنة|سنين)/i', $message, $m) || preg_match('/(?:age|عمر|سن)\s*:?\s*(\d{2,3})/i', $message, $m)) {
            $profile['age'] = (int) $m[1];
        }

        // Extract weight (kg)
        if (preg_match('/(\d{2,3}(?:\.\d+)?)\s*(?:kg|kgs|kilos|كيلو)/i', $message, $m)) {
            $profile['weight'] = (float) $m[1];
        }

        // Extract height (cm)
        if (preg_match('/(\d{2,3})\s*(?:cm|cms|سنتي)/i', $message, $m)) {
            $profile['height'] = (int) $m[1];
        }

        // Extract activity level
        $activityKeywords = [
            'sedentary' => ['sedentary', 'little', 'no exercise', 'قليل', 'بدون', 'مكتب', 'office'],
            'light' => ['light', 'light exercise', '1-3', 'lightly', 'خفيف', 'قليل'],
            'moderate' => ['moderate', 'moderate exercise', '3-5', 'متوسط', 'متوسط'],
            'active' => ['active', 'heavy exercise', '6-7', 'very active', 'نشط', 'رياضي', 'كثير'],
        ];
        foreach ($activityKeywords as $level => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($message, $keyword) !== false) {
                    $profile['activity'] = $level;
                    break 2;
                }
            }
        }

        Session::put("ai_chat_profile_{$context}", $profile);
    }

    private function weightLossReply(string $lang, array $profile, string $context): string
    {
        $missing = $this->missingProfileFields($profile);
        if (empty($missing)) {
            $bmr = $this->calculateBmr($profile['weight'], $profile['height'], $profile['age'], 'female'); // default; we don't know gender here
            // Use a conservative estimate. Actual calculator on the site is more precise.
            $target = max(1200, round($bmr * $this->activityMultiplier($profile['activity'] ?? 'moderate') * 0.8));
            if ($lang === 'ar') {
                return "بناءً على بياناتك ({$profile['age']} سنة، {$profile['weight']} كجم، {$profile['height']} سم)، هدف السعرات الحرارية اليومية تقريبًا {$target} سعرة. خطة **فقدان الوزن** مناسبة لك — يمكنك الاشتراك لمدة شهر أو شهرين أو ثلاثة أشهر. هل تريد مساعدة في اختيار المدة؟";
            }
            return "Based on your details (age {$profile['age']}, weight {$profile['weight']}kg, height {$profile['height']}cm), your estimated daily calorie target is around {$target} kcal. The **Weight Loss** plan is a great fit. You can subscribe for One, Two, or Three months. Would you like help choosing a duration?";
        }

        $ask = $this->askMissing($missing, $lang, 'weight_loss');
        return $lang === 'ar'
            ? "خطة **فقدان الوزن** مناسبة لتحقيق هدفك. {$ask}"
            : "The **Weight Loss** plan fits your goal. {$ask}";
    }

    private function muscleGainReply(string $lang, array $profile, string $context): string
    {
        $missing = $this->missingProfileFields($profile);
        if (empty($missing)) {
            $bmr = $this->calculateBmr($profile['weight'], $profile['height'], $profile['age'], 'male');
            $target = round($bmr * $this->activityMultiplier($profile['activity'] ?? 'moderate') * 1.1);
            if ($lang === 'ar') {
                return "بناءً على بياناتك، هدف السعرات الحرارية اليومية تقريبًا {$target} سعرة. خطة **زيادة العضلات** مناسبة لك — اشترك لمدة شهر أو شهرين أو ثلاثة أشهر. هل تريد مساعدة في البدء؟";
            }
            return "Based on your details, your estimated daily calorie target is around {$target} kcal. The **Muscle Gain** plan is a great fit. Subscribe for One, Two, or Three months. Need help getting started?";
        }

        $ask = $this->askMissing($missing, $lang, 'muscle_gain');
        return $lang === 'ar'
            ? "خطة **زيادة العضلات** مناسبة لتحقيق هدفك. {$ask}"
            : "The **Muscle Gain** plan fits your goal. {$ask}";
    }

    private function maintenanceReply(string $lang, array $profile, string $context): string
    {
        $missing = $this->missingProfileFields($profile);
        if (empty($missing)) {
            $bmr = $this->calculateBmr($profile['weight'], $profile['height'], $profile['age'], 'female');
            $target = round($bmr * $this->activityMultiplier($profile['activity'] ?? 'moderate'));
            if ($lang === 'ar') {
                return "بناءً على بياناتك، هدف السعرات الحرارية اليومية تقريبًا {$target} سعرة. خطة **الحفاظ على الوزن** مناسبة لك. يمكنك الاشتراك لمدة شهر أو شهرين أو ثلاثة أشهر.";
            }
            return "Based on your details, your estimated daily calorie target is around {$target} kcal. The **Maintenance** plan is a great fit. You can subscribe for One, Two, or Three months.";
        }

        $ask = $this->askMissing($missing, $lang, 'maintenance');
        return $lang === 'ar'
            ? "خطة **الحفاظ على الوزن** مناسبة لك. {$ask}"
            : "The **Maintenance** plan fits your goal. {$ask}";
    }

    private function missingProfileFields(array $profile): array
    {
        $missing = [];
        if (empty($profile['age'])) $missing[] = 'age';
        if (empty($profile['weight'])) $missing[] = 'weight';
        if (empty($profile['height'])) $missing[] = 'height';
        if (empty($profile['activity'])) $missing[] = 'activity';
        return $missing;
    }

    private function askMissing(array $missing, string $lang, string $goal): string
    {
        $labels = [
            'en' => [
                'age' => 'your age',
                'weight' => 'your weight in kg',
                'height' => 'your height in cm',
                'activity' => 'your activity level (sedentary, light, moderate, active)',
            ],
            'ar' => [
                'age' => 'عمرك',
                'weight' => 'وزنك بالكيلو',
                'height' => 'طولك بالسنتي',
                'activity' => 'مستوى نشاطك (منخفض، خفيف، متوسط، نشط)',
            ],
        ];

        $lang = $lang === 'ar' ? 'ar' : 'en';
        $items = array_map(fn ($f) => $labels[$lang][$f], $missing);

        if ($lang === 'ar') {
            $last = array_pop($items);
            $list = empty($items) ? $last : implode('، ', $items) . ' و ' . $last;
            return "لإعطائك توصية دقيقة، أخبرني بـ {$list} (يمكنك إرسالها معًا في رسالة واحدة).";
        }

        $last = array_pop($items);
        $list = empty($items) ? $last : implode(', ', $items) . ' and ' . $last;
        return "To give you an accurate recommendation, please share {$list} (you can send them all in one message).";
    }

    private function calculateBmr(float $weight, int $height, int $age, string $gender): float
    {
        // Mifflin-St Jeor equation
        $base = 10 * $weight + 6.25 * $height - 5 * $age;
        return $gender === 'male' ? $base + 5 : $base - 161;
    }

    private function activityMultiplier(string $activity): float
    {
        return match ($activity) {
            'sedentary' => 1.2,
            'light' => 1.375,
            'moderate' => 1.55,
            'active' => 1.725,
            default => 1.55,
        };
    }

    private function answer(string $topic, string $lang, ?string $firstName): string
    {
        $name = $firstName ? ($lang === 'ar' ? " {$firstName}" : " {$firstName}") : '';
        $nameAr = $firstName ? " {$firstName}" : '';

        $knowledge = [
            'greeting' => [
                'en' => "Hi{$name}! Welcome to Nutrio Meals. I'm your AI assistant. How can I help you today?",
                'ar' => "مرحبًا{$nameAr}! أهلاً بك في نوتريو ميلز. أنا مساعدك الذكي. كيف يمكنني مساعدتك اليوم؟",
            ],
            'plans' => [
                'en' => "Nutrio Meals offers goal-based plans: Weight Loss, Muscle Gain, and Maintenance. Subscriptions are available for One Month, Two Months, or Three Months. Weekly plans are not currently available. You can view full details on the Plans page.",
                'ar' => "تقدم نوتريو ميلز خططًا بناءً على الهدف: فقدان الوزن، وزيادة العضلات، والحفاظ على الوزن. الاشتراكات متاحة لمدة شهر أو شهرين أو ثلاثة أشهر. الخطط الأسبوعية غير متوفرة حاليًا. يمكنك رؤية التفاصيل الكاملة في صفحة الخطط.",
            ],
            'pricing' => [
                'en' => "Plan prices depend on your chosen duration and goals. Please visit the Plans page for current, verified pricing, as I don't have the exact live price list.",
                'ar' => "أسعار الخطط تعتمد على المدة والهدف الذي تختاره. يرجى زيارة صفحة الخطط للاطلاع على الأسعار الحالية والموثقة، لأن ليس لدي قائمة الأسعار المحدثة مباشرة.",
            ],
            'delivery' => [
                'en' => "We deliver fresh meals to Riyadh, Jeddah, and Dammam. Deliveries are typically scheduled between 6 AM and 10 AM daily. You can track your delivery status from your dashboard.",
                'ar' => "نوصل الوجبات الطازجة إلى الرياض وجدة والدمام. يتم جدولة التوصيلات عادةً بين الساعة 6 صباحًا و10 صباحًا يوميًا. يمكنك تتبع حالة توصيلك من لوحة التحكم.",
            ],
            'contact' => [
                'en' => "You can reach our support team via WhatsApp, live chat, or email at support@nutriomeals.com. We're here to help!",
                'ar' => "يمكنك التواصل مع فريق الدعم عبر واتساب أو الدردشة المباشرة أو البريد الإلكتروني support@nutriomeals.com. نحن هنا للمساعدة!",
            ],
            'meals' => [
                'en' => "Our meals are chef-crafted, fresh, and portion-controlled. You can view and customize your weekly menu from the Meals page after subscribing.",
                'ar' => "وجباتنا من إعداد الطهاة، طازجة، ومراقبة في الحصص. يمكنك عرض قائمتك الأسبوعية وتخصيصها من صفحة الوجبات بعد الاشتراك.",
            ],
            'nutrition' => [
                'en' => "Your personalized calorie and macro targets are calculated from your profile, body metrics, and goal. Use the Nutrition page or the calorie calculator on the home page for an estimate.",
                'ar' => "يتم حساب أهداف السعرات الحرارية والعناصر الغذائية المخصصة لك من ملفك الشخصي ومؤشرات جسمك وهدفك. استخدم صفحة التغذية أو حاسبة السعرات في الصفحة الرئيسية للحصول على تقدير.",
            ],
            'orders' => [
                'en' => "You can create and manage orders from your dashboard after subscribing. Need help picking a plan first?",
                'ar' => "يمكنك إنشاء الطلبات وإدارتها من لوحة التحكم بعد الاشتراك. هل تحتاج مساعدة في اختيار خطة أولاً؟",
            ],
            'account' => [
                'en' => "Creating an account is simple: just your name, phone, email, password, location, and address. After you log in, you can complete your profile and subscribe.",
                'ar' => "إنشاء الحساب بسيط: يكفي اسمك ورقم الجوال والبريد الإلكتروني وكلمة المرور والموقع والعنوان. بعد تسجيل الدخول، يمكنك إكمال ملفك الشخصي والاشتراك.",
            ],
            'dietary_removed' => [
                'en' => "We no longer use a Dietary Preference field. You can still choose your meals each week from the menu and note any allergies in your profile.",
                'ar' => "لم نعد نستخدم حقل التفضيل الغذائي. ما زال بإمكانك اختيار وجباتك أسبوعيًا من القائمة وذكر أي حساسيات في ملفك الشخصي.",
            ],
            'data_ack' => [
                'en' => "Thanks for sharing your details. How can I help you next — choose a plan, estimate calories, or subscribe?",
                'ar' => "شكرًا لمشاركة بياناتك. كيف يمكنني مساعدتك الآن — اختيار خطة، تقدير السعرات، أو الاشتراك؟",
            ],
            'default' => [
                'en' => "I'm here to help with Nutrio Meals plans, meals, nutrition, delivery, and account questions. Could you clarify what you'd like to know?",
                'ar' => "أنا هنا للمساعدة في خطط ووجبات وتغذية وتوصيل وحسابات نوتريو ميلز. هل يمكنك توضيح ما تريد معرفته؟",
            ],
        ];

        $text = $knowledge[$topic][$lang] ?? $knowledge[$topic]['en'] ?? $knowledge['default']['en'];
        return $text;
    }
}

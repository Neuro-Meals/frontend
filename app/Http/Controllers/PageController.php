<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    private $pages = [
        'about-us', 'track-your-order', 'order-history', 'subscription-management',
        'payment-methods', 'contact-support', 'faqs', 'help-center', 'live-chat',
        'whatsapp-support', 'partner-with-us', 'become-a-supplier', 'affiliate-program',
        'collaboration', 'sponsorship', 'my-subscriptions', 'reward-points',
        'refer-a-friend', 'privacy-policy', 'terms-of-service', 'refund-policy',
        'cookie-policy', 'food-safety',
    ];

    public function show($slug)
    {
        if (!in_array($slug, $this->pages)) {
            abort(404);
        }

        return view('pages.' . $slug);
    }
}


            // ---- Account ----
            'my-subscriptions' => [
                'title' => 'My Subscriptions',
                'description' => 'View and manage your active meal plan subscriptions.',
                'sections' => [
                    [
                        'type' => 'text',
                        'heading' => 'Your Subscriptions',
                        'body' => 'Sign in to view your active subscriptions, see your next delivery date, manage meal preferences, and make changes to your plan. Everything is in one place.',
                    ],
                    [
                        'type' => 'cards',
                        'heading' => 'Subscription Features',
                        'cards' => [
                            ['icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9', 'title' => 'Active Plans', 'text' => 'See your current plan, next delivery, and billing date.'],
                            ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2', 'title' => 'Meal Preferences', 'text' => 'Choose which meals you want each week from our rotating menu.'],
                            ['icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0', 'title' => 'Plan Settings', 'text' => 'Pause, skip, upgrade, or cancel — all from one dashboard.'],
                        ],
                    ],
                    [
                        'type' => 'cta',
                        'heading' => 'Sign in to manage your subscriptions',
                        'button_text' => 'Sign In',
                        'button_link' => '/login',
                    ],
                ],
            ],
            'reward-points' => [
                'title' => 'Reward Points',
                'description' => 'Earn points with every order and redeem them for discounts and free meals.',
                'sections' => [
                    [
                        'type' => 'text',
                        'heading' => 'Earn While You Eat',
                        'body' => 'Every order you place earns you reward points. Points can be redeemed for discounts on future orders, free meals, or exclusive perks. The more you order, the more you earn.',
                    ],
                    [
                        'type' => 'cards',
                        'heading' => 'How to Earn Points',
                        'cards' => [
                            ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2', 'title' => '1 Point Per SAR', 'text' => 'Earn 1 point for every SAR spent on orders.'],
                            ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857', 'title' => 'Refer Friends', 'text' => 'Get 500 bonus points for each friend who subscribes.'],
                            ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Weekly Bonus', 'text' => 'Complete a full week of orders and get 200 bonus points.'],
                            ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Social Shares', 'text' => 'Share your meals on social media and earn 50 points per post.'],
                        ],
                    ],
                    [
                        'type' => 'text',
                        'heading' => 'Rede Your Points',
                        'body' => '100 points = SAR 1 discount. Redeem at checkout or save up for free meals. 1,000 points gets you a free meal, 5,000 points gets you a free week.',
                    ],
                    [
                        'type' => 'cta',
                        'heading' => 'Sign in to see your points',
                        'button_text' => 'Sign In',
                        'button_link' => '/login',
                    ],
                ],
            ],
            'refer-a-friend' => [
                'title' => 'Refer a Friend',
                'description' => 'Give your friends a discount and earn reward points for yourself.',
                'sections' => [
                    [
                        'type' => 'text',
                        'heading' => 'Share the Health',
                        'body' => 'Love Nutrio Meals? Share it with your friends! When they sign up using your referral link, they get SAR 50 off their first order, and you earn 500 reward points. Everybody wins.',
                    ],
                    [
                        'type' => 'steps',
                        'heading' => 'How It Works',
                        'steps' => [
                            ['title' => 'Get Your Link', 'text' => 'Sign in and copy your unique referral link from your dashboard.'],
                            ['title' => 'Share With Friends', 'text' => 'Send your link via WhatsApp, social media, or email.'],
                            ['title' => 'They Get SAR 50 Off', 'text' => 'Your friend gets SAR 50 discount on their first subscription order.'],
                            ['title' => 'You Get 500 Points', 'text' => 'Earn 500 reward points for each successful referral.'],
                        ],
                    ],
                    [
                        'type' => 'cta',
                        'heading' => 'Start referring friends today',
                        'button_text' => 'Get Your Referral Link',
                        'button_link' => '/login',
                    ],
                ],
            ],

            // ---- Legal ----
            'privacy-policy' => [
                'title' => 'Privacy Policy',
                'description' => 'How Nutrio Meals collects, uses, and protects your personal information.',
                'sections' => [
                    [
                        'type' => 'text',
                        'heading' => 'Introduction',
                        'body' => 'At Nutrio Meals, we take your privacy seriously. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our website and services. Please read this policy carefully.',
                    ],
                    [
                        'type' => 'text',
                        'heading' => 'Information We Collect',
                        'body' => 'We collect information you provide directly to us, such as your name, email address, phone number, delivery address, and payment information when you create an account or place an order. We also automatically collect certain information about your device and usage patterns.',
                    ],
                    [
                        'type' => 'text',
                        'heading' => 'How We Use Your Information',
                        'body' => 'We use your information to process orders, deliver meals, manage subscriptions, send notifications, improve our services, and communicate with you about promotions and updates. We do not sell your personal information to third parties.',
                    ],
                    [
                        'type' => 'text',
                        'heading' => 'Data Security',
                        'body' => 'We implement industry-standard security measures to protect your personal information, including encryption, secure servers, and access controls. However, no method of transmission over the internet is 100% secure.',
                    ],
                    [
                        'type' => 'text',
                        'heading' => 'Your Rights',
                        'body' => 'You have the right to access, update, or delete your personal information. You can also opt out of marketing communications at any time. To exercise these rights, contact us at privacy@nutriomeals.com.',
                    ],
                    [
                        'type' => 'text',
                        'heading' => 'Contact Us',
                        'body' => 'If you have questions about this Privacy Policy, please contact us at privacy@nutriomeals.com or +966 50 123 4567.',
                    ],
                ],
            ],
            'terms-of-service' => [
                'title' => 'Terms of Service',
                'description' => 'The terms and conditions for using Nutrio Meals services.',
                'sections' => [
                    [
                        'type' => 'text',
                        'heading' => 'Acceptance of Terms',
                        'body' => 'By accessing and using Nutrio Meals services, you accept and agree to be bound by these Terms of Service. If you do not agree, please do not use our services.',
                    ],
                    [
                        'type' => 'text',
                        'heading' => 'Subscriptions',
                        'body' => 'Subscriptions are billed in advance on a weekly, monthly, or annual basis depending on your selected plan. You can pause, modify, or cancel your subscription at any time through your dashboard. Cancellations take effect at the end of the current billing cycle.',
                    ],
                    [
                        'type' => 'text',
                        'heading' => 'Delivery',
                        'body' => 'We strive to deliver all orders on time. However, we are not liable for delays caused by circumstances beyond our control. If you are not satisfied with your delivery, contact us within 24 hours for a resolution.',
                    ],
                    [
                        'type' => 'text',
                        'heading' => 'Food Quality',
                        'body' => 'All meals are prepared fresh daily. We maintain strict food safety standards. If you receive a meal that does not meet our quality standards, contact us within 24 hours for a refund or replacement.',
                    ],
                    [
                        'type' => 'text',
                        'heading' => 'Limitation of Liability',
                        'body' => 'Nutrio Meals is not liable for any indirect, incidental, or consequential damages arising from the use of our services. Our total liability shall not exceed the amount you have paid for the service in question.',
                    ],
                ],
            ],
            'refund-policy' => [
                'title' => 'Refund Policy',
                'description' => 'Our policy for refunds and replacements.',
                'sections' => [
                    [
                        'type' => 'text',
                        'heading' => 'Satisfaction Guarantee',
                        'body' => 'We stand behind the quality of our meals. If you are not satisfied with your order, contact us within 24 hours of delivery and we will arrange a refund or replacement.',
                    ],
                    [
                        'type' => 'text',
                        'heading' => 'Subscription Refunds',
                        'body' => 'If you cancel your subscription, you will not be charged for the next billing cycle. Refunds for the current cycle are issued on a pro-rata basis for any undelivered meals. Processing time is 5-10 business days.',
                    ],
                    [
                        'type' => 'text',
                        'heading' => 'Non-Refundable Cases',
                        'body' => 'Refunds are not issued for: meals that have been consumed, orders not reported within 24 hours, or cancellations made after delivery has been completed.',
                    ],
                    [
                        'type' => 'text',
                        'heading' => 'How to Request a Refund',
                        'body' => 'To request a refund, contact our support team at support@nutriomeals.com or via WhatsApp at +966 50 123 4567. Include your order number and reason for the refund request.',
                    ],
                ],
            ],
            'cookie-policy' => [
                'title' => 'Cookie Policy',
                'description' => 'How Nutrio Meals uses cookies and similar technologies.',
                'sections' => [
                    [
                        'type' => 'text',
                        'heading' => 'What Are Cookies?',
                        'body' => 'Cookies are small text files stored on your device when you visit a website. They help us remember your preferences, keep you logged in, and understand how you use our services.',
                    ],
                    [
                        'type' => 'text',
                        'heading' => 'Types of Cookies We Use',
                        'body' => 'Essential cookies: Required for the website to function properly. Preference cookies: Remember your settings and preferences. Analytics cookies: Help us understand how visitors use our site. Marketing cookies: Used to show you relevant advertisements.',
                    ],
                    [
                        'type' => 'text',
                        'heading' => 'Managing Cookies',
                        'body' => 'You can control and delete cookies through your browser settings. Disabling essential cookies may affect website functionality. You can also opt out of analytics and marketing cookies at any time.',
                    ],
                ],
            ],
            'food-safety' => [
                'title' => 'Food Safety',
                'description' => 'Our commitment to the highest food safety standards.',
                'sections' => [
                    [
                        'type' => 'text',
                        'heading' => 'Our Commitment to Safety',
                        'body' => 'Food safety is our top priority. Our kitchen follows HACCP (Hazard Analysis Critical Control Point) standards and is regularly inspected by the Saudi Food and Drug Authority (SFDA).',
                    ],
                    [
                        'type' => 'cards',
                        'heading' => 'Our Safety Standards',
                        'cards' => [
                            ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Certified Kitchen', 'text' => 'Our facilities are SFDA certified and HACCP compliant.'],
                            ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857', 'title' => 'Trained Staff', 'text' => 'All chefs and kitchen staff are certified in food safety handling.'],
                            ['icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4', 'title' => 'Quality Ingredients', 'text' => 'We source from approved suppliers with full traceability.'],
                            ['icon' => 'M21 13.255A23.931 23.931 0 0112 15', 'title' => 'Temperature Control', 'text' => 'Meals are kept at safe temperatures from kitchen to delivery.'],
                        ],
                    ],
                    [
                        'type' => 'text',
                        'heading' => 'Allergen Information',
                        'body' => 'All meals are labeled with allergen information. If you have specific allergies or dietary restrictions, please note them in your account settings. Our team takes every precaution to prevent cross-contamination.',
                    ],
                    [
                        'type' => 'text',
                        'heading' => 'Reporting Concerns',
                        'body' => 'If you have any food safety concerns, please contact us immediately at safety@nutriomeals.com. We take all reports seriously and investigate promptly.',
                    ],
                ],
            ],
        ];
    }

    public function show($slug)
    {
        $pages = $this->pages();

        if (!isset($pages[$slug])) {
            abort(404);
        }

        $page = $pages[$slug];

        return view('pages.page', [
            'title' => $page['title'],
            'description' => $page['description'],
            'sections' => $page['sections'],
        ]);
    }
}

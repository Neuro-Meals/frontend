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

            'faqs' => [
                'title' => 'Frequently Asked Questions',
                'description' => 'Find answers to the most common questions about Nutrio Meals.',
                'sections' => [
                    [
                        'type' => 'faq',
                        'heading' => 'General Questions',
                        'items' => [
                            ['q' => 'How does Nutrio Meals work?', 'a' => 'Choose a meal plan that fits your goals, select your preferred meals from our weekly menu, and we deliver fresh meals to your door daily or weekly depending on your plan.'],
                            ['q' => 'What areas do you deliver to?', 'a' => 'We currently deliver to Riyadh, Jeddah, and Dammam. We are expanding to more cities soon — follow us on social media for updates.'],
                            ['q' => 'Can I customize my meals?', 'a' => 'Yes! You can customize your meals each week from our menu. We offer options for different dietary preferences including keto, vegetarian, and high-protein.'],
                            ['q' => 'How fresh are the meals?', 'a' => 'All meals are prepared fresh daily by our chefs and delivered in insulated packaging to maintain temperature and quality.'],
                        ],
                    ],
                    [
                        'type' => 'faq',
                        'heading' => 'Subscription & Billing',
                        'items' => [
                            ['q' => 'Can I pause my subscription?', 'a' => 'Absolutely. You can pause your subscription anytime from your dashboard. No fees, no questions asked. Resume whenever you are ready.'],
                            ['q' => 'How do I cancel?', 'a' => 'You can cancel your subscription from your account dashboard with a single click. There are no cancellation fees or contracts.'],
                            ['q' => 'Do you offer refunds?', 'a' => 'If you are not satisfied with your meals, contact us within 24 hours of delivery for a refund or replacement. See our Refund Policy for details.'],
                            ['q' => 'Can I switch plans?', 'a' => 'Yes, you can upgrade or downgrade your plan at any time. Changes take effect from your next billing cycle.'],
                        ],
                    ],
                    [
                        'type' => 'faq',
                        'heading' => 'Delivery & Packaging',
                        'items' => [
                            ['q' => 'What time are meals delivered?', 'a' => 'Meals are delivered between 6 AM and 10 AM daily. You will receive a notification when your delivery is on the way.'],
                            ['q' => 'Do I need to be home for delivery?', 'a' => 'No, our insulated packaging keeps meals fresh for up to 4 hours. Just let us know if you have a preferred drop-off spot.'],
                            ['q' => 'Is the packaging eco-friendly?', 'a' => 'Yes, we use recyclable and biodegradable packaging materials. We are committed to reducing our environmental impact.'],
                        ],
                    ],
                ],
            ],
            'help-center' => [
                'title' => 'Help Center',
                'description' => 'Find guides, tutorials, and answers to help you get the most out of Nutrio Meals.',
                'sections' => [
                    [
                        'type' => 'cards',
                        'heading' => 'Browse by Category',
                        'cards' => [
                            ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2', 'title' => 'Getting Started', 'text' => 'New to Nutrio Meals? Learn how to set up your account and place your first order.'],
                            ['icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9', 'title' => 'Managing Subscriptions', 'text' => 'Learn how to pause, skip, upgrade, or cancel your subscription.'],
                            ['icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4', 'title' => 'Delivery & Tracking', 'text' => 'Everything about delivery times, areas, and tracking your orders.'],
                            ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2', 'title' => 'Payments & Billing', 'text' => 'Manage payment methods, view invoices, and understand billing cycles.'],
                            ['icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01', 'title' => 'Nutrition & Meal Plans', 'text' => 'Understand macros, calories, and choosing the right plan for your goals.'],
                            ['icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0', 'title' => 'Account Settings', 'text' => 'Update your profile, change your password, and manage preferences.'],
                        ],
                    ],
                    [
                        'type' => 'text',
                        'heading' => 'Still Need Help?',
                        'body' => 'Our support team is available Sunday through Thursday, 9 AM to 9 PM. Reach out via contact form, WhatsApp, or phone, and we will get back to you as soon as possible.',
                    ],
                    [
                        'type' => 'cta',
                        'heading' => 'Cannot find what you are looking for?',
                        'button_text' => 'Contact Support',
                        'button_link' => '/page/contact-support',
                    ],
                ],
            ],
            'live-chat' => [
                'title' => 'Live Chat',
                'description' => 'Chat with our support team in real-time for instant assistance.',
                'sections' => [
                    [
                        'type' => 'text',
                        'heading' => 'Live Chat Support',
                        'body' => 'Our live chat is available Sunday through Thursday, 9 AM to 9 PM (AST). Click the chat icon in the bottom right corner of any page to start a conversation with our support team.',
                    ],
                    [
                        'type' => 'cards',
                        'heading' => 'What We Can Help With',
                        'cards' => [
                            ['icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'title' => 'Order Issues', 'text' => 'Missing items, wrong delivery, or quality concerns.'],
                            ['icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9', 'title' => 'Subscription Help', 'text' => 'Pausing, upgrading, or changing your plan.'],
                            ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2', 'title' => 'Payment Questions', 'text' => 'Billing issues, refunds, or payment method changes.'],
                            ['icon' => 'M9 7h6m0 10v-3m-3 3h.01', 'title' => 'Nutrition Advice', 'text' => 'Help choosing the right plan for your fitness goals.'],
                        ],
                    ],
                    [
                        'type' => 'cta',
                        'heading' => 'Prefer to talk to us directly?',
                        'button_text' => 'Contact Support',
                        'button_link' => '/page/contact-support',
                    ],
                ],
            ],
            'whatsapp-support' => [
                'title' => 'WhatsApp Support',
                'description' => 'Get quick help through WhatsApp — the fastest way to reach our team.',
                'sections' => [
                    [
                        'type' => 'text',
                        'heading' => 'Chat With Us on WhatsApp',
                        'body' => 'Save our number +966 50 123 4567 and send us a message on WhatsApp anytime. Our team responds within minutes during business hours (Sun-Thu, 9 AM to 9 PM AST). Outside business hours, we will reply first thing in the morning.',
                    ],
                    [
                        'type' => 'cards',
                        'heading' => 'Why WhatsApp?',
                        'cards' => [
                            ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Fast Response', 'text' => 'Average response time under 5 minutes during business hours.'],
                            ['icon' => 'M8 12h.01M12 12h.01M16 12h.01', 'title' => 'Easy Communication', 'text' => 'Send photos, voice notes, or documents to explain your issue.'],
                            ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Always Available', 'text' => 'Message us anytime — we reply as soon as we are online.'],
                        ],
                    ],
                    [
                        'type' => 'cta',
                        'heading' => 'Open WhatsApp now',
                        'button_text' => 'Chat on WhatsApp',
                        'button_link' => 'https://wa.me/966501234567',
                    ],
                ],
            ],

            // ---- Partnerships ----
            'partner-with-us' => [
                'title' => 'Partner With Us',
                'description' => 'Join forces with Nutrio Meals and grow your business with Saudi Arabia\'s leading meal subscription service.',
                'sections' => [
                    [
                        'type' => 'text',
                        'heading' => 'Why Partner With Nutrio Meals?',
                        'body' => 'We are always looking for strategic partners who share our vision of a healthier Saudi Arabia. Whether you are a gym, fitness center, corporate office, or health brand, we offer partnership programs tailored to your needs.',
                    ],
                    [
                        'type' => 'cards',
                        'heading' => 'Partnership Opportunities',
                        'cards' => [
                            ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2', 'title' => 'Gym & Fitness Partners', 'text' => 'Offer Nutrio Meals to your members with exclusive discounts.'],
                            ['icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'title' => 'Corporate Wellness', 'text' => 'Provide healthy meal plans as part of your employee wellness program.'],
                            ['icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13l3-3m0 0l-3-3m3 3H8', 'title' => 'Health Brands', 'text' => 'Co-brand and cross-promote with Nutrio Meals campaigns.'],
                            ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Influencer Program', 'text' => 'Promote Nutrio Meals and earn commission on referrals.'],
                        ],
                    ],
                    [
                        'type' => 'contact',
                        'heading' => 'Get in Touch',
                    ],
                ],
            ],
            'become-a-supplier' => [
                'title' => 'Become a Supplier',
                'description' => 'Supply quality ingredients to Nutrio Meals and be part of our growing network.',
                'sections' => [
                    [
                        'type' => 'text',
                        'heading' => 'Quality Starts With Ingredients',
                        'body' => 'We source the freshest, highest-quality ingredients from trusted suppliers across Saudi Arabia. If you are a farmer, distributor, or food producer committed to quality and sustainability, we want to work with you.',
                    ],
                    [
                        'type' => 'cards',
                        'heading' => 'What We Look For',
                        'cards' => [
                            ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Quality Standards', 'text' => 'All suppliers must meet our strict quality and food safety standards.'],
                            ['icon' => 'M5 13l4 4L19 7', 'title' => 'Fresh Produce', 'text' => 'Vegetables, fruits, herbs, and organic produce delivered fresh.'],
                            ['icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4', 'title' => 'Protein Sources', 'text' => 'Chicken, beef, fish, and plant-based protein suppliers.'],
                            ['icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9', 'title' => 'Local Sourcing', 'text' => 'We prioritize locally-sourced ingredients to support the Saudi economy.'],
                        ],
                    ],
                    [
                        'type' => 'contact',
                        'heading' => 'Apply to Become a Supplier',
                    ],
                ],
            ],
            'affiliate-program' => [
                'title' => 'Affiliate Program',
                'description' => 'Earn commission by referring customers to Nutrio Meals.',
                'sections' => [
                    [
                        'type' => 'text',
                        'heading' => 'Earn While You Share',
                        'body' => 'Join our affiliate program and earn commission for every new subscriber you refer. Whether you are a fitness influencer, blogger, or just love our meals — you can earn by sharing Nutrio Meals with your network.',
                    ],
                    [
                        'type' => 'steps',
                        'heading' => 'How It Works',
                        'steps' => [
                            ['title' => 'Sign Up', 'text' => 'Register for our affiliate program and get your unique referral link.'],
                            ['title' => 'Share', 'text' => 'Share your link on social media, your blog, or with friends and family.'],
                            ['title' => 'Earn', 'text' => 'Get commission for every new paying subscriber that signs up through your link.'],
                        ],
                    ],
                    [
                        'type' => 'cards',
                        'heading' => 'Program Benefits',
                        'cards' => [
                            ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2', 'title' => 'Competitive Commission', 'text' => 'Earn up to 15% commission on every subscription you refer.'],
                            ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Transparent Tracking', 'text' => 'Real-time dashboard to track your referrals and earnings.'],
                            ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Fast Payouts', 'text' => 'Monthly payouts via bank transfer or STC Pay.'],
                        ],
                    ],
                    [
                        'type' => 'cta',
                        'heading' => 'Ready to start earning?',
                        'button_text' => 'Sign Up Now',
                        'button_link' => '/register',
                    ],
                ],
            ],
            'collaboration' => [
                'title' => 'Collaboration Opportunities',
                'description' => 'Let us create something amazing together — events, content, and more.',
                'sections' => [
                    [
                        'type' => 'text',
                        'heading' => 'Let Us Collaborate',
                        'body' => 'We love working with like-minded brands and creators. From co-branded meal launches to fitness events and content collaborations, we are open to creative ideas that promote healthy living.',
                    ],
                    [
                        'type' => 'cards',
                        'heading' => 'Collaboration Types',
                        'cards' => [
                            ['icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15', 'title' => 'Co-Branded Meals', 'text' => 'Create a signature meal with our chefs and your brand.'],
                            ['icon' => 'M21 13.255A23.931 23.931 0 0112 15', 'title' => 'Events & Pop-Ups', 'text' => 'Host healthy eating events, workshops, or pop-up kitchens together.'],
                            ['icon' => 'M15 10l4.207-4.207a1 1 0 011.414 0L22 7', 'title' => 'Content Creation', 'text' => 'Collaborate on recipes, videos, blogs, and social media content.'],
                            ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857', 'title' => 'Charity Initiatives', 'text' => 'Partner on community health and food donation programs.'],
                        ],
                    ],
                    [
                        'type' => 'contact',
                        'heading' => 'Tell Us Your Idea',
                    ],
                ],
            ],
            'sponsorship' => [
                'title' => 'Sponsorship Requests',
                'description' => 'Request Nutrio Meals sponsorship for your event, team, or cause.',
                'sections' => [
                    [
                        'type' => 'text',
                        'heading' => 'Sponsorship Program',
                        'body' => 'We sponsor fitness events, sports teams, health conferences, and community initiatives that align with our mission of promoting healthy living in Saudi Arabia. If you have an event or cause that could benefit from Nutrio Meals sponsorship, we would love to hear from you.',
                    ],
                    [
                        'type' => 'cards',
                        'heading' => 'What We Sponsor',
                        'cards' => [
                            ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Sports Events', 'text' => 'Marathons, tournaments, and athletic competitions.'],
                            ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857', 'title' => 'Fitness Challenges', 'text' => 'Gym competitions, CrossFit events, and fitness bootcamps.'],
                            ['icon' => 'M21 13.255A23.931 23.931 0 0112 15', 'title' => 'Health Conferences', 'text' => 'Medical, nutrition, and wellness conferences and seminars.'],
                            ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Community Causes', 'text' => 'Charity runs, food drives, and community health programs.'],
                        ],
                    ],
                    [
                        'type' => 'contact',
                        'heading' => 'Submit Your Sponsorship Request',
                    ],
                ],
            ],

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

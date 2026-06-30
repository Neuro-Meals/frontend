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

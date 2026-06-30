<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->isAdmin()) {
                abort(403, 'Access denied. Admin only.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $stats = [
            'totalUsers' => \App\Models\User::count(),
            'newUsersThisWeek' => \App\Models\User::where('created_at', '>=', now()->subWeek())->count(),
            'totalRevenue' => 0,
            'activeSubscriptions' => 0,
            'totalMeals' => 0,
            'successRate' => 100,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $user = Auth::user();

        $stats = [
            'activePlan' => 'None',
            'mealsThisWeek' => 0,
            'totalOrders' => 0,
            'dailyCalories' => 0,
            'proteinTarget' => 0,
            'streakDays' => 0,
        ];

        return view('user.dashboard', compact('user', 'stats'));
    }
}

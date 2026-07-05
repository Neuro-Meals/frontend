<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class ConfirmPasswordController extends Controller
{
    public function __construct()
    {
        // API-based auth — no local auth middleware needed
    }
}

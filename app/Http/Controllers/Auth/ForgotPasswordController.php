<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Api\AuthApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request, AuthApiService $authApi)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->only('email'));
        }

        $response = $authApi->forgotPassword($request->email);

        if (isset($response['success']) && $response['success'] === false) {
            return back()->withErrors(['email' => $response['message'] ?? 'Failed to send reset OTP.'])
                ->withInput($request->only('email'));
        }

        return back()->with('status', 'A password reset OTP has been sent to your email.')
            ->withInput($request->only('email'));
    }
}

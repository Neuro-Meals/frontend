<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Api\AuthApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(
        Request $request,
        AuthApiService $authApi
    ) {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $email = strtolower(trim((string) $request->email));
        $response = $authApi->forgotPassword($email);

        if (($response['success'] ?? true) === false) {
            return back()
                ->withErrors([
                    'email' => $response['message']
                        ?? __('Unable to send password reset OTP.'),
                ])
                ->withInput($request->only('email'));
        }

        session(['password_reset_email' => $email]);

        return redirect()
            ->route('password.reset', ['email' => $email])
            ->with(
                'status',
                __('A 6-digit password reset OTP has been sent to your email.')
            );
    }

    public function resend(
        Request $request,
        AuthApiService $authApi
    ) {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $email = strtolower(trim((string) $request->email));
        $response = $authApi->resendPasswordResetOtp($email);

        if (($response['success'] ?? true) === false) {
            return back()
                ->withErrors([
                    'otp' => $response['message']
                        ?? __('Unable to resend password reset OTP.'),
                ])
                ->withInput($request->only('email'));
        }

        session(['password_reset_email' => $email]);

        return redirect()
            ->route('password.reset', ['email' => $email])
            ->with(
                'status',
                __('A new 6-digit OTP has been sent to your email.')
            );
    }
}
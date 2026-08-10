<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Api\AuthApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request)
    {
        $email = strtolower(trim((string) (
            $request->query('email')
            ?: session('password_reset_email', '')
        )));

        if ($email === '') {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => __('Enter your email first to request a reset OTP.'),
                ]);
        }

        return view('auth.passwords.reset', [
            'email' => $email,
        ]);
    }

    public function reset(
        Request $request,
        AuthApiService $authApi
    ) {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'otp' => ['required', 'string', 'regex:/^[0-9]{6}$/'],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:128',
                'confirmed',
            ],
        ], [
            'otp.regex' => __('Enter the 6-digit OTP sent to your email.'),
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email', 'otp'));
        }

        $email = strtolower(trim((string) $request->email));

        $response = $authApi->resetPassword(
            $email,
            trim((string) $request->otp),
            (string) $request->password
        );

        if (($response['success'] ?? true) === false) {
            $message = $response['message']
                ?? $response['detail']
                ?? __('Invalid or expired OTP.');

            return back()
                ->withErrors(['otp' => $message])
                ->withInput($request->only('email'));
        }

        session()->forget('password_reset_email');

        return redirect()
            ->route('login')
            ->with(
                'status',
                __('Your password has been reset successfully. Please log in with your new password.')
            );
    }
}
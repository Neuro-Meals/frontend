<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Api\AuthApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    public function show(Request $request)
    {
        $email = $request->query('email', '');
        return view('auth.verify', ['email' => $email]);
    }

    public function verify(Request $request, AuthApiService $authApi)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'otp'   => ['required', 'string', 'size:6'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->only('email'));
        }

        $response = $authApi->verifyEmail($request->email, $request->otp);

        if (isset($response['success']) && $response['success'] === false) {
            return back()->withErrors(['otp' => $response['message'] ?? 'Invalid or expired OTP.'])
                ->withInput($request->only('email'));
        }

        return redirect()->route('login')
            ->with('status', 'Email verified successfully! You can now log in.');
    }

    public function resend(Request $request, AuthApiService $authApi)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $response = $authApi->resendVerificationOtp($request->email);

        if (isset($response['success']) && $response['success'] === false) {
            return back()->withErrors(['email' => $response['message'] ?? 'Failed to resend OTP.']);
        }

        return back()->with('status', 'A new verification OTP has been sent to your email.');
    }
}

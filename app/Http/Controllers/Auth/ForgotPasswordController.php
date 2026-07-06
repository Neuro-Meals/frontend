<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Services\Api\AuthApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

        $apiFailed = isset($response['success']) && $response['success'] === false;
        $otpCode = $apiFailed ? null : $this->extractOtpFromResponse($response);

        if ($otpCode) {
            try {
                Mail::to($request->email)->send(new PasswordResetOtpMail(
                    email: $request->email,
                    otpCode: $otpCode,
                    expiresInMinutes: 15,
                ));
            } catch (\Exception $e) {
                Log::warning('Failed to send password reset OTP via sendmail', [
                    'email' => $request->email,
                    'error' => $e->getMessage(),
                ]);
            }
        } elseif (! $apiFailed) {
            Log::info('Password reset API did not return an OTP; relying on backend API to deliver the email', [
                'email' => $request->email,
            ]);
        }

        if ($apiFailed) {
            return back()->withErrors(['email' => $response['message'] ?? 'Failed to send reset OTP.'])
                ->withInput($request->only('email'));
        }

        return back()->with('status', 'A password reset OTP has been sent to your email.')
            ->withInput($request->only('email'));
    }

    /**
     * Extract an OTP/code from the API response using common keys.
     */
    private function extractOtpFromResponse(array $response): ?string
    {
        foreach (['otp', 'code', 'reset_code', 'token', 'reset_token'] as $key) {
            if (! empty($response[$key]) && is_string($response[$key])) {
                return $response[$key];
            }
        }

        if (! empty($response['data']) && is_array($response['data'])) {
            foreach (['otp', 'code', 'reset_code', 'token', 'reset_token'] as $key) {
                if (! empty($response['data'][$key]) && is_string($response['data'][$key])) {
                    return $response['data'][$key];
                }
            }
        }

        return null;
    }
}

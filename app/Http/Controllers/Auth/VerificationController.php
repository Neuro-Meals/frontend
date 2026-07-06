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
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Please fix the errors in the form.'),
                    'errors' => $validator->errors()->toArray(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput($request->only('email'));
        }

        $response = $authApi->verifyEmail($request->email, $request->otp);

        if (isset($response['success']) && $response['success'] === false) {
            $message = $response['message'] ?? 'Invalid or expired OTP.';

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => ['otp' => [$message]],
                ], 422);
            }

            return back()->withErrors(['otp' => $message])
                ->withInput($request->only('email'));
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully! You can now log in.',
                'redirect' => route('login'),
            ]);
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
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Please fix the errors in the form.'),
                    'errors' => $validator->errors()->toArray(),
                ], 422);
            }
            return back()->withErrors($validator);
        }

        $response = $authApi->resendVerificationOtp($request->email);

        if (isset($response['success']) && $response['success'] === false) {
            $message = $response['message'] ?? 'Failed to resend OTP.';

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => ['email' => [$message]],
                ], 422);
            }

            return back()->withErrors(['email' => $message]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'A new verification OTP has been sent to your email.',
            ]);
        }

        return back()->with('status', 'A new verification OTP has been sent to your email.');
    }
}

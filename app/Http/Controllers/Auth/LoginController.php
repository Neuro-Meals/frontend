<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Api\AuthApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $authApi = app(AuthApiService::class);
            if ($authApi->check()) {
                return redirect()->route('home');
            }
            return $next($request);
        })->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request, AuthApiService $authApi)
    {
        $validator = Validator::make($request->all(), [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->only('email'));
        }

        $response = $authApi->login($request->email, $request->password);

        // API may explicitly tell us that the email needs verification.
        if (isset($response['requires_verification']) && $response['requires_verification'] === true) {
            $authApi->resendVerificationOtp($request->email);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'requires_verification' => true,
                    'message' => __('Please verify your email before logging in. We have sent an OTP to your email.'),
                    'redirect' => route('verify.email', ['email' => $request->email]),
                ], 403);
            }
            return redirect()->route('verify.email', ['email' => $request->email])
                ->with('status', __('Please verify your email before logging in. We have sent an OTP to your email.'));
        }

        if (isset($response['access_token'])) {
            $user = $response['user'] ?? [];

            // Determine verification status: API field first, then fall back to resend endpoint.
            $isVerified = !empty($user['is_verified']);
            if (!$isVerified) {
                $resendResponse = $authApi->resendVerificationOtp($request->email);
                $resendMessage = $resendResponse['message'] ?? '';
                $isVerified = is_string($resendMessage) && str_contains(strtolower($resendMessage), 'already verified');
            }
            session(['email_verified' => $isVerified]);

            // Redirect based on role: admin users to admin dashboard, customers to user dashboard.
            $redirect = $authApi->isAdmin() ? route('admin.dashboard') : route('user.dashboard');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'verified' => $isVerified,
                    'message' => $isVerified ? __('Login successful. Redirecting to your dashboard...') : __('Login successful. Please complete verification.'),
                    'redirect' => $redirect,
                ]);
            }

            return redirect()->to($redirect);
        }

        $message = $response['message'] ?? 'Invalid credentials. Please try again.';
        $status = $response['status'] ?? 0;

        // API may reject unverified users with 401 Unauthorized.
        // To avoid sending already-verified users to the verify page, check the resend endpoint first.
        if ($status === 401 || (is_string($message) && str_contains(strtolower($message), 'unauthorized'))) {
            $resendResponse = $authApi->resendVerificationOtp($request->email);
            $resendMessage = $resendResponse['message'] ?? '';

            if (is_string($resendMessage) && str_contains(strtolower($resendMessage), 'already verified')) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => __('Invalid credentials. Please try again.'),
                    ], 422);
                }
                return back()->withErrors(['email' => __('Invalid credentials. Please try again.')])
                    ->withInput($request->only('email'));
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'requires_verification' => true,
                    'message' => __('Please verify your email before logging in. We have sent an OTP to your email.'),
                    'redirect' => route('verify.email', ['email' => $request->email]),
                ], 403);
            }

            return redirect()->route('verify.email', ['email' => $request->email])
                ->with('status', __('Please verify your email before logging in. We have sent an OTP to your email.'));
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 422);
        }

        return back()->withErrors(['email' => $message])->withInput($request->only('email'));
    }

    public function logout(AuthApiService $authApi)
    {
        $authApi->logout();
        return redirect()->route('login');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Api\AuthApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        try {
            $validator = Validator::make($request->all(), [
                'email'    => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
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

            try {
                $response = $authApi->login($request->email, $request->password);
            } catch (\Throwable $e) {
                Log::error('Login API request failed', [
                    'email' => $request->email,
                    'error' => $e->getMessage(),
                ]);

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => __('Unable to reach authentication service. Please try again later.'),
                    ], 503);
                }

                return back()->withErrors(['email' => __('Unable to reach authentication service. Please try again later.')])
                    ->withInput($request->only('email'));
            }

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

        // If the backend itself failed, surface a friendly message and log details.
        if ($status >= 500) {
            Log::error('Backend returned server error during login', [
                'email' => $request->email,
                'status' => $status,
                'response' => $response,
            ]);

            $serverMessage = __('Something went wrong on our side. Please try again in a moment.');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $serverMessage,
                ], 500);
            }

            return back()->withErrors(['email' => $serverMessage])->withInput($request->only('email'));
        }

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
        } catch (\Throwable $e) {
            Log::error('Unexpected login error', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $fallbackMessage = __('Something went wrong. Please try again.');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $fallbackMessage,
                ], 500);
            }

            return back()->withErrors(['email' => $fallbackMessage])->withInput($request->only('email'));
        }
    }

    public function logout(AuthApiService $authApi)
    {
        $authApi->logout();
        return redirect()->route('login');
    }
}

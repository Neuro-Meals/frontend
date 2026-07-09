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

            $response = $authApi->login($request->email, $request->password);

            // Backend returned an access token — login succeeded.
            if (isset($response['access_token'])) {
                $user = $response['user'] ?? [];
                $isVerified = !empty($user['is_verified']);

                // Unverified users must complete OTP verification before accessing the app.
                if (!$isVerified) {
                    $authApi->logout();
                    session([
                        'pending_verification_email' => $request->email,
                        'pending_verification_name' => $user['first_name'] ?? '',
                    ]);
                    $redirect = route('verify.email', ['email' => urlencode($request->email)]);

                    Log::info('Login requires email verification', [
                        'email' => $request->email,
                    ]);

                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'requires_verification' => true,
                            'verified' => false,
                            'message' => __('Please verify your email before continuing.'),
                            'redirect' => $redirect,
                        ]);
                    }

                    return redirect()->to($redirect)
                        ->with('status', __('Please verify your email before continuing.'));
                }

                session(['email_verified' => true]);

                $role = $authApi->role();
                $redirect = $authApi->isAdmin() ? route('admin.dashboard') : route('user.dashboard');

                Log::info('Login role redirect', [
                    'email' => $request->email,
                    'role' => $role,
                    'is_admin' => $authApi->isAdmin(),
                    'redirect' => $redirect,
                ]);

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'verified' => true,
                        'message' => __('Login successful. Redirecting to your dashboard...'),
                        'redirect' => $redirect,
                    ]);
                }

                return redirect()->to($redirect);
            }

            // Something else came back from the backend.
            $message = $response['message'] ?? $response['detail'][0]['msg'] ?? __('Invalid credentials. Please try again.');
            $status = $response['status'] ?? 422;

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], $status >= 400 && $status < 600 ? $status : 422);
            }

            return back()->withErrors(['email' => $message])->withInput($request->only('email'));
        } catch (\Throwable $e) {
            Log::error('Login failed', [
                'email' => $request->email,
                'error' => $e->getMessage(),
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

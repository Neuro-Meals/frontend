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

        if (isset($response['access_token'])) {
            $user = $response['user'] ?? [];

            // If user is not verified, redirect to email verification page
            if (empty($user['is_verified'])) {
                $authApi->logout();
                $authApi->resendVerificationOtp($request->email);
                return redirect()->route('verify.email', ['email' => $request->email])
                    ->with('status', __('Please verify your email before logging in. We have sent an OTP to your email.'));
            }

            if (in_array($user['role'] ?? null, ['admin', 'super_admin'])) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('user.dashboard');
        }

        $message = $response['message'] ?? 'Invalid credentials. Please try again.';

        // If the API signals email not verified, redirect to verification page
        if (is_string($message) && (
            str_contains(strtolower($message), 'verify') ||
            str_contains(strtolower($message), 'verified') ||
            str_contains(strtolower($message), 'not verified') ||
            str_contains(strtolower($message), 'unauthorized')
        )) {
            $authApi->resendVerificationOtp($request->email);
            return redirect()->route('verify.email', ['email' => $request->email])
                ->with('status', __('Please verify your email before logging in. We have sent an OTP to your email.'));
        }

        return back()->withErrors(['email' => $message])->withInput($request->only('email'));
    }

    public function logout(AuthApiService $authApi)
    {
        $authApi->logout();
        return redirect()->route('login');
    }
}

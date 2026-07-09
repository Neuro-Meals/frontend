<?php

namespace App\Http\Middleware;

use App\Services\Api\AuthApiService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmailMiddleware
{
    public function __construct(
        private AuthApiService $authApi
    ) {}

    /**
     * Background verification check for protected routes.
     *
     * If a user is authenticated but their email is not verified, they are logged
     * out and redirected to the OTP verification page. The check refreshes the
     * user data from the API behind the scenes so the status is always accurate.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->authApi->check()) {
            return redirect()->route('login');
        }

        $user = $this->authApi->user();
        $email = $user['email'] ?? '';

        // Background verification refresh against the API.
        if (!$this->authApi->isVerified()) {
            $this->authApi->logout();

            session([
                'pending_verification_email' => $email,
                'pending_verification_name' => $user['first_name'] ?? '',
            ]);

            $redirect = route('verify.email', ['email' => urlencode($email)]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'requires_verification' => true,
                    'verified' => false,
                    'message' => __('Please verify your email before continuing.'),
                    'redirect' => $redirect,
                ], 403);
            }

            return redirect()->to($redirect)
                ->with('status', __('Please verify your email before continuing.'));
        }

        session(['email_verified' => true]);

        return $next($request);
    }
}

<?php

namespace App\Services\Api;

class AuthApiService extends BaseApiService
{
    /**
     * Login user and store token + user data in session.
     * API returns: { access_token, token_type, user: LoggedInUser }
     */
    public function login(string $email, string $password): array
    {
        $response = $this->post('auth.login', [], [
            'email' => $email,
            'password' => $password,
        ]);

        if (isset($response['access_token'])) {
            session(['api_token' => $response['access_token']]);
            session(['api_user' => $response['user'] ?? null]);
        }

        return $response;
    }

    /**
     * Register a new user.
     * API returns: UserResponse (no token — user must verify email then login).
     */
    public function register(array $data): array
    {
        return $this->post('auth.register', [], $data);
    }

    /**
     * Verify email with OTP.
     */
    public function verifyEmail(string $email, string $otp): array
    {
        return $this->post('auth.verify_email', [], [
            'email' => $email,
            'otp' => $otp,
        ]);
    }

    /**
     * Resend email verification OTP.
     */
    public function resendVerificationOtp(string $email): array
    {
        return $this->post('auth.resend_verification_otp', [], [
            'email' => $email,
        ]);
    }

    /**
     * Get the currently authenticated user (GET /auth/me).
     * Updates session user data.
     */
    public function me(): array
    {
        $response = $this->get('auth.me');

        if (isset($response['id'])) {
            session(['api_user' => $response]);
        }

        return $response;
    }

    /**
     * Request a password reset OTP.
     */
    public function forgotPassword(string $email): array
    {
        return $this->post('auth.forgot_password', [], [
            'email' => $email,
        ]);
    }

    /**
     * Reset password using OTP.
     */
    public function resetPassword(string $email, string $otp, string $newPassword): array
    {
        return $this->post('auth.reset_password', [], [
            'email' => $email,
            'otp' => $otp,
            'new_password' => $newPassword,
        ]);
    }

    /**
     * Change password for the authenticated user.
     */
    public function changePassword(string $oldPassword, string $newPassword): array
    {
        return $this->post('auth.change_password', [], [
            'old_password' => $oldPassword,
            'new_password' => $newPassword,
        ]);
    }

    /**
     * Logout and clear session.
     */
    public function logout(): array
    {
        session()->forget(['api_token', 'api_user']);

        return ['success' => true];
    }

    /**
     * Check if user is authenticated via API token in session.
     */
    public function check(): bool
    {
        return session()->has('api_token');
    }

    /**
     * Get the authenticated user from session.
     */
    public function user(): ?array
    {
        return session('api_user');
    }

    /**
     * Check if the authenticated user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        $user = $this->user();
        return $user && ($user['role'] ?? null) === $role;
    }

    /**
     * Check if the authenticated user is an admin or super_admin.
     */
    public function isAdmin(): bool
    {
        $user = $this->user();
        return $user && in_array($user['role'] ?? null, ['admin', 'super_admin']);
    }

    /**
     * Check if the user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        $user = $this->user();
        return $user && in_array($permission, $user['permissions'] ?? []);
    }
}

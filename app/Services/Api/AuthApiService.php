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
        session()->forget(['api_token', 'api_user', 'api_user_verified_at', 'email_verified']);

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
     * Extract the normalized role string from user data.
     * Handles role as string, enum value, role_id integer, or nested role object.
     */
    public function role(): ?string
    {
        $user = $this->user();
        if (!$user) {
            return null;
        }

        $rawRole = $user['role'] ?? null;

        // Backend sends UserRole enum serialized as a string (e.g. "customer", "admin").
        if (is_string($rawRole) && !empty($rawRole)) {
            return strtolower($rawRole);
        }

        // Enum object fallback: { value: "admin" }
        if (is_array($rawRole)) {
            foreach (['value', 'name', 'role'] as $key) {
                if (!empty($rawRole[$key]) && is_string($rawRole[$key])) {
                    return strtolower($rawRole[$key]);
                }
            }
        }

        $roleId = $user['role_id'] ?? null;
        if (is_numeric($roleId)) {
            $map = config('api.role_map', [
                1 => 'customer',
                2 => 'admin',
                3 => 'super_admin',
                4 => 'driver',
            ]);
            return $map[(int) $roleId] ?? null;
        }

        return null;
    }

    /**
     * Check if the authenticated user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role() === strtolower($role);
    }

    /**
     * Check if the authenticated user is an admin or super_admin.
     */
    public function isAdmin(): bool
    {
        return in_array($this->role(), ['admin', 'super_admin']);
    }

    /**
     * Check if the authenticated user is a customer.
     */
    public function isCustomer(): bool
    {
        return in_array($this->role(), ['customer', 'user', 'client', null]);
    }

    /**
     * Refresh the authenticated user from the API and check whether the email is verified.
     * This is used as a background verification check on protected routes.
     */
    public function isVerified(): bool
    {
        $user = $this->user();

        if (!$user) {
            return false;
        }

        // Refresh user data from the API periodically (max once per 60 seconds) so that
        // a verification completed on another device or via email link is reflected here.
        $lastCheck = session('api_user_verified_at', 0);
        $now = now()->timestamp;
        $verified = !empty($user['is_verified']);

        if ($now - $lastCheck >= 60) {
            $fresh = $this->me();
            $verified = !empty($fresh['is_verified']);

            // If the API refresh failed, keep the existing session value so a verified
            // user is not locked out during a temporary API outage.
            if (isset($fresh['success']) && $fresh['success'] === false) {
                $verified = !empty($user['is_verified']);
            }

            session([
                'api_user_verified_at' => $now,
            ]);
        }

        // Sync the verified status back into the session so the dashboard always shows the latest state.
        if (isset($user['is_verified']) && (bool) $user['is_verified'] !== $verified) {
            $user['is_verified'] = $verified;
            session(['api_user' => $user]);
        }

        return $verified;
    }

    /**
     * Check if the user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        $user = $this->user();
        return $user && in_array($permission, $user['permissions'] ?? []);
    }

    /**
     * Check if the authenticated user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        $current = $this->role();
        if (!$current) {
            return in_array('customer', $roles, true) || in_array('user', $roles, true);
        }
        return in_array($current, array_map('strtolower', $roles), true);
    }
}

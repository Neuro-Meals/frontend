<?php

namespace App\Services\Api;

class AuthApiService extends BaseApiService
{
    /**
     * Login user and store token.
     */
    public function login(string $email, string $password): array
    {
        $response = $this->post('auth.login', [], [
            'email' => $email,
            'password' => $password,
        ]);

        if (isset($response['token'])) {
            session(['api_token' => $response['token']]);
            session(['api_user' => $response['user'] ?? null]);
        }

        return $response;
    }

    /**
     * Register new user.
     */
    public function register(array $data): array
    {
        $response = $this->post('auth.register', [], $data);

        if (isset($response['token'])) {
            session(['api_token' => $response['token']]);
            session(['api_user' => $response['user'] ?? null]);
        }

        return $response;
    }

    /**
     * Logout and clear session.
     */
    public function logout(): array
    {
        $response = $this->post('auth.logout');

        session()->forget(['api_token', 'api_user']);

        return $response;
    }

    /**
     * Get current user profile.
     */
    public function profile(): array
    {
        return $this->get('auth.profile');
    }

    /**
     * Refresh auth token.
     */
    public function refresh(): array
    {
        $response = $this->post('auth.refresh');

        if (isset($response['token'])) {
            session(['api_token' => $response['token']]);
        }

        return $response;
    }
}

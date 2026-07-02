<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BaseApiService
{
    protected string $baseUrl;
    protected int $timeout;
    protected int $retryAttempts;

    public function __construct()
    {
        $this->baseUrl = config('api.base_url');
        $this->timeout = config('api.timeout', 30);
        $this->retryAttempts = config('api.retry_attempts', 3);
    }

    /**
     * Build full URL from endpoint key.
     * Supports dot notation: 'admin.dashboard.stats'
     */
    protected function endpoint(string $key): string
    {
        $path = config("api.endpoints.{$key}");
        if (!$path) {
            throw new \InvalidArgumentException("API endpoint not found: {$key}");
        }
        return $this->baseUrl . $path;
    }

    /**
     * Replace path parameters in URL.
     */
    protected function buildUrl(string $key, array $params = []): string
    {
        $url = $this->endpoint($key);
        foreach ($params as $param => $value) {
            $url = str_replace("{{$param}}", $value, $url);
        }
        return $url;
    }

    /**
     * Get auth token from session.
     */
    protected function getAuthToken(): ?string
    {
        return session('api_token');
    }

    /**
     * Build headers with auth token.
     */
    protected function headers(array $extra = []): array
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $token = $this->getAuthToken();
        if ($token) {
            $headers['Authorization'] = "Bearer {$token}";
        }

        return array_merge($headers, $extra);
    }

    /**
     * GET request.
     */
    protected function get(string $key, array $params = [], array $query = []): array
    {
        $url = $this->buildUrl($key, $params);

        return $this->request('get', $url, $query);
    }

    /**
     * POST request.
     */
    protected function post(string $key, array $params = [], array $data = []): array
    {
        $url = $this->buildUrl($key, $params);

        return $this->request('post', $url, $data);
    }

    /**
     * PUT request.
     */
    protected function put(string $key, array $params = [], array $data = []): array
    {
        $url = $this->buildUrl($key, $params);

        return $this->request('put', $url, $data);
    }

    /**
     * DELETE request.
     */
    protected function delete(string $key, array $params = []): array
    {
        $url = $this->buildUrl($key, $params);

        return $this->request('delete', $url);
    }

    /**
     * Core HTTP request with retry logic.
     */
    protected function request(string $method, string $url, array $data = []): array
    {
        $attempt = 0;
        $lastError = null;

        while ($attempt < $this->retryAttempts) {
            $attempt++;

            try {
                $response = Http::withHeaders($this->headers())
                    ->timeout($this->timeout)
                    ->{$method}($url, $data);

                if ($response->successful()) {
                    return $response->json();
                }

                if ($response->status() === 401) {
                    Log::warning('API unauthorized', ['url' => $url]);
                    return [
                        'success' => false,
                        'status' => 401,
                        'message' => 'Unauthorized',
                    ];
                }

                if ($response->status() >= 400 && $response->status() < 500) {
                    return [
                        'success' => false,
                        'status' => $response->status(),
                        'message' => $response->json('message', 'Request failed'),
                        'errors' => $response->json('errors'),
                    ];
                }

                $lastError = $response->json('message', 'Server error');

            } catch (\Exception $e) {
                Log::error('API request failed', [
                    'url' => $url,
                    'method' => $method,
                    'attempt' => $attempt,
                    'error' => $e->getMessage(),
                ]);
                $lastError = $e->getMessage();
            }

            if ($attempt < $this->retryAttempts) {
                usleep(500000 * $attempt);
            }
        }

        return [
            'success' => false,
            'status' => 500,
            'message' => $lastError ?? 'Request failed after retries',
        ];
    }
}

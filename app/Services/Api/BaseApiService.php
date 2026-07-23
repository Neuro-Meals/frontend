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
        $token = session('api_token');
        if (!$token) {
            Log::warning('No API token in session', [
                'session_id' => session()->getId(),
                'session_keys' => array_keys(session()->all()),
            ]);
        }
        return $token;
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

        return $this->request('get', $url, $query, true);
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
     * POST multipart request for file uploads.
     *
     * @param array<int, array{name: string, contents: mixed, filename: string}> $files
     */
    protected function postMultipart(string $key, array $params = [], array $files = [], array $extraData = []): array
    {
        $url = $this->buildUrl($key, $params);
        $attempt = 0;
        $lastError = null;

        while ($attempt < $this->retryAttempts) {
            $attempt++;

            try {
                $http = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . ($this->getAuthToken() ?? ''),
                ])
                    ->withOptions([
                        'curl' => [
                            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                            CURLOPT_CONNECTTIMEOUT => 10,
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                        ],
                    ])
                    ->timeout($this->timeout);

                foreach ($files as $file) {
                    $http = $http->attach($file['name'], $file['contents'], $file['filename']);
                }

                foreach ($extraData as $key => $value) {
                    $http = $http->attach($key, $value);
                }

                $response = $http->post($url);

                if ($response->successful()) {
                    return $response->json() ?? ['success' => true];
                }

                if ($response->status() === 401) {
                    Log::warning('API unauthorized', ['url' => $url]);
                    return [
                        'success' => false,
                        'status' => 401,
                        'message' => $response->json('message') ?? $response->json('detail') ?? 'Unauthorized',
                    ];
                }

                $json = $response->json() ?? [];
                return [
                    'success' => false,
                    'status' => $response->status(),
                    'message' => $json['message'] ?? $json['detail'] ?? 'Upload failed',
                ];
            } catch (\Exception $e) {
                Log::error('API upload failed', [
                    'url' => $url,
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
            'message' => $lastError ?? 'Upload failed after retries',
        ];
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
     * PATCH request.
     */
    protected function patch(string $key, array $params = [], array $data = []): array
    {
        $url = $this->buildUrl($key, $params);

        return $this->request('patch', $url, $data);
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
     * For GET requests, $data is treated as query parameters.
     */
    protected function request(string $method, string $url, array $data = [], bool $isQuery = false): array
    {
        $attempt = 0;
        $lastError = null;

        while ($attempt < $this->retryAttempts) {
            $attempt++;

            try {
                $http = Http::withHeaders($this->headers())
                    ->withOptions([
                        'curl' => [
                            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                            CURLOPT_CONNECTTIMEOUT => 10,
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                        ],
                    ])
                    ->timeout($this->timeout);

                if ($isQuery && !empty($data)) {
                    $response = $http->{$method}($url, $data);
                } else {
                    $response = $http->{$method}($url, $data);
                }

                if ($response->successful()) {
                    return $response->json() ?? ['success' => true];
                }

                if ($response->status() === 401) {
                    $json = $response->json() ?? [];
                    Log::warning('API unauthorized', [
                        'url' => $url,
                        'body' => $response->body(),
                    ]);
                    return [
                        'success' => false,
                        'status' => 401,
                        'message' => $json['message'] ?? $json['detail'] ?? 'Unauthorized',
                    ];
                }

                if ($response->status() >= 400 && $response->status() < 500) {
                    $json = $response->json() ?? [];

                    // New standardized format: {success: false, message, error_code, errors}
                    if (isset($json['success']) && $json['success'] === false && isset($json['message'])) {
                        $errors = $json['errors'] ?? [];
                        $errorMap = [];
                        if (is_array($errors) && array_is_list($errors)) {
                            foreach ($errors as $err) {
                                $field = $err['field'] ?? 'error';
                                $errorMap[$field] = $err['message'] ?? 'Validation error';
                            }
                        } elseif (is_array($errors)) {
                            $errorMap = $errors;
                        }
                        return [
                            'success' => false,
                            'status' => $response->status(),
                            'message' => $json['message'],
                            'errors' => $errorMap ?: null,
                            'error_code' => $json['error_code'] ?? null,
                        ];
                    }

                    // Legacy FastAPI validation errors: {detail: [{loc, msg, type}]}
                    $detail = $json['detail'] ?? null;
                    $isValidationList = is_array($detail)
                        && array_is_list($detail)
                        && isset($detail[0])
                        && is_array($detail[0])
                        && isset($detail[0]['loc']);

                    if ($isValidationList) {
                        $errors = [];
                        foreach ($detail as $err) {
                            $field = is_array($err['loc'] ?? []) ? implode('.', array_slice($err['loc'], 1)) : ($err['loc'][0] ?? 'error');
                            $errors[$field] = $err['msg'] ?? 'Validation error';
                        }
                        return [
                            'success' => false,
                            'status' => $response->status(),
                            'message' => 'Validation failed',
                            'errors' => $errors,
                        ];
                    }
                    $detail = $json['detail'] ?? null;
                    $message = $json['message'] ?? null;
                    $extra = [];

                    // FastAPI HTTPException can return detail as a dict
                    // e.g. {"detail": {"message": "...", "subscription_id": 10}}
                    if (is_array($detail) && !array_is_list($detail)) {
                        if (!$message && isset($detail['message'])) {
                            $message = $detail['message'];
                        }
                        $extra = $detail;
                    } elseif (is_string($detail)) {
                        if (!$message) {
                            $message = $detail;
                        }
                    }

                    return [
                        'success' => false,
                        'status' => $response->status(),
                        'message' => $message ?? 'Request failed',
                        'errors' => $json['errors'] ?? null,
                        ...$extra,
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

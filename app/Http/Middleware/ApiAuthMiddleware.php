<?php

namespace App\Http\Middleware;

use App\Services\Api\AuthApiService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    public function __construct(
        private AuthApiService $authApi
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->authApi->check()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}

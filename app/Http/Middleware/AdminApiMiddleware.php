<?php

namespace App\Http\Middleware;

use App\Services\Api\AuthApiService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminApiMiddleware
{
    public function __construct(
        private AuthApiService $authApi
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->authApi->check()) {
            return redirect()->route('login');
        }

        if (!$this->authApi->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        return $next($request);
    }
}

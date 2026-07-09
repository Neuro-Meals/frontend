<?php

namespace App\Http\Middleware;

use App\Services\Api\AuthApiService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DriverApiMiddleware
{
    public function __construct(
        private AuthApiService $authApi
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->authApi->check()) {
            return redirect()->route('login');
        }

        if ($this->authApi->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admins should use the admin dashboard.');
        }

        if (!$this->authApi->hasRole('driver')) {
            return redirect()->route('user.dashboard')
                ->with('error', 'This area is for drivers only.');
        }

        return $next($request);
    }
}

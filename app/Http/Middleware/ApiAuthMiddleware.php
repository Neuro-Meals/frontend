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
            if ($request->expectsJson() || $request->is('upload/*')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized. Please log in again.'], 401);
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}

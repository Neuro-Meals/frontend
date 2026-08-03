<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Api\HealthProfileOptionApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PublicHealthProfileOptionController extends Controller
{
    public function __invoke(
        HealthProfileOptionApiService $healthProfileOptionApi
    ): JsonResponse {
        try {
            $response = $healthProfileOptionApi->publicOptions();

            /*
             * Preserve the FastAPI grouped structure:
             * dietary_preferences, allergies, health_conditions.
             */
            $payload = $response['data'] ?? $response;

            return response()->json([
                'success' => true,
                'data' => [
                    'dietary_preferences' => array_values(
                        $payload['dietary_preferences'] ?? []
                    ),
                    'allergies' => array_values(
                        $payload['allergies'] ?? []
                    ),
                    'health_conditions' => array_values(
                        $payload['health_conditions'] ?? []
                    ),
                ],
            ]);
        } catch (\Throwable $exception) {
            Log::error('Unable to load public health profile options', [
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => __(
                    'Unable to load health profile options.'
                ),
            ], 502);
        }
    }
}

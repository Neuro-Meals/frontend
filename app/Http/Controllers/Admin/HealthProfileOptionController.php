<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Api\HealthProfileOptionApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Throwable;

class HealthProfileOptionController extends Controller
{
    private const TYPES = [
        'dietary_preference',
        'allergy',
        'health_condition',
    ];

    public function index(Request $request, HealthProfileOptionApiService $api): JsonResponse
    {
        try {
            $query = array_filter([
                'option_type' => $request->query('option_type'),
                'is_active' => $request->query('is_active'),
                'search' => $request->query('search'),
                'page' => $request->integer('page', 1),
                'limit' => $request->integer('limit', 500),
            ], static fn ($value) => $value !== null && $value !== '');

            return response()->json($api->adminList($query));
        } catch (Throwable $exception) {
            report($exception);
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }

    public function store(Request $request, HealthProfileOptionApiService $api): JsonResponse
    {
        $payload = $request->validate($this->rules());

        try {
            return response()->json($api->create($payload), 201);
        } catch (Throwable $exception) {
            report($exception);
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }

    public function update(
        Request $request,
        int $optionId,
        HealthProfileOptionApiService $api
    ): JsonResponse {
        $payload = $request->validate($this->rules());

        try {
            return response()->json($api->update($optionId, $payload));
        } catch (Throwable $exception) {
            report($exception);
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }

    public function updateStatus(
        Request $request,
        int $optionId,
        HealthProfileOptionApiService $api
    ): JsonResponse {
        $payload = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        try {
            return response()->json(
                $api->updateStatus($optionId, (bool) $payload['is_active'])
            );
        } catch (Throwable $exception) {
            report($exception);
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }

    public function destroy(
        int $optionId,
        HealthProfileOptionApiService $api
    ): JsonResponse {
        try {
            $api->destroy($optionId);
            return response()->json([
                'success' => true,
                'message' => __('Health profile option deleted successfully.'),
            ]);
        } catch (Throwable $exception) {
            report($exception);
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }

    private function rules(): array
    {
        return [
            'option_type' => ['required', Rule::in(self::TYPES)],
            'value' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9]+(?:_[a-z0-9]+)*$/'],
            'label_en' => ['required', 'string', 'max:150'],
            'label_ar' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:100000'],
        ];
    }
}

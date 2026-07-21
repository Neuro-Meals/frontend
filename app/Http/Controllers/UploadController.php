<?php

namespace App\Http\Controllers;

use App\Services\Api\UploadApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UploadController extends Controller
{
    public function uploadImage(Request $request, UploadApiService $uploadApi)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $result = $uploadApi->images([$request->file('file')]);

        if (empty($result['images'][0]['image_url'])) {
            $message = $result['message'] ?? 'Image upload failed.';
            return response()->json(['success' => false, 'message' => $message], $result['status'] ?? 500);
        }

        return response()->json([
            'success' => true,
            'image_url' => $result['images'][0]['image_url'],
            'original_name' => $result['images'][0]['original_name'] ?? $request->file('file')->getClientOriginalName(),
        ]);
    }

    public function uploadImages(Request $request, UploadApiService $uploadApi)
    {
        $request->validate([
            'files' => 'required',
            'files.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $files = is_array($request->file('files')) ? $request->file('files') : [$request->file('files')];

        $result = $uploadApi->images($files);

        if (empty($result['images'])) {
            $message = $result['message'] ?? 'Image upload failed.';
            return response()->json(['success' => false, 'message' => $message], $result['status'] ?? 500);
        }

        return response()->json([
            'success' => true,
            'count' => count($result['images']),
            'images' => $result['images'],
        ]);
    }

    public function uploadAvatar(Request $request, UploadApiService $uploadApi)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $result = $uploadApi->images([$request->file('file')]);

        if (empty($result['images'][0]['image_url'])) {
            $message = $result['message'] ?? 'Avatar upload failed.';
            return response()->json(['success' => false, 'message' => $message], $result['status'] ?? 500);
        }

        return response()->json([
            'success' => true,
            'image_url' => $result['images'][0]['image_url'],
            'original_name' => $result['images'][0]['original_name'] ?? $request->file('file')->getClientOriginalName(),
        ]);
    }

    public function proxyStatic(Request $request, string $path)
    {
        // Use direct backend URL to bypass nginx which doesn't proxy /static/
        $baseUrl = rtrim(env('API_BASE_URL_DIRECT', 'http://185.237.97.69:8080'), '/');
        $url = $baseUrl . '/static/' . $path;

        try {
            $response = Http::withOptions([
                'curl' => [
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                    CURLOPT_CONNECTTIMEOUT => 10,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                ],
            ])->timeout(15)->get($url);

            if ($response->successful()) {
                return response($response->body(), 200, [
                    'Content-Type' => $response->header('Content-Type') ?: 'image/jpeg',
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }

            return response()->json(['error' => 'File not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch file'], 500);
        }
    }
}

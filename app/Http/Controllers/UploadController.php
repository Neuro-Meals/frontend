<?php

namespace App\Http\Controllers;

use App\Services\Api\UploadApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    private function storeLocal(Request $request, string $inputName = 'file', bool $multiple = false): array
    {
        $uploadDir = public_path('uploads');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $images = [];
        $files = $multiple ? $request->file($inputName) : [$request->file($inputName)];

        foreach ($files as $file) {
            $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $filename = Str::random(32) . '.' . $extension;
            $file->move($uploadDir, $filename);

            $images[] = [
                'original_name' => $file->getClientOriginalName(),
                'image_url' => '/uploads/' . $filename,
            ];
        }

        return $images;
    }

    public function uploadImage(Request $request, UploadApiService $uploadApi)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $images = $this->storeLocal($request, 'file');

        if (empty($images[0]['image_url'])) {
            return response()->json(['success' => false, 'message' => 'Image upload failed.'], 500);
        }

        return response()->json([
            'success' => true,
            'image_url' => $images[0]['image_url'],
            'original_name' => $images[0]['original_name'],
        ]);
    }

    public function uploadImages(Request $request, UploadApiService $uploadApi)
    {
        $request->validate([
            'files' => 'required',
            'files.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $files = is_array($request->file('files')) ? $request->file('files') : [$request->file('files')];
        $images = [];

        $uploadDir = public_path('uploads');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($files as $file) {
            $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $filename = Str::random(32) . '.' . $extension;
            $file->move($uploadDir, $filename);

            $images[] = [
                'original_name' => $file->getClientOriginalName(),
                'image_url' => '/uploads/' . $filename,
            ];
        }

        if (empty($images)) {
            return response()->json(['success' => false, 'message' => 'Image upload failed.'], 500);
        }

        return response()->json([
            'success' => true,
            'count' => count($images),
            'images' => $images,
        ]);
    }

    public function uploadAvatar(Request $request, UploadApiService $uploadApi)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $images = $this->storeLocal($request, 'file');

        if (empty($images[0]['image_url'])) {
            return response()->json(['success' => false, 'message' => 'Avatar upload failed.'], 500);
        }

        return response()->json([
            'success' => true,
            'image_url' => $images[0]['image_url'],
            'original_name' => $images[0]['original_name'],
        ]);
    }

    public function proxyStatic(Request $request, string $path)
    {
        $baseUrl = rtrim(env('API_BASE_URL', 'https://app.nutriomeals.com'), '/');
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

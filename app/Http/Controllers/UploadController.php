<?php

namespace App\Http\Controllers;

use App\Services\Api\UploadApiService;
use Illuminate\Http\Request;

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
            'files' => 'required|array',
            'files.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $result = $uploadApi->images($request->file('files'));

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
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
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
}

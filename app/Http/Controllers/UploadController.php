<?php

namespace App\Http\Controllers;

use App\Services\Api\UploadApiService;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function uploadImage(Request $request, UploadApiService $uploadApi)
    {
        if ($response = $this->phpUploadLimitError($request, 'file')) {
            return $response;
        }

        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
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
        if ($response = $this->phpUploadLimitError($request, 'files')) {
            return $response;
        }

        $request->validate([
            'files' => 'required|array',
            'files.*' => 'image|mimes:jpg,jpeg,png,webp|max:10240',
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

    /**
     * Detect uploads rejected by PHP itself (upload_max_filesize / post_max_size)
     * and return a clear error instead of a confusing validation failure.
     */
    private function phpUploadLimitError(Request $request, string $field)
    {
        // If the request declared content but PHP dropped the file(s), the
        // upload exceeded upload_max_filesize or post_max_size.
        $contentLength = (int) $request->server('CONTENT_LENGTH', 0);
        $postMax = $this->iniBytes(ini_get('post_max_size'));

        if ($postMax > 0 && $contentLength > $postMax) {
            return response()->json([
                'success' => false,
                'message' => __('The uploaded file is too large. Maximum allowed size is :max MB.', ['max' => 10]),
            ], 413);
        }

        $files = $request->file($field);
        $files = is_array($files) ? $files : ($files ? [$files] : []);

        foreach ($files as $file) {
            if (! $file->isValid()) {
                $error = $file->getError();
                if (in_array($error, [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)) {
                    return response()->json([
                        'success' => false,
                        'message' => __('The uploaded file is too large. Maximum allowed size is :max MB.', ['max' => 10]),
                    ], 413);
                }

                return response()->json([
                    'success' => false,
                    'message' => __('File upload failed. Please try again.'),
                ], 400);
            }
        }

        return null;
    }

    private function iniBytes(string $value): int
    {
        $value = trim($value);
        if ($value === '' || $value === '-1') {
            return 0;
        }

        $unit = strtolower(substr($value, -1));
        $number = (int) $value;

        return match ($unit) {
            'g' => $number * 1024 * 1024 * 1024,
            'm' => $number * 1024 * 1024,
            'k' => $number * 1024,
            default => $number,
        };
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
    private const DISK = 'public';
    private const UPLOAD_PATH = 'uploads';

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $file = $request->file('file');
        $url = $this->storeFile($file);

        if (!$url) {
            return response()->json(['success' => false, 'message' => 'Upload failed'], 500);
        }

        return response()->json([
            'success' => true,
            'image_url' => $url,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function uploadImages(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $uploaded = [];
        foreach ($request->file('files') as $file) {
            $url = $this->storeFile($file);
            if ($url) {
                $uploaded[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'image_url' => $url,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'count' => count($uploaded),
            'images' => $uploaded,
        ]);
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $file = $request->file('file');
        $url = $this->storeFile($file, 'avatars');

        if (!$url) {
            return response()->json(['success' => false, 'message' => 'Avatar upload failed'], 500);
        }

        return response()->json([
            'success' => true,
            'image_url' => $url,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    private function storeFile($file, string $subFolder = ''): ?string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            return null;
        }

        $folder = self::UPLOAD_PATH . ($subFolder ? '/' . $subFolder : '');
        $filename = Str::uuid()->toString() . '.' . $extension;

        $stored = Storage::disk(self::DISK)->putFileAs($folder, $file, $filename);

        if (!$stored) {
            return null;
        }

        return Storage::disk(self::DISK)->url($stored);
    }
}

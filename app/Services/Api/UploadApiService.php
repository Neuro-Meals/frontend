<?php

namespace App\Services\Api;

use Illuminate\Http\UploadedFile;

class UploadApiService extends BaseApiService
{
    /**
     * Upload one or more images to the backend API.
     *
     * @param array<int, UploadedFile> $files
     */
    public function images(array $files): array
    {
        $prepared = [];
        foreach ($files as $file) {
            $prepared[] = [
                'name' => 'files',
                'contents' => $file->get(),
                'filename' => $file->getClientOriginalName(),
            ];
        }

        $result = $this->postMultipart('uploads.images', [], $prepared);

        // Keep image URLs relative so they are served through Laravel's /static/{path} proxy.
        // The backend nginx doesn't serve /static/ directly, so we proxy via Laravel.

        return $result;
    }
}

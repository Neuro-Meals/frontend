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

        // Convert relative backend URLs to absolute URLs using the API base URL.
        if (!empty($result['images']) && is_array($result['images'])) {
            foreach ($result['images'] as $index => $image) {
                if (!empty($image['image_url']) && str_starts_with($image['image_url'], '/')) {
                    $result['images'][$index]['image_url'] = rtrim($this->baseUrl, '/') . $image['image_url'];
                }
            }
        }

        return $result;
    }
}

<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Convert uploaded image to WebP and store it.
     * Returns the storage-relative path (e.g. "products/abc123.webp").
     */
    public function storeAsWebp(UploadedFile $file, string $folder, int $quality = 80): string
    {
        $imageData = file_get_contents($file->getRealPath());
        $source = imagecreatefromstring($imageData);

        if ($source === false) {
            // GD ვერ ახდენს parse-ს — შევინახოთ ორიგინალი
            return $file->store($folder, 'public');
        }

        $filename = Str::uuid() . '.webp';
        $relativePath = $folder . '/' . $filename;
        $fullPath = Storage::disk('public')->path($relativePath);

        // Create folder if needed
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        imagewebp($source, $fullPath, $quality);
        imagedestroy($source);

        return $relativePath;
    }

    /**
     * Convert an existing image file on disk to WebP in-place.
     * Deletes the original and returns the new relative path.
     */
    public function convertExistingToWebp(string $relativePath, int $quality = 80): string
    {
        $fullPath = Storage::disk('public')->path($relativePath);

        if (!file_exists($fullPath)) {
            return $relativePath;
        }

        // Already webp
        if (strtolower(pathinfo($fullPath, PATHINFO_EXTENSION)) === 'webp') {
            return $relativePath;
        }

        $imageData = @file_get_contents($fullPath);
        if ($imageData === false) {
            return $relativePath;
        }

        $source = @imagecreatefromstring($imageData);
        if ($source === false) {
            return $relativePath;
        }

        $newRelativePath = preg_replace('/\.[^.]+$/', '.webp', $relativePath);
        $newFullPath = Storage::disk('public')->path($newRelativePath);

        imagewebp($source, $newFullPath, $quality);
        imagedestroy($source);

        // Delete original only if conversion succeeded
        if (file_exists($newFullPath) && filesize($newFullPath) > 0) {
            @unlink($fullPath);
            return $newRelativePath;
        }

        return $relativePath;
    }
}

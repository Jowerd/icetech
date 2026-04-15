<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    // Max dimensions per folder type
    private const MAX_DIMENSIONS = [
        'slides'          => [1920, 900],
        'products'        => [900,  900],
        'category_images' => [800,  800],
        'blog'            => [1200, 800],
        'default'         => [1200, 1200],
    ];

    /**
     * Convert uploaded image to WebP, resize if needed, and store it.
     */
    public function storeAsWebp(UploadedFile $file, string $folder, int $quality = 82): string
    {
        $imageData = file_get_contents($file->getRealPath());
        $source    = imagecreatefromstring($imageData);

        if ($source === false) {
            return $file->store($folder, 'public');
        }

        $source = $this->resizeIfNeeded($source, $folder);

        $filename     = Str::uuid() . '.webp';
        $relativePath = $folder . '/' . $filename;
        $fullPath     = Storage::disk('public')->path($relativePath);

        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        imagewebp($source, $fullPath, $quality);
        imagedestroy($source);

        return $relativePath;
    }

    /**
     * Convert an existing image on disk to WebP with resize.
     * Deletes the original and returns the new relative path.
     */
    public function convertExistingToWebp(string $relativePath, int $quality = 82): string
    {
        $fullPath = Storage::disk('public')->path($relativePath);

        if (!file_exists($fullPath)) {
            return $relativePath;
        }

        if (strtolower(pathinfo($fullPath, PATHINFO_EXTENSION)) === 'webp') {
            // Already webp — still resize if oversized
            $this->resizeWebpInPlace($fullPath, $relativePath, $quality);
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

        $folder = dirname($relativePath);
        $source = $this->resizeIfNeeded($source, $folder);

        $newRelativePath = preg_replace('/\.[^.]+$/', '.webp', $relativePath);
        $newFullPath     = Storage::disk('public')->path($newRelativePath);

        imagewebp($source, $newFullPath, $quality);
        imagedestroy($source);

        if (file_exists($newFullPath) && filesize($newFullPath) > 0) {
            @unlink($fullPath);
            return $newRelativePath;
        }

        return $relativePath;
    }

    // ---------------------------------------------------------------

    private function resizeIfNeeded(\GdImage $source, string $folder): \GdImage
    {
        [$maxW, $maxH] = self::MAX_DIMENSIONS[$folder] ?? self::MAX_DIMENSIONS['default'];

        $w = imagesx($source);
        $h = imagesy($source);

        if ($w <= $maxW && $h <= $maxH) {
            return $source;
        }

        $ratio  = min($maxW / $w, $maxH / $h);
        $newW   = (int) round($w * $ratio);
        $newH   = (int) round($h * $ratio);

        $resized = imagecreatetruecolor($newW, $newH);

        // Preserve transparency (PNG/WebP)
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
        imagefilledrectangle($resized, 0, 0, $newW, $newH, $transparent);

        imagecopyresampled($resized, $source, 0, 0, 0, 0, $newW, $newH, $w, $h);
        imagedestroy($source);

        return $resized;
    }

    private function resizeWebpInPlace(string $fullPath, string $relativePath, int $quality): void
    {
        $data   = @file_get_contents($fullPath);
        $source = $data ? @imagecreatefromstring($data) : false;
        if ($source === false) return;

        $folder  = dirname($relativePath);
        $resized = $this->resizeIfNeeded($source, $folder);

        // Only rewrite if actually resized (resizeIfNeeded destroys original only when resizing)
        if ($resized !== $source) {
            imagewebp($resized, $fullPath, $quality);
            imagedestroy($resized);
        } else {
            imagedestroy($resized);
        }
    }
}

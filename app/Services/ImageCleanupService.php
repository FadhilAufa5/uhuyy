<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageCleanupService
{
    /**
     * Delete image files from storage after successful database save
     *
     * @param array $imagePaths - Array of relative paths from storage/app/public/
     * @return array - Stats about deletion process
     */
    public static function deleteImagesAfterDatabaseSave(array $imagePaths): array
    {
        $stats = [
            'total' => count($imagePaths),
            'deleted' => 0,
            'failed' => 0,
            'skipped' => 0,
            'total_size_freed' => 0,
        ];

        foreach ($imagePaths as $imagePath) {
            try {
                $fullPath = storage_path('app/public/' . $imagePath);

                // Safety checks
                if (!file_exists($fullPath)) {
                    Log::warning("Image file not found for deletion: {$fullPath}");
                    $stats['skipped']++;
                    continue;
                }

                // Ensure it's an image file
                $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
                $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
                
                if (!in_array($extension, $allowedExtensions)) {
                    Log::warning("File is not an image, skipping deletion: {$fullPath}");
                    $stats['skipped']++;
                    continue;
                }

                // Ensure file is in branches directory (security check)
                if (!str_contains($imagePath, 'branches/')) {
                    Log::error("Security: Attempted to delete file outside branches directory: {$imagePath}");
                    $stats['failed']++;
                    continue;
                }

                // Get file size before deletion
                $fileSize = filesize($fullPath);

                // Delete the file
                if (unlink($fullPath)) {
                    $stats['deleted']++;
                    $stats['total_size_freed'] += $fileSize;
                    
                    Log::debug("Image deleted from storage", [
                        'file' => basename($imagePath),
                        'size' => self::formatBytes($fileSize),
                    ]);
                } else {
                    $stats['failed']++;
                    Log::error("Failed to delete image file: {$fullPath}");
                }

            } catch (\Exception $e) {
                $stats['failed']++;
                Log::error("Exception while deleting image: {$e->getMessage()}", [
                    'file_path' => $imagePath,
                    'exception' => $e->getMessage(),
                ]);
            }
        }

        // Log summary
        if ($stats['deleted'] > 0) {
            Log::info("✅ Images successfully deleted from storage", [
                'total_images' => $stats['total'],
                'deleted' => $stats['deleted'],
                'failed' => $stats['failed'],
                'skipped' => $stats['skipped'],
                'storage_freed' => self::formatBytes($stats['total_size_freed']),
            ]);
        }

        return $stats;
    }

    /**
     * Convert file to base64 for database storage
     *
     * @param string $filePath - Full path to file
     * @return array - ['data' => base64, 'mime' => mime_type, 'size' => file_size]
     */
    public static function fileToBase64(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new \Exception("File not found: {$filePath}");
        }

        $fileContent = file_get_contents($filePath);
        $base64 = base64_encode($fileContent);
        $mimeType = mime_content_type($filePath);
        $fileSize = filesize($filePath);

        return [
            'data' => $base64,
            'mime' => $mimeType,
            'size' => $fileSize,
            'filename' => basename($filePath),
        ];
    }

    /**
     * Convert base64 back to displayable image
     *
     * @param array $imageData - Array with 'data' and 'mime' keys
     * @return string - Data URI for img src
     */
    public static function base64ToDataUri(array $imageData): string
    {
        if (empty($imageData['data']) || empty($imageData['mime'])) {
            return '';
        }

        return "data:{$imageData['mime']};base64,{$imageData['data']}";
    }

    /**
     * Format bytes to human-readable size
     *
     * @param int $bytes
     * @return string
     */
    public static function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        return number_format($bytes / pow(1024, $power), 2) . ' ' . $units[$power];
    }

    /**
     * Get total storage size of images
     *
     * @param array $imagePaths
     * @return int - Total bytes
     */
    public static function getTotalStorageSize(array $imagePaths): int
    {
        $totalSize = 0;

        foreach ($imagePaths as $imagePath) {
            $fullPath = storage_path('app/public/' . $imagePath);
            if (file_exists($fullPath)) {
                $totalSize += filesize($fullPath);
            }
        }

        return $totalSize;
    }
}

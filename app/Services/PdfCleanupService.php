<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PdfCleanupService
{
    /**
     * Delete PDF file after successful conversion
     *
     * @param string $filePath - Relative path from storage/app/public/
     * @return bool
     */
    public static function deletePdfAfterConversion(string $filePath): bool
    {
        try {
            // Get full path
            $fullPath = storage_path('app/public/' . $filePath);

            // Safety checks
            if (!file_exists($fullPath)) {
                Log::warning("PDF file not found for deletion: {$fullPath}");
                return false;
            }

            // Ensure it's actually a PDF file
            $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            if ($extension !== 'pdf') {
                Log::warning("File is not a PDF, skipping deletion: {$fullPath}");
                return false;
            }

            // Ensure file is in branches directory (security check)
            if (!str_contains($filePath, 'branches/')) {
                Log::error("Security: Attempted to delete file outside branches directory: {$filePath}");
                return false;
            }

            // Delete the file
            if (unlink($fullPath)) {
                Log::info("✅ PDF successfully deleted after conversion", [
                    'file_path' => $filePath,
                    'full_path' => $fullPath,
                    'deleted_at' => now()->toDateTimeString(),
                ]);
                return true;
            }

            Log::error("Failed to delete PDF file: {$fullPath}");
            return false;

        } catch (\Exception $e) {
            Log::error("Exception while deleting PDF: {$e->getMessage()}", [
                'file_path' => $filePath,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Get file size before deletion (for logging)
     *
     * @param string $filePath
     * @return string
     */
    public static function getFileSizeFormatted(string $filePath): string
    {
        try {
            $fullPath = storage_path('app/public/' . $filePath);
            if (file_exists($fullPath)) {
                $bytes = filesize($fullPath);
                $units = ['B', 'KB', 'MB', 'GB'];
                $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
                return number_format($bytes / pow(1024, $power), 2) . ' ' . $units[$power];
            }
        } catch (\Exception $e) {
            Log::error("Error getting file size: {$e->getMessage()}");
        }
        return 'Unknown';
    }
}

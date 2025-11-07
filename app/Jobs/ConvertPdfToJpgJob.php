<?php

namespace App\Jobs;

use App\Models\Branch;
use App\Services\PdfCleanupService;
use App\Services\ImageCleanupService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class ConvertPdfToJpgJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Branch $branch;

    public function __construct(Branch $branch)
    {
        $this->branch = $branch;
    }

    public function handle(): void
    {
        try {
            $filePath = storage_path('app/public/' . $this->branch->file_path);
            $baseFileName = pathinfo($filePath, PATHINFO_FILENAME);
            $saveDir = storage_path('app/public/branches/');

            if (!file_exists($filePath)) {
                throw new \Exception("PDF file not found: " . $filePath);
            }

            if (!is_dir($saveDir)) {
                mkdir($saveDir, 0755, true);
            }

            $imagePaths = [];

            // Konversi PDF ke PNG menggunakan Imagick
            $imagick = new \Imagick();
            $imagick->setResolution(200, 200);
            $imagick->readImage($filePath);

            foreach ($imagick as $index => $page) {
                $page->setImageFormat('png');
                $imageFileName = "{$baseFileName}_page_" . ($index + 1) . ".png";
                $imageFullPath = $saveDir . $imageFileName;
                $page->writeImage($imageFullPath);
                $imagePaths[] = "branches/" . $imageFileName;
            }

            $imagick->clear();
            $imagick->destroy();

            // Convert images to base64 for database storage
            $imagesDataForDb = [];
            $storageSize = 0;

            foreach ($imagePaths as $imagePath) {
                $fullImagePath = storage_path('app/public/' . $imagePath);
                try {
                    $imageData = ImageCleanupService::fileToBase64($fullImagePath);
                    $imagesDataForDb[] = [
                        'filename' => $imageData['filename'],
                        'mime' => $imageData['mime'],
                        'size' => $imageData['size'],
                        'data' => $imageData['data'],
                    ];
                    $storageSize += $imageData['size'];
                } catch (\Exception $e) {
                    \Log::error("Failed to convert image to base64: {$e->getMessage()}");
                }
            }

            // Update database dengan images di database dan paths untuk backward compatibility
            $this->branch->update([
                'images_data'       => json_encode($imagesDataForDb), // Store in database
                'image_gallery'     => json_encode($imagePaths),      // Keep for backward compatibility
                'image_path'        => $imagePaths[0] ?? null,        // Keep for backward compatibility
                'conversion_status' => 'selesai',
            ]);

            // Log conversion stats
            $pdfSize = PdfCleanupService::getFileSizeFormatted($this->branch->file_path);
            \Log::info("📄 PDF conversion completed", [
                'branch_id' => $this->branch->id,
                'file_name' => basename($this->branch->file_path),
                'pdf_size' => $pdfSize,
                'pages_converted' => count($imagePaths),
                'images_in_database' => count($imagesDataForDb),
                'database_size' => ImageCleanupService::formatBytes($storageSize),
            ]);

            // Auto-delete images from storage after saving to database
            $deleteStats = ImageCleanupService::deleteImagesAfterDatabaseSave($imagePaths);
            
            \Log::info("🗑️ Images cleanup completed", [
                'deleted_files' => $deleteStats['deleted'],
                'storage_freed' => ImageCleanupService::formatBytes($deleteStats['total_size_freed']),
            ]);

            // Auto-delete PDF setelah konversi berhasil
            PdfCleanupService::deletePdfAfterConversion($this->branch->file_path);

        } catch (\Exception $e) {
            \Log::error("Gagal konversi PDF ke PNG untuk Branch ID {$this->branch->id}: " . $e->getMessage());

            $this->branch->update([
                'conversion_status' => 'gagal',
            ]);

            // Jangan hapus PDF jika konversi gagal (untuk debugging/retry)
        }
    }
}

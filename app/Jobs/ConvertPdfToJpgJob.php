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

            if (!file_exists($filePath)) {
                throw new \Exception("PDF file not found: " . $filePath);
            }

            $startTime = microtime(true);

          
            $imagick = new \Imagick();
            $imagick->setResolution(150, 150); 
            $imagick->readImage($filePath);

            $imagesDataForDb = [];
            $storageSize = 0;

            foreach ($imagick as $index => $page) {
                // Use JPG format with compression for smaller file sizes
                $page->setImageFormat('jpg');
                $page->setImageCompressionQuality(85); // Good balance between quality and size
                
                // Get image blob directly without writing to disk
                $imageBlob = $page->getImageBlob();
                $blobSize = strlen($imageBlob);
                
                // Convert directly to base64
                $base64Data = base64_encode($imageBlob);
                
                $imagesDataForDb[] = [
                    'filename' => "{$baseFileName}_page_" . ($index + 1) . ".jpg",
                    'mime' => 'image/jpeg',
                    'size' => $blobSize,
                    'data' => $base64Data,
                ];
                
                $storageSize += $blobSize;
            }

            $imagick->clear();
            $imagick->destroy();

            // Update database dengan images di database
            $this->branch->update([
                'images_data'       => json_encode($imagesDataForDb),
                'image_gallery'     => null, // No longer needed
                'image_path'        => null, // No longer needed
                'conversion_status' => 'selesai',
            ]);

            $processingTime = round(microtime(true) - $startTime, 2);

            // Log conversion stats
            $pdfSize = PdfCleanupService::getFileSizeFormatted($this->branch->file_path);
            \Log::info("📄 PDF conversion completed (OPTIMIZED)", [
                'branch_id' => $this->branch->id,
                'file_name' => basename($this->branch->file_path),
                'pdf_size' => $pdfSize,
                'pages_converted' => count($imagesDataForDb),
                'database_size' => ImageCleanupService::formatBytes($storageSize),
                'processing_time' => "{$processingTime}s",
            ]);

            // Auto-delete PDF setelah konversi berhasil
            PdfCleanupService::deletePdfAfterConversion($this->branch->file_path);

            // Dispatch event untuk refresh UI
            event(new \App\Events\BranchConversionCompleted($this->branch));
            
            // Set cache flag untuk trigger refresh
            \Cache::put('branch_conversion_completed_' . $this->branch->user_id, time(), 60);

        } catch (\Exception $e) {
            \Log::error("Gagal konversi PDF ke JPG untuk Branch ID {$this->branch->id}: " . $e->getMessage());

            $this->branch->update([
                'conversion_status' => 'gagal',
            ]);

            // Jangan hapus PDF jika konversi gagal (untuk debugging/retry)
        }
    }
}

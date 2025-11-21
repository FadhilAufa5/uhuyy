<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Branch;
use App\Services\ImageCleanupService;
use Illuminate\Support\Facades\Cache;

class GuestHero extends Component
{
    public $images = [];
    public $branches = [];
    public $lastRefreshCheck = 0;

    public function mount()
    {
        $this->loadBranches();
        $this->lastRefreshCheck = time();
    }

    public function checkForUpdates()
    {
        // Check all users for updates
        $users = \App\Models\User::pluck('id');
        
        foreach ($users as $userId) {
            $cacheKey = 'branch_conversion_completed_' . $userId;
            $conversionTime = Cache::get($cacheKey);
            
            if ($conversionTime && $conversionTime > $this->lastRefreshCheck) {
                $this->lastRefreshCheck = time();
                $this->loadBranches();
                break;
            }
        }
    }

    public function loadBranches()
    {
        $this->branches = Branch::where('status', 'aktif')
            ->where('conversion_status', 'selesai')
            ->orderByDesc('created_at')
            ->get();

        $this->images = [];
        
        foreach ($this->branches as $branch) {
            // Priority 1: Get images from database (base64)
            if ($branch->hasImagesInDatabase()) {
                $imagesData = json_decode($branch->images_data, true);
                if (is_array($imagesData)) {
                    foreach ($imagesData as $imageData) {
                        if (!empty($imageData['data']) && !empty($imageData['mime'])) {
                            $this->images[] = ImageCleanupService::base64ToDataUri($imageData);
                        }
                    }
                }
            }
            // Priority 2: Fallback to storage paths (backward compatibility)
            elseif ($branch->image_gallery) {
                $gallery = json_decode($branch->image_gallery, true);
                if (is_array($gallery)) {
                    foreach ($gallery as $path) {
                        $this->images[] = asset('storage/' . $path);
                    }
                }
            } elseif ($branch->image_path) {
                $this->images[] = asset('storage/' . $branch->image_path);
            }
        }
    }

    public function render()
    {
        return view('livewire.guest-hero');
    }
}

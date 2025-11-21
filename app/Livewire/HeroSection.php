<?php
namespace App\Livewire;
use Livewire\Component;
use App\Models\Branch;
use Illuminate\Support\Facades\Cache;

class HeroSection extends Component
{
    public ?Branch $branch = null;
    public $lastRefreshCheck = 0;

    protected $listeners = ['refresh-hero' => 'loadBranch'];

    public function mount()
    {
        $this->loadBranch();
        $this->lastRefreshCheck = time();
    }

    public function checkForUpdates()
    {
        // Check all users for updates (hero is public)
        $users = \App\Models\User::pluck('id');
        
        foreach ($users as $userId) {
            $cacheKey = 'branch_conversion_completed_' . $userId;
            $conversionTime = Cache::get($cacheKey);
            
            if ($conversionTime && $conversionTime > $this->lastRefreshCheck) {
                $this->lastRefreshCheck = time();
                $this->loadBranch();
                break;
            }
        }
    }

    public function loadBranch()
    {
        $this->branch = Branch::where('status', 'aktif')
            ->where('conversion_status', 'selesai')
            ->latest()
            ->first();
    }

    public function render()
    {
        return view('livewire.hero-section');
    }
}


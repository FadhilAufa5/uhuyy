<?php

namespace App\Livewire\Branches;

use Livewire\Component;
use App\Models\Branch;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;

#[On("refresh-branch-table")]
#[On("record-deleted")]
class Table extends Component
{
    use WithPagination;
    
    public $lastRefreshCheck = 0;
    public $pollingInterval = 5000; // milliseconds

    public function mount()
    {
        $this->lastRefreshCheck = time();
        $this->updatePollingInterval();
    }

    public function checkForUpdates()
    {
        $cacheKey = 'branch_conversion_completed_' . auth()->id();
        $conversionTime = Cache::get($cacheKey);
        
        if ($conversionTime && $conversionTime > $this->lastRefreshCheck) {
            $this->lastRefreshCheck = time();
            Cache::forget($cacheKey);
            session()->flash('message', 'Konversi file berhasil diselesaikan!');
            
            // Trigger full refresh
            $this->resetPage();
        }
        
        // Update polling interval based on processing status
        $this->updatePollingInterval();
    }

    protected function updatePollingInterval()
    {
        $hasProcessing = Branch::where('user_id', auth()->id())
            ->where('conversion_status', 'proses')
            ->exists();
        
        // Fast polling (1s) if processing, slow polling (5s) if idle
        $this->pollingInterval = $hasProcessing ? 1000 : 5000;
    }

    public function toggleStatus($branchId)
    {
        $branch = Branch::where('id', $branchId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $branch->status = $branch->status === 'aktif' ? 'tidak aktif' : 'aktif';
        $branch->save();
        
        $statusText = $branch->status === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('message', "File berhasil {$statusText}");
    }

    public function render()
    {
        $currentUser = auth()->id();
        $branches = Branch::where('user_id', $currentUser)
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        // Check if there are any branches being processed
        $hasProcessingBranches = Branch::where('user_id', $currentUser)
            ->where('conversion_status', 'proses')
            ->exists();

        return view('livewire.branches.table', [
            'branches' => $branches,
            'hasProcessingBranches' => $hasProcessingBranches,
        ]);
    }
}

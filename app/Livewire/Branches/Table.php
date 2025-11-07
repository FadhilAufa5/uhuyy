<?php

namespace App\Livewire\Branches;

use Livewire\Component;
use App\Models\Branch;
use Livewire\Attributes\On;

#[On("refresh-branch-table")]
#[On("record-deleted")]
class Table extends Component
{
    public function render()
    {
        $currentUser = auth()->id();
        $branches = Branch::where('user_id', $currentUser)
            ->orderBy('id', 'desc')
            ->paginate(10);     

        return view('livewire.branches.table', [
            'branches' => $branches,
        ]);
    }
}

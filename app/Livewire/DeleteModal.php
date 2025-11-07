<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Branch;

class DeleteModal extends Component
{
    public $branchId;

    public function delete()
    {
        $branch = Branch::findOrFail($this->branchId);
        $branch->delete();

        session()->flash('message', 'Branch deleted successfully!');
        $this->emit('refreshBranches'); // Emit untuk refresh data setelah delete
    }

    public function render()
    {
        return view('livewire.delete-modal');
    }
}

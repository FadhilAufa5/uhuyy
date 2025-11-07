<?php
namespace App\Livewire;
use Livewire\Component;
use App\Models\Branch;

class HeroSection extends Component
{
    public ?Branch $branch = null;

    protected $listeners = ['refresh-hero' => 'loadBranch'];

    public function mount()
    {
        $this->loadBranch();
    }

    public function loadBranch()
    {
        $this->branch = Branch::where('status', 'aktif')->latest()->first();
    }

    public function render()
    {
        return view('livewire.hero-section');
    }
}


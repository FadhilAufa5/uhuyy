<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\WithFileUploads;

class BranchForm extends Form
{
    use WithFileUploads;

    public $file_path;

    public function rules(): array
    {
        return [
            'file_path' => 'required|file|mimes:pdf|max:10240', // max 10MB
        ];
    }
}

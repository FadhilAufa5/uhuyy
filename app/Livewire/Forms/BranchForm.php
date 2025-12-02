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
            'file_path' => 'required|file|mimes:pdf|max:20480', // max 20MB (20480 KB)
        ];
    }
}

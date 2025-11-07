<?php

namespace App\Livewire\Forms;

use App\Models\PersonsInCharge;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Livewire\Form;

class PersonsInChargeForm extends Form
{
    public $vendor_id = null;
    public ?array $responsibilities = ['owner', 'leader', 'marketing', 'finance'];
    public ?array $pics = [];
    public ?string $name = null;
    public ?string $email = null;
    public ?string $phone = null;

    public function mount(): void
    {
        if (!Auth::check()) {
            dd('User not authenticated');
        }
        $this->vendor_id = Auth::user()->vendors()->first();
    }


    public function setPics(): void
    {
        $user = auth()->user();
        $vendor = $user->vendors()?->first();

        $existingPics = PersonsInCharge::where('vendor_id', $vendor?->id)->get()->keyBy('responsibility') ?? null;

        $this->pics = []; // Reset array

        if ($existingPics) {
            foreach ($this->responsibilities as $responsibility) {
                // Cek apakah sudah ada PIC untuk responsibility ini
                $pic = $existingPics[$responsibility] ?? null;

                $this->pics[$responsibility] = [
                    'vendor_id' => $vendor?->id,
                    'responsibility' => $responsibility,
                    'name' => $pic->name ?? '',   // Gunakan nilai jika ada, jika tidak kosongkan
                    'email' => $pic->email ?? '',
                    'phone' => $pic->phone ?? '',
                ];
            }
        }
    }

    public function rules(): array
    {
        return [
            'pics.*.name' => 'required|string',
            'pics.*.email' => 'required|email',
            'pics.*.phone' => 'required|string',
        ];
    }

    public function save(): void
    {
        try {
            $validated = $this->validate();
            foreach ($validated['pics'] as &$pic) {
                $pic['vendor_id'] = $this->vendor_id;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            dump($e->errors()); // Lihat error validasi
        }

        foreach ($this->pics as $responsibility => $personData) {
            PersonsInCharge::updateOrCreate(
                [
                    'vendor_id' => $this->vendor_id,
                    'responsibility' => $responsibility,
                ],
                [
                    'name' => $personData['name'],
                    'email' => $personData['email'],
                    'phone' => $personData['phone'],
                ]
            );
        }

        $this->reset();
    }
}

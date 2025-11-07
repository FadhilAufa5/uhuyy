<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Locked;
use Livewire\Form;

class UserForm extends Form
{
    public ?User $user = null;

    #[Locked]
    public $id;

    public
        $name = '',
        $username = '',
        $email = '',
        // $branch_id = null,
        $password = '',
        $password_confirmation = '';

    public $userId = null;

    public function rules(): array
    {
        return [
            'name'      => ['required', 'min:3'],
            'username'  => ['required', 'min:3', 'lowercase',
                            Rule::unique('users')->ignore($this->user)
            ],
            'email'     => ['required', 'min:6', 'email',
                            Rule::unique('users')->ignore($this->user)
            ],
            // 'branch_id' => ['nullable', 'numeric', 'exists:branches,id'],
            'password'  => [
                'nullable',
                Password::min(8)
                        ->letters()
                        ->numbers()
                        ->symbols()
                        ->uncompromised()
            ],
        ];
    }

    public function setUser(?User $user = null): void
    {
        $this->user = $user;

        $this->name = $user?->name;
        $this->username = $user?->username;
        $this->email = $user?->email;
        // $this->branch_id = $user?->branch_id;
    }

    public function save()
    {
        $validated = $this->validate();

        if (!$this->user) {
            // Hash password only if it's set
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }
            $user = User::create($validated);
            event(new Registered(($user)));

            $this->userId = $user->id; // Store user ID for Vendor Form
            return $this->userId;
        } else {
            // Remove password fields if not provided
            if (empty($validated['password'])) {
                unset($validated['password']);
            } else {
                $validated['password'] = Hash::make($validated['password']);
            }
            $this->user->update($validated);
        }
        $this->reset();

    }
}

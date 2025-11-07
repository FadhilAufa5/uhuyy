<?php

namespace App\Livewire\Users;

use App\Exports\UsersSelectedExport;
use App\Livewire\BaseTableComponent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;

#[On('reload-users')]
#[On('record-updated')]
#[On('record-deleted')]
class Table extends BaseTableComponent
{
    public $roles;

    protected function getModelClass(): string
    {
        return User::class;
    }

    protected function getRefreshEvent(): string
    {
        return 'refresh-users-table';
    }

    public function mount(): void
    {
        parent::mount();
        $this->roles = Cache::remember('roles_list', now()->addMinutes(10), fn() => Role::all());
    }

    protected function getQuery()
    {
        return User::with(['roles', 'permissions'])
            ->when($this->search, fn($query, $search) => $query
                ->whereAny(['users.name', 'users.username', 'users.email'], 'like', "%{$search}%")
            )
            ->orderByRaw("CASE WHEN {$this->sortField} IS NULL THEN 1 ELSE 0 END, {$this->sortField} {$this->sortDirection}");
    }

    public function render(): View
    {
        return view('livewire.users.table', [
            'users' => $this->getRecords(),
            'roles' => $this->roles,
        ]);
    }

    public function getSessionsProperty(): Collection
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            \DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                ->orderBy('last_activity', 'desc')
                ->get()
        )->map(function ($session) {
            return (object)[
                'user_id' => $session->user_id,
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === request()->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                'last_activity' => $session->last_activity,
            ];
        });
    }

    public function exportChecked(): \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return (new UsersSelectedExport($this->checked))->download('users.xlsx');
    }

    public function editUser($id): void
    {
        $this->dispatch('edit-user', $id);
    }
}

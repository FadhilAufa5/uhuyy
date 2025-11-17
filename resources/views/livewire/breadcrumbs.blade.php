<?php

use Livewire\Volt\Component;

new class extends Component {
    public array $items = [];
    public string $icon = '';
    public string $root = '';

    public function mount(array $items = []): void
    {
        $this->items = $items;
    }
}; ?>

<div>
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('home') }}" icon="home">{{ $root }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>
            <flux:dropdown>
                <flux:button icon="ellipsis-horizontal" variant="ghost" size="sm"/>

                <flux:navmenu>
                    <flux:navmenu.item href="{{ route('dashboard') }}">Dashboard</flux:navmenu.item>
                   
                    @can(\App\Enums\Permissions::ManageDepartments->value)
                        <flux:navmenu.item href="{{ route('branches.index') }}">Data Management</flux:navmenu.item>
                        <flux:navmenu.item icon="arrow-turn-down-right" href="{{ route('branches.index') }}">Data
                            
                        </flux:navmenu.item>
    
                    @endcan
                    @hasrole(\App\Enums\Roles::SuperAdmin->value)
                    @unless(Request::is('users'))
                        <flux:navmenu.item href="{{ route('users.index') }}">Users</flux:navmenu.item>
                    @endunless
                    @endhasrole
                </flux:navmenu>
            </flux:dropdown>
        </flux:breadcrumbs.item>
        @foreach ($items as $item)
            <flux:breadcrumbs.item href="{{ $item['href'] ?? '#' }}">
                {{ $item['label'] ?? 'Undefined' }}
            </flux:breadcrumbs.item>
        @endforeach
    </flux:breadcrumbs>
</div>

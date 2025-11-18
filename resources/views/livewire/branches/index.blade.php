<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $title;

    public function mount(): void
    {
        $this->title = 'Branches';
    }
}; ?>

<div>
    <livewire:breadcrumbs :items="[
    [
        'href' => route('branches.index'),
        'label' => 'Branches'
    ]
]" />

    <!-- Header Section -->
    <div class="mb-6">
        <div class="relative w-full">
            <flux:heading size="xl" level="1">Branch Documents</flux:heading>
            <flux:subheading size="lg" class="mb-4">Upload dan kelola dokumen cabang dalam format PDF</flux:subheading>
            <flux:separator variant="subtle" />
        </div>
    </div>

    @livewire('branches.table')

</div>

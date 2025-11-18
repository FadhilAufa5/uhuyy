<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $title;

    public function mount(): void
    {
        $this->title = 'Apoteks';
    }
}; ?>

<div>
    <livewire:breadcrumbs :items="[
    [
        'href' => route('apoteks.index'),
        'label' => 'Apoteks'
    ]
]"/>
    
    <!-- Header Section -->
    <div class="mb-6">
        <div class="relative w-full">
            <flux:heading size="xl" level="1">Apoteks</flux:heading>
            <flux:subheading size="lg" class="mb-4">Kelola data apotek dan informasi lengkap setiap outlet</flux:subheading>
            <flux:separator variant="subtle" />
        </div>
    </div>
    
    @livewire('apoteks.cards')
</div>

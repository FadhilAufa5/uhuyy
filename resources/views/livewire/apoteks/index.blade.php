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
    @livewire('apoteks.cards')
</div>

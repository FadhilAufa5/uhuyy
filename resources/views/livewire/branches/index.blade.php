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

    @livewire('branches.table')

</div>

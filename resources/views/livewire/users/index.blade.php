<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $title;

    public function mount(): void
    {
        $this->title = 'Users';
    }
}; ?>

<section class="w-full">
    <livewire:breadcrumbs :items="[
            ['href' => route('users.index'), 'label' => 'Users']
        ]"
    />

    @include('partials.users-heading')

    @livewire('users.table')
</section>

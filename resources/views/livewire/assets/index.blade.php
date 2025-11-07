<?php

use Livewire\Volt\Component;

new class extends Component {

}; ?>

<section class="w-full">
    <livewire:breadcrumbs :items="[
            ['href' => route('assets.index'), 'label' => 'Assets']
        ]"
    />

    @include('partials.asset-heading')

    @livewire('assets.table')
</section>

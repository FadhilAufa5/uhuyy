<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<section class="w-full">
    <livewire:breadcrumbs :items="[
            ['href' => route('vendors.index'), 'label' => 'Vendors']
        ]"
    />

    @include('partials.vendor-heading')

    @livewire('vendors.table')
</section>

<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $title = 'Banking Info';

}; ?>

<section class="w-full">
    @include('partials.vendor-heading')

    <x-vendor-settings.layout heading="Data Rekening Bank" subheading="Update data rekening bank anda">
        @livewire('vendor-settings.banks.table')
    </x-vendor-settings.layout>
</section>

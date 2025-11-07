<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $title = 'Legal & Technical Documents';

}; ?>

<section class="w-full">
    @include('partials.vendor-heading')

    <x-vendor-settings.layout heading="Dokumen Legal dan Teknikal"
                              subheading="Lengkapi dokumen legal dan teknikal perusahaan anda">
        <blockquote>Data Dokumen Legal dan Teknikal Perusahaan</blockquote>

        <div x-data="{
                progress: 0,
                progressInterval: null,
            }"
             x-init="
                progressInterval = setInterval(() => {
                    progress = progress + 1;
                    if (progress >= 100) {
                        clearInterval(progressInterval);
                    }
                }, 100);
            "
             class="relative w-full h-3 overflow-hidden rounded-full bg-neutral-100">
            <span :style="'width:' + progress + '%'"
                  class="absolute w-24 h-full duration-300 ease-linear bg-neutral-900" x-cloak></span>
        </div>

    </x-vendor-settings.layout>
</section>

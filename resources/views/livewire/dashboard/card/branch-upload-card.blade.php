<?php

use Livewire\Volt\Component;
use App\Models\Branch;
use Illuminate\Support\Facades\Cache;

new class extends Component {
    public $uploadedCount;

    public function mount(): void
    {
        // Hanya hitung data branch yang diupload admin
        $this->uploadedCount = Cache::remember(
            'uploaded-branch-count',
            now()->addMinutes(10),
            fn() => Branch::count()
        );
    }
};
?>
    <div class="relative flex flex-col md:flex-row min-w-0 min-h-full break-words rounded mb-4 xl:mb-0 shadow-lg transition-all overflow-hidden">
    <div class="flex flex-col md:flex-row w-full px-4 py-4 md:p-2 lg:px-4 gap-4 items-center">
        <div class="flex flex-col flex-1 w-full">
            <div class="relative w-full px-2 md:px-4 lg:px-6 max-w-full flex-grow flex-1">
                <h5 class="text-zinc-700 uppercase font-bold text-xs dark:text-zinc-200">Uploaded Data</h5>
                <span class="font-semibold text-4xl md:text-2xl lg:text-5xl">{{ $uploadedCount }}</span>
            </div>
            <div class="px-2 md:px-4 lg:px-6 text-sm lg:text-base text-zinc-400 dark:text-zinc-200">
                <flux:menu.item class="!text-green-500 gap-2">
                    <flux:icon.arrow-up/>
                    100%
                </flux:menu.item>
                <span class="whitespace-nowrap"> Since last upload </span>
            </div>
        </div>
        <div class="relative w-auto p-2 md:p-4 flex items-center justify-center">
            <div
                class="text-white p-3 text-center inline-flex items-center justify-center size-20 md:size-24 xl:size-32 shadow-lg rounded-full bg-blue-500">
                <flux:icon.document-text class="size-12 md:size-16 xl:size-24"/>
            </div>
        </div>
    </div> 
</div>

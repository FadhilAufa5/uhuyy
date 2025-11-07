<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;

new class extends Component {

    public $userCount;

    public function mount(): void
{
    // Buat cache key global (tidak berdasarkan admin id)
    $cacheKey = "user-count-global";

    // Hitung jumlah user baru dalam 7 hari terakhir (tanpa filter admin)
    $this->userCount = Cache::remember(
        $cacheKey,
        now()->addMinutes(10),
        fn () => DB::table('users')
            ->where('created_at', '>', now()->subDays(7))
            ->count()
    );
}
};

    ?>

<div class="relative flex flex-col md:flex-row min-w-0 min-h-full break-words rounded mb-4 xl:mb-0 shadow-lg transition-all overflow-hidden">
    <div class="flex flex-col md:flex-row w-full px-4 py-4 md:p-2 lg:px-4 gap-4 items-center">
        <div class="flex flex-col flex-1 w-full">
            <div class="relative w-full px-2 md:px-4 lg:px-6 max-w-full flex-grow flex-1">
                <h5 class="text-zinc-700 uppercase font-bold text-xs dark:text-zinc-200">
                    New users 
                </h5>
                <span class="font-semibold text-4xl md:text-2xl lg:text-5xl">
                    {{ $userCount }}
                </span>
            </div>
            <div class="px-2 md:px-4 lg:px-6 text-sm lg:text-base text-zinc-400 dark:text-zinc-200">
                <flux:menu.item class="!text-green-500 gap-2">
                    <flux:icon.arrow-up/>
                    100%
                </flux:menu.item>
                <span class="whitespace-nowrap">Since last week</span>
            </div>
        </div>
        <div class="relative w-auto p-2 md:p-4 flex items-center justify-center">
            <div
                class="text-white p-3 text-center inline-flex items-center justify-center size-20 md:size-24 xl:size-32 shadow-lg rounded-full bg-emerald-400">
                <flux:icon.users class="size-12 md:size-16 xl:size-24"/>
            </div>
        </div>
    </div>
</div>


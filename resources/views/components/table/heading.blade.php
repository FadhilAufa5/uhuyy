@props(['sortable' => null, 'direction'=> null])

<th
    {{ $attributes->merge(['class' => 'px-4 py-3 bg-zinc-200/60 dark:!bg-zinc-900 dark:!text-white !font-bold'])->only('class') }}
>
    @unless($sortable)
        <span
                class="text-left text-xs 2xl:text-sm leading-4 font-bold text-zinc-500 dark:text-zinc-50 uppercase tracking-wider">{{ $slot }}</span>
    @else
        <button
                {{ $attributes->except('class') }} class="flex group items-center space-x-1 text-left text-xs 2xl:text-sm uppercase leading-4 font-medium text-zinc-600 dark:text-zinc-50 cursor-pointer">
            <span class="text-zinc-700 dark:text-zinc-50">
                @if($direction === 'asc')
                    <flux:icon.arrow-down-a-z class="w-4 h-4 opacity-50 group-hover:opacity-100 transition-opacity duration-300"/>
                @elseif($direction === 'desc')
                    <flux:icon.arrow-up-z-a size="2" class="w-4 h-4 opacity-50 group-hover:opacity-100 transition-opacity duration-300"/>
                @else
                    <flux:icon.chevrons-up-down size="2" class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300" aria-hidden="true"/>
                @endif
            </span>
            <span>{{ $slot }}</span>
        </button>
    @endif
</th>

@props(['title' => null, 'details' => null, 'id' => null])

<div :class="{ 'border-neutral-100/60 text-neutral-800' : activeAccordion === '{{ $id }}', 'border-transparent text-neutral-600 hover:text-neutral-800' : activeAccordion !== '{{ $id }}' }" class="duration-200 ease-out bg-zinc-1 00/60 border rounded-md cursor-pointer group dark:bg-zinc-800 dark:text-white" x-cloak>
    <button @click="activeAccordion = (activeAccordion === '{{ $id }}') ? null : '{{ $id }}'" class="flex items-center justify-between w-full p-4 text-left select-none group-hover:underline group-hover:dark:text-zinc-50">
        <span class="flex-1/12">{{ $title }}</span>
        <span class="flex-4/12">{{ $details }}</span>
        <div :class="{ 'rotate-90': activeAccordion === '{{ $id }}' }" class="relative flex items-center justify-center w-2.5 h-2.5 duration-300 ease-out">
            <div class="absolute w-0.5 h-full bg-neutral-500 group-hover:bg-neutral-800 dark:group-hover:bg-neutral-400 rounded-full"></div>
            <div :class="{ 'rotate-90': activeAccordion === '{{ $id }}' }" class="absolute w-full h-0.5 ease duration-500 bg-neutral-500 group-hover:bg-neutral-800 dark:group-hover:bg-neutral-400 rounded-full"></div>
        </div>
    </button>
    <div x-show="activeAccordion === '{{ $id }}'" x-collapse x-cloak>
        <div class="p-4 pt-0 opacity-70">
            {{ $slot }}
        </div>
    </div>
</div>

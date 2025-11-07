@props(['column'=>null, 'value'=>null])
<div class="flex items-center gap-3 justify-between w-full mt-2 drop-shadow-md border-b dark:border-b-zinc-600">
    <flux:heading class="flex-1/12 max-w-xs">{{ $column }}</flux:heading>
    <flux:heading >:</flux:heading>
    <flux:heading class="flex-6/12">{{ $value }}</flux:heading>
</div>

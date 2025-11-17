@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center space-y-1">
    <flux:heading size="xl" class="font-semibold text-gray-900 dark:text-white">{{ $title }}</flux:heading>
    @if($description)
        <flux:subheading class="text-gray-600 dark:text-gray-400">{{ $description }}</flux:subheading>
    @endif
</div>

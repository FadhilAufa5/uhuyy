<div class="flex items-start max-md:flex-col">
    <div class="mr-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>
            <flux:navlist.item href="{{ route('settings.vendor') }}" :current="Request::is('settings/vendor')" wire:navigate>General Information</flux:navlist.item>
            <flux:navlist.item href="{{ route('settings.vendor-pics') }}" :current="Request::is('settings/vendor-persons')" wire:navigate>Persons in Charge</flux:navlist.item>
            <flux:navlist.item href="{{ route('settings.vendor-banks') }}" :current="Request::is('settings/vendor-bank')" wire:navigate>Banking Information</flux:navlist.item>
            <flux:navlist.item href="{{ route('settings.vendor-documents') }}" :current="Request::is('settings/vendor-documents')" wire:navigate>Legal and Technical Documents</flux:navlist.item>
            <flux:navlist.item href="{{ route('settings.vendor-experiences') }}" :current="Request::is('settings/vendor-experiences')" wire:navigate>Experiences</flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{ $slot }}
        </div>
    </div>
</div>

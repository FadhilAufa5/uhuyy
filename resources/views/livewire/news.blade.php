<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component {
}; ?>

<div
        x-data="{
        tabSelected: 1,
        tabId: $id('tabs'),
        tabButtonClicked(tabButton){
            this.tabSelected = tabButton.id.replace(this.tabId + '-', '');
            this.tabRepositionMarker(tabButton);
        },
        tabRepositionMarker(tabButton){
            this.$refs.tabMarker.style.width=tabButton.offsetWidth + 'px';
            this.$refs.tabMarker.style.height=tabButton.offsetHeight + 'px';
            this.$refs.tabMarker.style.left=tabButton.offsetLeft + 'px';
        },
        tabContentActive(tabContent){
            return this.tabSelected == tabContent.id.replace(this.tabId + '-content-', '');
        },
        tabButtonActive(tabContent){
            const tabId = tabContent.id.split('-').slice(-1);
            return this.tabSelected == tabId;
        }
    }"

        x-init="tabRepositionMarker($refs.tabButtons.firstElementChild);" class="relative w-full max-w-5xl mx-auto">

    <div x-ref="tabButtons" class="relative inline-grid items-center justify-center w-full h-10 grid-cols-2 p-1 text-gray-500 bg-white border border-gray-100 rounded-lg select-none dark:bg-zinc-700 dark:text-zinc-50">
        <button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button" :class="{ 'bg-gray-100 text-gray-700' : tabButtonActive($el) }" class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">Open</button>
        <button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button" :class="{ 'bg-gray-100 text-gray-700' : tabButtonActive($el) }" class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">History</button>
        <div x-ref="tabMarker" class="absolute left-0 z-10 w-1/2 h-full duration-300 ease-out" x-cloak><div class="w-full h-full bg-gray-100 rounded-md shadow-sm"></div></div>
    </div>
    <div class="relative flex items-center justify-center w-full p-5 mt-2 text-xs text-gray-400 border rounded-md content border-gray-200/70">

        <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative">
            @livewire('news.open')
        </div>

        <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative" x-cloak>
            @livewire('news.history')
        </div>

    </div>
</div>
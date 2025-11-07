@props([
    'model',         // e.g. App\Models\Branch
    'searchColumn',  // e.g. name
    'valueColumn' => 'id',
    'placeholder' => 'Search...',
    'name',          // the <input name="branch_id">
    'initialValue' => '',
    'initialName' => '',
])

@php
    $modelClass = "\\$model"; // ensure fully qualified
@endphp

<div x-data="{
    query: '',
    results: [],
    selectedId: @entangle($attributes->wire('model')->value()),
    selectedName: '{{ $initialName }}',
    highlightIndex: 0,
    async search() {
        if (this.query.length < 1) { this.results = []; return; }
        const res = await fetch('/api/search-select?' + new URLSearchParams({
            model: '{{ addslashes($model) }}',
            column: '{{ $searchColumn }}',
            value: '{{ $valueColumn }}',
            q: this.query
        }), { headers: {'Accept':'application/json'} });
        this.results = await res.json();
    },
    select(item) {
        this.selectedId = item['{{ $valueColumn }}'];
        this.selectedName = item['{{ $searchColumn }}'];
        this.query = '';
        this.results = [];
    },
    selectHighlighted() {
        if (this.results.length) this.select(this.results[this.highlightIndex]);
    },
    incrementHighlight() {
        this.highlightIndex = (this.highlightIndex + 1) % this.results.length;
    },
    decrementHighlight() {
        this.highlightIndex = (this.highlightIndex - 1 + this.results.length) % this.results.length;
    },
}">
    <input
        type="text"
        class="form-input w-full bg-zinc-50"
        :placeholder="selectedName || '{{ $placeholder }}'"
        x-model="query"
        @input="search"
        @keydown.arrow-down.prevent="incrementHighlight"
        @keydown.arrow-up.prevent="decrementHighlight"
        @keydown.enter.prevent="selectHighlighted"
    />

    <input type="hidden" name="{{ $name }}" :value="selectedId">

    <template x-if="query && results.length">
        <div class="absolute z-10 w-full bg-white shadow rounded mt-1">
            <template x-for="(item, index) in results" :key="index">
                <div
                    @click="select(item)"
                    :class="{'bg-gray-200': highlightIndex === index }"
                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                    x-text="item['{{ $searchColumn }}']"
                ></div>
            </template>
        </div>
    </template>

</div>

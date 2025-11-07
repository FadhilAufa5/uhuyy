@props([
	// item format: [ 'id' => '', 'title' => '', 'subtitle' => '' ]
	'options' => [],
	// optional, only use if wire:model is not functioning
	// correctly: ['ITEM1','ITEM2','ITEM3']
	'selectedItems' => []
])

{{--
	Github docs: https://gist.github.com/CallumCarmicheal/3bcbfb178443c9a11c673be83530ac8d
  --}}
@php
    // Get the wire model and convert it into a unique id
    $modelId = $attributes->wire('model');
    if ($modelId != null) $modelId = str_replace('\'', '', str_replace('\"', '', $modelId));
    // Get the id, attribute
    $attrId = $attributes->get('id') ?? null;
    if ($attrId != null) $attrId = str_replace('\'', '', str_replace('\"', '', $attrId));
    $compId = (!isset($attrId) || trim($attrId) === '') ? str_replace('.', '_', $modelId) : $attrId;
@endphp

<div id="{{$compId}}_container">
    {{-- <p><i>{{$attrId}}</i> - <b>{{$modelId}}</b> - <small>{{$compId}}</small></p> --}}
    {{-- <p>{{!isset($attrId) ? 't' : 'f' }} | {{ trim($attrId) === '' ? 't' : 'f' }} | {{(!isset($attrId) || trim($attrId) === '') ? 't' : 'f'}}</p> --}}
    {{-- <p>{{ str_replace('.', '_', $modelId) }} = {{$compId}}</p> --}}

    <!-- DOM Attribute tracking to get latest value of $options if it changes. -->
    <div id="{{$compId}}_dropdownItems" class="d-none" x-items="{{ collect($options)->toJson() }}"></div>

    <!-- Alpine js binding to flag an event to trigger when ever the current value changes,
         if this is not done then the value will not reflect from any server side changes. -->
    <div wire:ignore {{ $attributes->wire('model') }}
    class="d-none" x-data="{ value: null }" x-modelable="value"
         x-init="$watch('value', (v) => {
			let tomSelect = document.querySelector('#{{$compId}}_select').tomselect;
			if (tomSelect.getValue() !== v) {
				// Value is different on the server, update our drop-down.
				// console.log('Different value: ', tomSelect.getValue(), v);
				tomSelect.setValue(v, true); // silent: true (don't trigger $wire.$refresh())
			} else {
				// Value is the same, no change required.
				// console.log('value same: ', tomSelect.getValue(), v);
			}
		 })">
    </div>

    <script>
        window.addEventListener('load', function () {
            // Create a DOM Observer to listen for changes on the item list.
            function createObserver(targetElementSelector, attributeFilter = [], callback) {
                //console.log('Started observing: ', targetElementSelector, 'Attr filter: ', attributeFilter);
                let targetElement = document.querySelector(targetElementSelector);
                if (targetElement) {
                    // Create a new instance of MutationObserver
                    const observer = new MutationObserver((mutationsList) => {
                        for (let mutation of mutationsList) {
                            //console.log("mutation: ", mutation.type);
                            // Check if the mutation type is 'childList' or 'attributes'
                            if (mutation.type === 'childList' || mutation.type === 'attributes') {
                                callback(targetElement);
                            }
                        }
                    });
                    // Configure the observer to watch for changes in child elements and character data
                    const config = {
                        childList: true,        // Watch for the addition or removal of child nodes
                        subtree: true,          // Watch for changes in all descendants
                        //characterData: true,    // Watch for changes to the text content of the node
                        attributeFilter: attributeFilter
                    };
                    // Start observing the target element
                    observer.observe(targetElement, config);
                } else {
                    console.error('Element with class ' + targetElementSelector + ' not found.');
                }
            }

            // Listen for dropdown item list changes
            createObserver('#{{$compId}}_dropdownItems', ['x-items'], (targetElement) => {
                // Get the item and parse it into a object.
                let attr = targetElement.getAttribute('x-items');
                let js = JSON.parse(attr); //
                // Get our tom-select object
                let container = $('#{{$compId}}_container');
                let tomSelect = container.find('select')[0].tomselect;
                let value = tomSelect.getValue();
                // Clear the properties, set the item list and change our value.
                tomSelect.clear();
                tomSelect.clearOptions();
                tomSelect.addOptions(js);
                tomSelect.setValue(value, true); // silent: true (don't trigger $wire.$refresh())
            });
        });
    </script>

    <!-- All changes in this element are ignored by wire, hence the hacky code above. -->
    <div wire:ignore>
        <select
            id="{{$compId}}_select"
            name="{{$attributes->get('name') ?? ($attributes->get('wire:model'))}}"
            wire:focus.stop
            placeholder="Select..."
            x-ref="input"
            x-cloak
            class="w-full rounded-xl block disabled:shadow-none dark:shadow-none text-base sm:text-sm h-10 leading-[1.375rem] text-zinc-700 disabled:text-zinc-500 placeholder-zinc-400 disabled:placeholder-zinc-400/70 dark:text-zinc-300 dark:disabled:text-zinc-400 dark:placeholder-zinc-400 dark:disabled:placeholder-zinc-500 bg-zinc-50 dark:bg-zinc-700"
            {{ $attributes->except(['name', 'id']) }}
            x-data="{
				tomSelectInstance: null,
				options: {{ collect($options) }},
				items: {{ collect($selectedItems) }},
				renderTemplate(data, escape) {
					return `<div class='flex items-center !bg-zinc-50 hover:!bg-zinc-700 dark:!bg-zinc-700 dark:hover:!bg-zinc-300 border-transparent !text-zinc-700 hover:!text-white dark:!text-zinc-50 dark:hover:!text-zinc-700'>
						<div>
							<div class='block font-medium'>${escape(data.title)}</div>
							${data.subtitle == undefined ? '' : `<small class='block text-zinc-500'>${escape(data.subtitle)}</small>`}
						</div>
					</div>`;
				},
				itemTemplate(data, escape) {
					return `<div>
						<span class='block font-medium text-zinc-700'>${escape(data.title)}</span>
					</div>`;
				}
			}"
            x-init="tomSelectInstance = new TomSelect($refs.input, {
				valueField: 'id',
				labelField: 'title',
				searchField: 'title',
				options: options,
				items: items,
				@if (!empty($items) && !$attributes->has('multiple'))
					placeholder: undefined,
				@endif
				render: {
					option: renderTemplate,
					item: itemTemplate
				},
				maxOptions: null,
				create: false,
				hidePlaceholder: true,
				plugins: {
					'clear_button': {},
					'caret_position': {}
				},
				onDropdownOpen: function(dropdown){
					let bounding = dropdown.getBoundingClientRect();
					if (bounding.bottom > (window.innerHeight || document.documentElement.clientHeight)) {
						dropdown.classList.add('dropup');
					}
				},
				onDropdownClose: function(dropdown){
					dropdown.classList.remove('dropup');
				},
			});
			tomSelectInstance.on('change', function() {
				// Update our binding when we change item.
				$wire.$refresh();
			});"
        ></select>
    </div>
</div>

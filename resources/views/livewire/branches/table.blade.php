<div>
            <style>
                [x-cloak] { display: none !important; }
            </style>

            <div class="p-6 bg-white dark:bg-zinc-800 rounded-xl shadow-lg border border-gray-200 dark:border-zinc-700 transition-colors duration-200" 
                wire:poll.2s="checkForUpdates"
                @if($hasProcessingBranches) wire:poll.1s @endif>
                {{-- Flash Message --}}
                @if (session()->has('message'))
                    <div x-data="{ show: true }" 
                         x-init="setTimeout(() => show = false, 3000)"
                         x-show="show"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-90"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-90"
                         class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 rounded-lg flex items-center gap-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">{{ session('message') }}</span>
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6 flex-wrap gap-3">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Branch Documents</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your uploaded documents</p>
                    </div>
                    <flux:button variant="primary" class="shadow-md hover:shadow-lg transition-shadow">
                        <flux:modal.trigger name='add-branch'>
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Upload File
                        </flux:modal.trigger>
                    </flux:button>
                </div>
                @livewire('branches.create')

                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-900">
                            <tr>
                                <th class="py-3.5 px-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider w-20">No</th>
                                <th class="py-3.5 px-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">File Name</th>
                                <th class="py-3.5 px-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider w-32">Status</th>
                                <th class="py-3.5 px-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider w-40">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                            @forelse($branches as $branch)
                                <tr class="hover:bg-gray-100/50 dark:hover:bg-zinc-700/50 transition-colors duration-200">
                                    <td class="py-4 px-4 text-sm font-medium text-gray-900 dark:text-gray-200">
                                        {{ $loop->iteration + ($branches->firstItem() - 1) }}
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($branch->file_path)
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-red-100 dark:bg-red-900/30 rounded-lg">
                                                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                        {{ $branch->file_name }}
                                                    </p>
                                                    <div class="flex items-center gap-2 mt-0.5">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            PDF Document
                                                        </p>
                                                        @if($branch->conversion_status === 'proses')
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                                                <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                </svg>
                                                                Converting...
                                                            </span>
                                                        @elseif($branch->conversion_status === 'selesai')
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium text-green-700 dark:text-green-400 bg-green-100 dark:bg-green-900/30 rounded-full">
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Converted
                                                            </span>
                                                        @elseif($branch->conversion_status === 'gagal')
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium text-red-700 dark:text-red-400 bg-red-100 dark:bg-red-900/30 rounded-full">
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Failed
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400 dark:text-gray-500 italic">No file uploaded</span>
                                        @endif
                                    </td>

                                    <td class="py-4 px-4">
                                        @if($branch->status === 'aktif')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-green-700 dark:text-green-400 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-full">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-700 dark:text-red-400 bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-full">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                Offline
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-4 px-4">
                                        <div class="flex justify-center gap-2"> 
                                            {{-- Preview Button --}}
                                            @if($branch->file_path)
                                                <flux:modal.trigger name="preview-pdf-{{ $branch->id }}">
                                                    <button class="inline-flex items-center justify-center p-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-all duration-200 border border-blue-200 dark:border-blue-800 group" title="Preview PDF">
                                                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    </button>
                                                </flux:modal.trigger>

                                                {{-- Preview Modal --}}
                                                <flux:modal name="preview-pdf-{{ $branch->id }}" class="w-full max-w-5xl">
                                                    <div class="space-y-4">
                                                        <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-zinc-700">
                                                            <div class="flex-1 min-w-0">
                                                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">PDF Preview</h3>
                                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 truncate">{{ $branch->file_name }}</p>
                                                            </div>
                                                            <a href="{{ asset('storage/' . $branch->file_path) }}" 
                                                               download="{{ $branch->file_name }}"
                                                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white rounded-lg transition-colors shadow-md hover:shadow-lg">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                                </svg>
                                                                Download
                                                            </a>
                                                        </div>
                                                        <div class="bg-gray-100 dark:bg-zinc-900 rounded-lg overflow-hidden">
                                                            <embed src="{{ asset('storage/' . $branch->file_path) }}" 
                                                                type="application/pdf" 
                                                                class="w-full"
                                                                height="700px">
                                                        </div>
                                                    </div>
                                                </flux:modal>
                                            @endif

                                            {{-- Toggle Status Button --}}
                                            <button 
                                                wire:click="toggleStatus({{ $branch->id }})"
                                                class="toggle-btn-{{ $branch->id }} inline-flex items-center justify-center p-2 {{ $branch->status === 'aktif' ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800 hover:bg-amber-100 dark:hover:bg-amber-900/40' : 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 border-green-200 dark:border-green-800 hover:bg-green-100 dark:hover:bg-green-900/40' }} rounded-lg transition-all duration-200 border group" 
                                                title="{{ $branch->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                x-data="{ loading: false }"
                                                @click="loading = true"
                                                :disabled="loading"
                                                :class="{ 'opacity-50 cursor-not-allowed': loading }"
                                            >
                                                <span x-show="!loading">
                                                    @if($branch->status === 'aktif')
                                                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                    @endif
                                                </span>
                                                <span x-show="loading" x-cloak>
                                                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                </span>
                                            </button>

                                            {{-- Delete Button --}}
                                            <flux:modal.trigger variant="danger" name="delete-modal"
                                                x-data="{ BranchId: {{ json_encode([$branch->id]) }} }"
                                                wire:click="$dispatch('bulkDelete', {recordIds: BranchId, model: 'Branch' })"
                                            >
                                                <button class="inline-flex items-center justify-center p-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/40 transition-all duration-200 border border-red-200 dark:border-red-800 group" title="Delete">
                                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </flux:modal.trigger>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 px-4 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 font-medium">No files found</p>
                                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Get started by uploading your first document</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($branches->hasPages())
                    <div class="mt-6 flex items-center justify-between border-t border-gray-200 dark:border-zinc-700 pt-4">
                        <div class="flex-1 flex justify-between sm:hidden">
                            {{ $branches->links('simple-pagination') }}
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    Showing <span class="font-medium">{{ $branches->firstItem() }}</span> to <span class="font-medium">{{ $branches->lastItem() }}</span> of <span class="font-medium">{{ $branches->total() }}</span> results
                                </p>
                            </div>
                            <div>
                                {{ $branches->links() }}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Delete Modal --}}
                <livewire:delete-modal />
            </div>

            <script>
                document.addEventListener('livewire:init', () => {
                    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                        succeed(({ snapshot, effect }) => {
                            // Reset all loading states after Livewire finishes
                            document.querySelectorAll('[x-data]').forEach(el => {
                                if (el.__x && el.__x.$data.loading !== undefined) {
                                    el.__x.$data.loading = false;
                                }
                            });
                        });
                    });
                });
            </script>
</div>
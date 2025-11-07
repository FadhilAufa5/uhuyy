<div class="w-full">
    @if($branch && $branch->file_path && Storage::disk('public')->exists($branch->file_path))
        <!-- Hero with Active Branch Document -->
        <div class="relative bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-zinc-800 dark:to-zinc-900 rounded-2xl shadow-xl overflow-hidden border border-blue-200 dark:border-zinc-700 transition-all duration-300">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="1"/>
                    </pattern>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>

            <div class="relative p-8 md:p-12">
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <!-- Document Icon/Preview -->
                    <div class="flex-shrink-0">
                        <div class="relative group">
                            <div class="absolute -inset-4 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl blur-2xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                            <div class="relative bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl border-2 border-blue-200 dark:border-zinc-700">
                                <svg class="w-20 h-20 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 text-center md:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-full mb-4">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </span>
                            <span class="text-xs font-semibold text-green-700 dark:text-green-400">ACTIVE DOCUMENT</span>
                        </div>

                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3">
                            Active Branch Document
                        </h2>
                        
                        <p class="text-gray-600 dark:text-gray-300 mb-2 flex items-center justify-center md:justify-start gap-2">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-medium">{{ $branch->file_name }}</span>
                        </p>

                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                            Last updated: {{ $branch->updated_at->diffForHumans() }}
                        </p>

                        <!-- Actions -->
                        <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                            <a href="{{ asset('storage/' . $branch->file_path) }}" 
                               target="_blank"
                               class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View Document
                            </a>
                            
                            <a href="{{ asset('storage/' . $branch->file_path) }}" 
                               download="{{ $branch->file_name }}"
                               class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-200 font-semibold rounded-lg border-2 border-gray-300 dark:border-zinc-600 hover:border-gray-400 dark:hover:border-zinc-500 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty State: No Active Document -->
        <div class="relative bg-gradient-to-br from-gray-50 to-gray-100 dark:from-zinc-800 dark:to-zinc-900 rounded-2xl shadow-xl overflow-hidden border-2 border-dashed border-gray-300 dark:border-zinc-700 transition-all duration-300">
            <div class="p-12 md:p-16 text-center">
                <!-- Icon -->
                <div class="inline-flex items-center justify-center w-24 h-24 mb-6 bg-gray-200 dark:bg-zinc-700 rounded-full">
                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>

                <!-- Title -->
                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-3">
                    No Active Document Found
                </h3>

                <!-- Description -->
                <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                    There are currently no active branch documents available. Please upload a document to get started.
                </p>

                <!-- CTA Button -->
                @can(App\Enums\Permissions::ManageDepartments->value)
                    <a href="{{ route('branches.index') }}" 
                       wire:navigate
                       class="inline-flex items-center gap-2 px-8 py-4 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Upload Document
                    </a>
                @else
                    <div class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 dark:bg-zinc-800 text-gray-500 dark:text-gray-400 rounded-lg border border-gray-300 dark:border-zinc-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Contact administrator to upload documents
                    </div>
                @endcan
            </div>
        </div>
    @endif
</div>

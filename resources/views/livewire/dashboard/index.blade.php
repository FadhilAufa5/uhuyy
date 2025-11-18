<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app', ['title' => 'Dashboard'])] class extends Component {
}; ?>

<div>
    <livewire:breadcrumbs :items="[
        ['href' => route('dashboard'), 'label' => 'Dashboard']
    ]" />

    <div class="flex flex-col flex-1 w-full h-full gap-6 rounded-xl">

        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 dark:from-blue-800 dark:to-indigo-900 rounded-xl shadow-lg p-8 text-white">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">Selamat Datang di Dashboard! 👋</h1>
                    <p class="text-blue-100 text-lg">Kelola sistem manajemen Anda dengan mudah dan efisien</p>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <div class="inline-flex items-center bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm">{{ now()->format('l, d F Y') }}</span>
                        </div>
                        <div class="inline-flex items-center bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-sm">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <svg class="w-32 h-32 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid gap-4 auto-rows-min md:grid-cols-2 lg:grid-cols-3">
            <!-- Card: User -->
            <div class="relative border aspect-auto rounded-xl border-neutral-200 dark:border-neutral-700 hover:shadow-xl transition-shadow duration-200">
                <livewire:dashboard.card.user-card />
            </div>

            <!-- Card: Upload -->
            <div class="relative border aspect-auto rounded-xl border-neutral-200 dark:border-neutral-700 hover:shadow-xl transition-shadow duration-200">
                <livewire:dashboard.card.branch-upload-card>
            </div>

            <!-- Quick Info Card -->
            <div class="relative border aspect-auto rounded-xl border-neutral-200 dark:border-neutral-700 hover:shadow-xl transition-shadow duration-200 p-6 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20">
                <div class="flex flex-col h-full">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-zinc-700 uppercase font-bold text-xs dark:text-zinc-200">
                            System Status
                        </h5>
                        <div class="p-3 text-center inline-flex items-center justify-center size-12 shadow-lg rounded-full bg-purple-500 text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 flex items-center">
                        <div>
                            <span class="text-2xl font-bold text-green-600 dark:text-green-400">Operational</span>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-2">Semua sistem berjalan normal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-lg border border-gray-200 dark:border-zinc-700 p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('users.index') }}" class="flex flex-col items-center justify-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-all duration-200 group">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Users</span>
                </a>
                <a href="{{ route('assets.index') }}" class="flex flex-col items-center justify-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/40 transition-all duration-200 group">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Assets</span>
                </a>
                <a href="{{ route('branches.index') }}" class="flex flex-col items-center justify-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/40 transition-all duration-200 group">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Documents</span>
                </a>
                <a href="{{ route('apoteks.index') }}" class="flex flex-col items-center justify-center p-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-all duration-200 group">
                    <svg class="w-8 h-8 text-amber-600 dark:text-amber-400 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Apoteks</span>
                </a>
            </div>
        </div>

    </div>
</div>

<!-- Footer -->
<footer class="bg-white dark:bg-zinc-900 pt-10 border-t border-gray-200 dark:border-zinc-700">
    <flux:container>
        <!-- Logo Section -->
        <div class="flex flex-wrap items-start justify-between pb-10">
            <a href="https://kimiafarmaapotek.co.id" target="_blank"
                class="flex items-center w-auto mt-1 text-lg font-bold transition-all duration-300 ease-out 
                       brightness-0 hover:brightness-100 
                       dark:grayscale dark:brightness-200 dark:contrast-200 
                       dark:hover:grayscale-0 dark:hover:brightness-100 dark:hover:contrast-100">
                <x-app-logo-icon class="flex-shrink-0 w-auto h-10"></x-app-logo-icon>
            </a>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-200 dark:border-zinc-700 py-8 flex flex-col lg:flex-row items-center justify-between space-y-6 lg:space-y-0">
            
            <!-- Left: Text + Links -->
            <ul class="flex flex-wrap items-center justify-center text-sm text-gray-600 dark:text-gray-300">
                <li class="w-full text-center lg:w-auto mb-4 lg:mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel Wave') }}, Inc. All rights reserved.</li>

                <li class="mx-3">
                    <a href="#" class="relative inline-block group">
                        <span class="absolute bottom-0 w-full border-b border-black opacity-0 transform -translate-y-1 transition-all duration-150 ease-out group-hover:opacity-100 group-hover:translate-y-0"></span>
                        <span>Privacy Policy</span>
                    </a>
                </li>

                <li class="mx-3">
                    <a href="#" class="relative inline-block group">
                        <span class="absolute bottom-0 w-full border-b border-black opacity-0 transform -translate-y-1 transition-all duration-150 ease-out group-hover:opacity-100 group-hover:translate-y-0"></span>
                        <span>Disclaimers</span>
                    </a>
                </li>

                <li class="mx-3">
                    <a href="#" class="relative inline-block group">
                        <span class="absolute bottom-0 w-full border-b border-black opacity-0 transform -translate-y-1 transition-all duration-150 ease-out group-hover:opacity-100 group-hover:translate-y-0"></span>
                        <span>Terms & Conditions</span>
                    </a>
                </li>
            </ul>

            <!-- Right: Social Media -->
            <ul class="flex items-center justify-center space-x-6">
                <li>
                    <a href="#" class="text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition">
                        <span class="sr-only">Facebook</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 
                                1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 
                                0-1.63.771-1.63 1.562V12h2.773l-.443 
                                2.89h-2.33v6.988C18.343 21.128 22 
                                16.991 22 12z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </li>
                <li>
                    <a href="https://www.instagram.com/kimiafarmaapotek_id/"
                        class="text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition">
                        <span class="sr-only">Instagram</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 
                                4.902 0 011.772 1.153 4.902 4.902 0 
                                011.153 1.772c.247.636.416 1.363.465 
                                2.427.048 1.067.06 1.407.06 4.123v.08c0 
                                2.643-.012 2.987-.06 4.043-.049 1.064-.218 
                                1.791-.465 2.427a4.902 4.902 0 
                                01-1.153 1.772 4.902 4.902 0 
                                01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 
                                0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 
                                4.902 0 01-1.772-1.153 4.902 4.902 0 
                                01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 
                                4.902 0 011.153-1.772A4.902 4.902 0 
                                015.45 2.525c.636-.247 1.363-.416 
                                2.427-.465C8.901 2.013 9.256 2 
                                11.685 2h.63zM12 6.865a5.135 5.135 0 
                                110 10.27 5.135 5.135 0 010-10.27zm0 
                                1.802a3.333 3.333 0 100 6.666 3.333 
                                3.333 0 000-6.666zm5.338-3.205a1.2 1.2 
                                0 110 2.4 1.2 1.2 0 010-2.4z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
    </flux:container>
</footer>

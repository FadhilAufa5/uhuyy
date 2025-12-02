@php
use App\Services\ImageCleanupService;

$branches = \App\Models\Branch::where('status', 'aktif')
    ->where('conversion_status', 'selesai')
    ->orderByDesc('created_at')
    ->get(); 

$images = [];

foreach ($branches as $branch) {
    // Priority 1: Get images from database (base64)
    if ($branch->hasImagesInDatabase()) {
        $imagesData = json_decode($branch->images_data, true);
        if (is_array($imagesData)) {
            foreach ($imagesData as $imageData) {
                if (!empty($imageData['data']) && !empty($imageData['mime'])) {
                    $images[] = ImageCleanupService::base64ToDataUri($imageData);
                }
            }
        }
    }
    // Priority 2: Fallback to storage paths (backward compatibility)
    elseif ($branch->image_gallery) {
        $gallery = json_decode($branch->image_gallery, true);
        if (is_array($gallery)) {
            foreach ($gallery as $path) {
                $images[] = asset('storage/' . $path);
            }
        }
    } elseif ($branch->image_path) {
        $images[] = asset('storage/' . $branch->image_path);
    }
}
@endphp


@if (is_array($images) && count($images))

<section id="heroSection" class="relative w-full h-screen overflow-hidden bg-black">
 
    <button id="fullscreenBtn" 
        class="absolute top-5 right-5 z-30 p-3 bg-white/90 dark:bg-zinc-800/90 backdrop-blur-sm rounded-full hover:bg-white dark:hover:bg-zinc-700 transition-all duration-300 shadow-lg hover:scale-110 group"
        title="Enter Fullscreen (F)">
        <svg xmlns="http://www.w3.org/2000/svg" 
            class="h-6 w-6 text-zinc-900 dark:text-white" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
        </svg>
    </button>

   
    <div class="swiper-container w-full h-full">
        <div class="swiper-wrapper">
            @foreach($images as $img)
                <div class="swiper-slide">
                    <img 
                        src="{{ $img }}"
                        alt="Branch Image"
                        class="w-full h-screen object-cover object-center"
                    >
                </div>
            @endforeach
        </div>

        @foreach ($branches as $branch)
            {{ $branch->user->name }}
        @endforeach

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/40 z-10"></div>

        <!-- Pagination & Navigation -->
        <div class="swiper-pagination z-20"></div>
        <div class="swiper-button-next text-white z-20"></div>
        <div class="swiper-button-prev text-white z-20"></div>

        <!-- Fullscreen Controls (Hidden by default, shown in fullscreen) -->
        <div id="fullscreenControls" class="hidden">
            <!-- Top Bar -->
            <div class="absolute top-0 left-0 right-0 z-40 bg-gradient-to-b from-black/80 to-transparent p-6 fullscreen-control-fade">
                <div class="flex items-center justify-between">
                    <div class="text-white">
                        <h3 class="text-xl font-semibold">Image Gallery</h3>
                        <p class="text-sm text-white/80 mt-1">Press ESC or click exit to close fullscreen</p>
                    </div>
                    <button id="exitFullscreenBtn" 
                        class="p-3 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-full transition-all duration-300 hover:scale-110"
                        title="Exit Fullscreen (ESC)">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                            class="h-6 w-6 text-white" 
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="absolute bottom-0 left-0 right-0 z-40 bg-gradient-to-t from-black/80 to-transparent p-6 fullscreen-control-fade">
                <div class="flex items-center justify-center gap-4">
                    <button class="fullscreen-nav-btn" id="fullscreenPrev" title="Previous (←)">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    
                    <button class="fullscreen-nav-btn" id="fullscreenPlayPause" title="Play/Pause (Space)">
                        <svg class="h-6 w-6 play-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <svg class="h-6 w-6 pause-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </button>

                    <button class="fullscreen-nav-btn" id="fullscreenNext" title="Next (→)">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

@endif

<!-- SwiperJS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .fade-in-image {
        animation: fadeIn 0.8s ease-in-out;
    }

    .swiper-slide img {
        opacity: 0;
        transition: opacity 0.8s ease-in-out;
    }

    .swiper-slide-active img,
    .swiper-slide-duplicate-active img {
        opacity: 1;
    }

    /* Fullscreen mode styles */
    #heroSection:fullscreen {
        background: #000;
    }

    #heroSection:-webkit-full-screen {
        background: #000;
    }

    #heroSection:-moz-full-screen {
        background: #000;
    }

    #heroSection:-ms-fullscreen {
        background: #000;
    }

    /* Show controls only in fullscreen */
    #heroSection:fullscreen #fullscreenControls,
    #heroSection:-webkit-full-screen #fullscreenControls,
    #heroSection:-moz-full-screen #fullscreenControls {
        display: block !important;
    }

    /* Hide normal fullscreen button in fullscreen mode */
    #heroSection:fullscreen #fullscreenBtn,
    #heroSection:-webkit-full-screen #fullscreenBtn,
    #heroSection:-moz-full-screen #fullscreenBtn {
        display: none !important;
    }

    /* Fullscreen navigation buttons */
    .fullscreen-nav-btn {
        padding: 1rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        color: white;
        transition: all 0.3s ease;
        border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .fullscreen-nav-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: scale(1.1);
        border-color: rgba(255, 255, 255, 0.4);
    }

    .fullscreen-nav-btn:active {
        transform: scale(0.95);
    }

    /* Auto-hide controls animation */
    .fullscreen-control-fade {
        animation: fadeInControls 0.3s ease-in-out;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    @keyframes fadeInControls {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Hide controls after inactivity */
    #heroSection:fullscreen.hide-controls .fullscreen-control-fade,
    #heroSection:-webkit-full-screen.hide-controls .fullscreen-control-fade,
    #heroSection:-moz-full-screen.hide-controls .fullscreen-control-fade {
        opacity: 0;
        pointer-events: none;
    }

    /* Make images fit better in fullscreen */
    #heroSection:fullscreen .swiper-slide img,
    #heroSection:-webkit-full-screen .swiper-slide img,
    #heroSection:-moz-full-screen .swiper-slide img {
        object-fit: contain;
        max-height: 100vh;
    }

    /* Cursor auto-hide in fullscreen */
    #heroSection:fullscreen.hide-cursor,
    #heroSection:-webkit-full-screen.hide-cursor,
    #heroSection:-moz-full-screen.hide-cursor {
        cursor: none;
    }

    #heroSection:fullscreen.hide-cursor .swiper-slide,
    #heroSection:-webkit-full-screen.hide-cursor .swiper-slide,
    #heroSection:-moz-full-screen.hide-cursor .swiper-slide {
        cursor: none;
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Initialize main swiper with continuous infinite loop
    const heroSwiper = new Swiper(".swiper-container", {
        // Infinite looping configuration
        loop: true,
        loopAdditionalSlides: 2, // Preload slides for smooth looping
        loopedSlides: null, // Auto-calculate for better performance
        
        // Autoplay configuration - never stops
        autoplay: {  
            delay: 3000,
            disableOnInteraction: false, // Keep playing after user interaction
            pauseOnMouseEnter: false, // Don't pause on hover
            waitForTransition: true, // Wait for transition to complete
            reverseDirection: false, // Forward direction
        },
        
        // Pagination
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true, // Better for many slides
        },
        
        // Navigation arrows
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        
        // Smooth transitions
        effect: "fade",
        fadeEffect: {
            crossFade: true, // Smoother fade transition
        },
        speed: 1000,
        
        // Performance optimization
        observer: true, // Watch for DOM changes
        observeParents: true,
        watchSlidesProgress: true,
        
        // Prevent autoplay stop
        on: {
            init: function() {
                console.log('Swiper initialized - autoplay started');
            },
            slideChange: function() {
                // Ensure autoplay continues after slide change
                if (!this.autoplay.running) {
                    this.autoplay.start();
                }
            },
            reachEnd: function() {
                // Force continue to beginning (should be automatic with loop:true)
                console.log('Reached end, looping back to start');
            },
        },
    });

    // Fullscreen API elements
    const heroSection = document.getElementById('heroSection');
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    const exitFullscreenBtn = document.getElementById('exitFullscreenBtn');
    const fullscreenPrev = document.getElementById('fullscreenPrev');
    const fullscreenNext = document.getElementById('fullscreenNext');
    const fullscreenPlayPause = document.getElementById('fullscreenPlayPause');
    
    let isPlaying = true;
    let hideControlsTimeout;
    let hideCursorTimeout;

    // Enter fullscreen
    function enterFullscreen() {
        if (heroSection.requestFullscreen) {
            heroSection.requestFullscreen();
        } else if (heroSection.webkitRequestFullscreen) {
            heroSection.webkitRequestFullscreen();
        } else if (heroSection.mozRequestFullScreen) {
            heroSection.mozRequestFullScreen();
        } else if (heroSection.msRequestFullscreen) {
            heroSection.msRequestFullscreen();
        }
    }

    // Exit fullscreen
    function exitFullscreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }

    // Check if in fullscreen
    function isFullscreen() {
        return !!(document.fullscreenElement || 
                  document.webkitFullscreenElement || 
                  document.mozFullScreenElement || 
                  document.msFullscreenElement);
    }

    // Auto-hide controls after 3 seconds of inactivity
    function resetHideControlsTimer() {
        heroSection.classList.remove('hide-controls', 'hide-cursor');
        clearTimeout(hideControlsTimeout);
        clearTimeout(hideCursorTimeout);
        
        if (isFullscreen()) {
            hideControlsTimeout = setTimeout(() => {
                heroSection.classList.add('hide-controls');
            }, 3000);
            
            hideCursorTimeout = setTimeout(() => {
                heroSection.classList.add('hide-cursor');
            }, 3000);
        }
    }

    // Toggle play/pause
    function togglePlayPause() {
        const playIcon = fullscreenPlayPause.querySelector('.play-icon');
        const pauseIcon = fullscreenPlayPause.querySelector('.pause-icon');
        
        if (isPlaying) {
            heroSwiper.autoplay.stop();
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
        } else {
            heroSwiper.autoplay.start();
            playIcon.classList.add('hidden');
            pauseIcon.classList.remove('hidden');
        }
        isPlaying = !isPlaying;
    }

    // Event listeners
    fullscreenBtn.addEventListener('click', enterFullscreen);
    exitFullscreenBtn.addEventListener('click', exitFullscreen);
    
    fullscreenPrev.addEventListener('click', () => {
        heroSwiper.slidePrev();
        resetHideControlsTimer();
    });
    
    fullscreenNext.addEventListener('click', () => {
        heroSwiper.slideNext();
        resetHideControlsTimer();
    });
    
    fullscreenPlayPause.addEventListener('click', () => {
        togglePlayPause();
        resetHideControlsTimer();
    });

    // Mouse movement in fullscreen
    heroSection.addEventListener('mousemove', resetHideControlsTimer);
    heroSection.addEventListener('click', (e) => {
        if (isFullscreen() && e.target.tagName !== 'BUTTON') {
            resetHideControlsTimer();
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        // Only handle if heroSection exists
        if (!heroSection) return;

        // F key for fullscreen
        if (e.key === 'f' || e.key === 'F') {
            if (isFullscreen()) {
                exitFullscreen();
            } else {
                enterFullscreen();
            }
        }
        
        // ESC is handled automatically by browser
        // But we can add extra handling
        if (e.key === 'Escape' && isFullscreen()) {
            exitFullscreen();
        }
        
        // Space for play/pause
        if (e.key === ' ' && isFullscreen()) {
            e.preventDefault();
            togglePlayPause();
            resetHideControlsTimer();
        }
        
        // Arrow keys for navigation
        if (isFullscreen()) {
            if (e.key === 'ArrowLeft') {
                heroSwiper.slidePrev();
                resetHideControlsTimer();
            } else if (e.key === 'ArrowRight') {
                heroSwiper.slideNext();
                resetHideControlsTimer();
            }
        }
    });

    // Handle fullscreen change events
    function handleFullscreenChange() {
        if (isFullscreen()) {
            console.log('Entered fullscreen mode - autoplay continues');
            resetHideControlsTimer();
            // Update play/pause button state
            const playIcon = fullscreenPlayPause.querySelector('.play-icon');
            const pauseIcon = fullscreenPlayPause.querySelector('.pause-icon');
            if (isPlaying) {
                playIcon.classList.add('hidden');
                pauseIcon.classList.remove('hidden');
            }
            // Ensure autoplay continues in fullscreen
            if (!heroSwiper.autoplay.running) {
                heroSwiper.autoplay.start();
            }
        } else {
            console.log('Exited fullscreen mode - ensuring autoplay continues');
            heroSection.classList.remove('hide-controls', 'hide-cursor');
            clearTimeout(hideControlsTimeout);
            clearTimeout(hideCursorTimeout);
            
            // Force autoplay to resume after exiting fullscreen
            heroSwiper.autoplay.start();
            isPlaying = true;
            
            // Update button state
            const playIcon = fullscreenPlayPause.querySelector('.play-icon');
            const pauseIcon = fullscreenPlayPause.querySelector('.pause-icon');
            playIcon.classList.add('hidden');
            pauseIcon.classList.remove('hidden');
            
            console.log('Autoplay resumed - continuous loop active');
        }
    }

    // Listen for fullscreen change events (cross-browser)
    document.addEventListener('fullscreenchange', handleFullscreenChange);
    document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
    document.addEventListener('mozfullscreenchange', handleFullscreenChange);
    document.addEventListener('MSFullscreenChange', handleFullscreenChange);

    // Double-click to toggle fullscreen
    heroSection.addEventListener('dblclick', () => {
        if (isFullscreen()) {
            exitFullscreen();
        } else {
            enterFullscreen();
        }
    });

    // Ensure autoplay continues when page becomes visible again
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden && !heroSwiper.autoplay.running) {
            console.log('Page visible - resuming autoplay');
            heroSwiper.autoplay.start();
        }
    });

    // Resume autoplay when window regains focus
    window.addEventListener('focus', () => {
        if (!heroSwiper.autoplay.running) {
            console.log('Window focused - resuming autoplay');
            heroSwiper.autoplay.start();
        }
    });

    // Failsafe: Check autoplay status every 5 seconds and restart if stopped
    setInterval(() => {
        if (!heroSwiper.autoplay.running && !isFullscreen()) {
            console.log('Autoplay check - restarting...');
            heroSwiper.autoplay.start();
        }
    }, 5000);

    // Additional safeguard: restart autoplay on any slide transition end
    heroSwiper.on('transitionEnd', function() {
        if (!this.autoplay.running) {
            console.log('Transition end - ensuring autoplay continues');
            this.autoplay.start();
        }
    });
});
</script>

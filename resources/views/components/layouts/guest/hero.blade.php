@php
use App\Services\ImageCleanupService;

$branches = \App\Models\Branch::where('status', 'aktif')
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

<section class="relative w-full h-screen overflow-hidden bg-black">
 
    <button id="fullscreenBtn" class="absolute top-5 right-5 z-30 p-2 bg-white/80 rounded-full hover:bg-white transition">
    
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4h4M4 4l6 6M20 16v4h-4m4 0l-6-6" />
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
    </div>
</section>

<!-- Fullscreen Modal -->
<div id="fullscreenModal" style="display:none;" 
     class="fixed inset-0 bg-black z-50 flex items-center justify-center overflow-hidden">
    
    <!-- Tombol Close -->
    <button id="closeFullscreenBtn" 
        class="absolute top-4 right-4 z-50 p-2 bg-white/80 hover:bg-white rounded-full transition">
        <svg xmlns="http://www.w3.org/2000/svg" 
            class="h-5 w-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <div class="swiper-container fullscreen-swiper w-full h-full">
        <div class="swiper-wrapper">
            @foreach($images as $img)
                <div class="swiper-slide">
                    <img 
                        src="{{ $img }}"
                        alt="Branch Image"
                        class="w-full h-full object-cover object-center transition-all duration-300"
                    >
                </div>
            @endforeach 
        </div>
    </div>
</div>

@endif

<!-- SwiperJS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const heroSwiper = new Swiper(".swiper-container", {
        loop: true,
        autoplay: {  
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        effect: "fade",
        speed: 1000,
    });

    const fullscreenBtn = document.getElementById('fullscreenBtn');
    const fullscreenModal = document.getElementById('fullscreenModal');
    let fullscreenSwiper = null;

    fullscreenBtn.addEventListener('click', () => {
        fullscreenModal.style.display = 'flex';
        if (!fullscreenSwiper) {
            fullscreenSwiper = new Swiper('.fullscreen-swiper', {
                loop: true,
                autoplay: { 
                    delay: 3000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".fullscreen-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".fullscreen-next",
                    prevEl: ".fullscreen-prev",
                },
                effect: "fade",
                speed: 1000,
            });
        }
        heroSwiper.autoplay.stop();
    });

    document.addEventListener('keydown', (e) => {
        if(e.key === "Escape" && fullscreenModal.style.display === 'flex'){
            fullscreenModal.style.display = 'none';
            heroSwiper.autoplay.start();
        }
    });

    // Click outside slide to exit fullscreen
    fullscreenModal.addEventListener('click', (e) => {
        if (e.target === fullscreenModal) {
            fullscreenModal.style.display = 'none';
            heroSwiper.autoplay.start();
        }
    });
});
const closeFullscreenBtn = document.getElementById('closeFullscreenBtn');

closeFullscreenBtn.addEventListener('click', () => {
    fullscreenModal.style.display = 'none';
    heroSwiper.autoplay.start();
});
</script>

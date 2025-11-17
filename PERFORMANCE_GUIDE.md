# Performance Optimization Guide

## Overview
Sistem telah dioptimasi untuk memberikan pengalaman yang cepat dan responsif. Berikut adalah fitur-fitur yang telah diimplementasikan:

## 1. Loading Progress Bar
- **Progress bar animasi** muncul di bagian atas saat halaman loading
- **Warna gradient** (biru → ungu → pink) yang smooth
- **Otomatis muncul** saat:
  - Navigasi antar halaman (wire:navigate)
  - Livewire request (AJAX)
  - Form submission

## 2. Instant Click Feedback
- **Scale animation** saat klik button/link (scale 0.98)
- **Hover effect** smooth (scale 1.05)
- **Visual feedback** instant untuk user

## 3. Link Prefetching
- **Otomatis prefetch** halaman saat hover link dengan `wire:navigate`
- **Delay 100ms** untuk menghindari prefetch berlebihan
- **Cache di browser** untuk navigasi instant

## 4. Debounce for Inputs
- **Function `debounce()`** tersedia global
- **Default 300ms delay** untuk optimize input performance
- **Penggunaan**:
  ```javascript
  const debouncedSearch = debounce((value) => {
      // Livewire call here
      Livewire.emit('search', value);
  }, 300);
  ```

## 5. Image Lazy Loading
- **Lazy load images** dengan IntersectionObserver
- **Gunakan**: `<img data-src="path/to/image.jpg" />`
- **Otomatis load** saat image visible di viewport

## 6. Form Optimization
- **Auto-disable** submit button saat submit
- **Loading spinner** otomatis
- **Prevent double submission**
- **Timeout 10s** untuk re-enable (fallback)

## 7. Table Optimization
- **GPU acceleration** untuk smooth scrolling
- **will-change transform** untuk better performance
- **Otomatis optimize** setiap render

## 8. CSS Optimizations
- **Smooth transitions** (200ms) untuk semua interactive elements
- **Skeleton loading** animation untuk loading states
- **Hardware acceleration** untuk animations
- **Smooth scroll** behavior
- **Reduced motion** support untuk accessibility

## 9. Cache Strategy
- **API Response Cache** class tersedia
- **Default 5 minutes** cache duration
- **Penggunaan**:
  ```javascript
  import { apiCache } from './performance.js';
  
  const cached = apiCache.get('users');
  if (cached) {
      return cached;
  }
  
  const data = await fetch('/api/users');
  apiCache.set('users', data);
  ```

## 10. Preconnect
- **Otomatis preconnect** ke:
  - fonts.bunny.net
  - cdn.jsdelivr.net
- **Mengurangi latency** untuk external resources

## Cara Menggunakan

### Untuk Button dengan Loading State
```blade
<button wire:click="save" wire:loading.attr="disabled" class="active-scale">
    <span wire:loading.remove>Save</span>
    <span wire:loading>
        <svg class="spinner h-4 w-4 inline mr-2" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Loading...
    </span>
</button>
```

### Untuk Link dengan Prefetch
```blade
<a href="{{ route('dashboard') }}" wire:navigate class="active-scale">
    Dashboard
</a>
```

### Untuk Skeleton Loading
```blade
<div wire:loading class="skeleton h-20 w-full"></div>
<div wire:loading.remove>
    <!-- Content here -->
</div>
```

### Untuk Lazy Load Images
```blade
<img data-src="{{ asset('images/large-image.jpg') }}" 
     alt="Lazy loaded image" 
     class="w-full fade-in" />
```

## Performance Metrics
Target metrics yang ingin dicapai:
- **First Contentful Paint**: < 1.5s
- **Time to Interactive**: < 3.5s
- **Click Response**: < 100ms
- **Navigation Speed**: < 500ms

## Browser Support
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Tips Tambahan
1. Gunakan `wire:navigate` untuk semua internal links
2. Tambahkan `wire:loading` states untuk better UX
3. Gunakan debounce untuk search/filter inputs
4. Lazy load images yang tidak visible immediately
5. Minimize Livewire component nesting
6. Use `wire:key` untuk optimize list rendering

## Troubleshooting
- Jika progress bar tidak muncul, check console untuk errors
- Pastikan Livewire v3 terinstall
- Clear browser cache jika perubahan tidak terlihat
- Run `npm run build` untuk production assets

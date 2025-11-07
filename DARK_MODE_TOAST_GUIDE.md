# Dark Mode & Toast Notification Guide

## ✨ Fitur Baru yang Ditambahkan

### 1. 🌙 Dark Mode System
Sistem dark mode yang lengkap dengan:
- **Auto-detection**: Mengikuti system preference secara otomatis
- **Persistent**: Preferensi disimpan di localStorage
- **Toggle UI**: Icon moon/sun yang berubah sesuai theme
- **Smooth Transition**: Transisi yang mulus antara mode

#### Cara Menggunakan:
```javascript
// Dark mode otomatis terdeteksi dari system preference
// Atau bisa diklik manual via toggle button di header/sidebar
```

#### Lokasi Toggle:
- **Desktop Header**: Top-right corner (untuk Vendor role)
- **Sidebar**: Bottom section (untuk role lainnya)
- **Mobile Header**: Top-right corner

### 2. 🔔 Toast Notification System
Toast notification modern menggantikan `alert()`:

#### Fitur:
- ✅ 4 tipe notifikasi: success, error, warning, info
- ✅ Auto-dismiss dengan custom duration
- ✅ Manual close button
- ✅ Smooth animations (slide in/out)
- ✅ Dark mode compatible
- ✅ Stacking support (multiple toasts)
- ✅ Responsive design

#### Cara Menggunakan:

**Dari JavaScript:**
```javascript
// Success notification
window.toast.success('Data berhasil disimpan!');

// Error notification
window.toast.error('Terjadi kesalahan!', 5000); // 5 detik

// Warning notification
window.toast.warning('Peringatan: Data akan dihapus');

// Info notification
window.toast.info('Proses sedang berjalan...');
```

**Dari Livewire Component:**
```php
// Di component PHP
$this->dispatch('notify', 
    title: 'success', 
    message: 'Data berhasil disimpan!',
    timeout: 3000
);

// Types: 'success', 'error', 'warning', 'info'
```

**Contoh Penggunaan di Controller:**
```php
public function save()
{
    try {
        // Save logic...
        $this->dispatch('notify', 
            title: 'success', 
            message: 'User berhasil dibuat!'
        );
    } catch (\Exception $e) {
        $this->dispatch('notify', 
            title: 'error', 
            message: 'Gagal menyimpan data'
        );
    }
}
```

## 🎨 Dark Mode Classes

### Background Colors
```html
<!-- Light/Dark backgrounds -->
<div class="bg-white dark:bg-zinc-800">Content</div>
<div class="bg-gray-50 dark:bg-zinc-900">Page background</div>
<div class="bg-gray-100 dark:bg-zinc-700">Card background</div>
```

### Text Colors
```html
<!-- Light/Dark text -->
<p class="text-gray-900 dark:text-gray-100">Primary text</p>
<p class="text-gray-600 dark:text-gray-400">Secondary text</p>
<p class="text-gray-500 dark:text-gray-500">Muted text</p>
```

### Borders
```html
<!-- Light/Dark borders -->
<div class="border border-gray-200 dark:border-zinc-700">Content</div>
<div class="border-b border-gray-100 dark:border-zinc-800">Divider</div>
```

### Shadows
```html
<!-- Light/Dark shadows -->
<div class="shadow-lg dark:shadow-zinc-900/50">Card with shadow</div>
```

## 📁 File Structure

```
resources/
├── js/
│   ├── app.js              # Main JS with dark mode logic
│   └── toast.js            # Toast notification class
├── views/
│   ├── components/
│   │   └── layouts/
│   │       ├── app.blade.php          # Main layout with toast handler
│   │       └── app/
│   │           ├── header.blade.php   # Header with dark mode toggle
│   │           └── sidebar.blade.php  # Sidebar with dark mode toggle
└── css/
    └── app.css             # Tailwind CSS with dark mode support
```

## 🔧 Technical Details

### Dark Mode Implementation
```javascript
// Auto-detect system preference
const theme = localStorage.getItem('theme') || 
              (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

// Apply dark mode
if (theme === 'dark') {
    document.documentElement.classList.add('dark');
}

// Toggle function
const toggleDarkMode = () => {
    document.documentElement.classList.toggle('dark');
    const isDark = document.documentElement.classList.contains('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
};
```

### Toast Notification Architecture
```javascript
class Toast {
    show(message, type, duration) {
        // Creates toast element
        // Adds animations
        // Auto-removes after duration
    }
    
    success(message, duration) {}
    error(message, duration) {}
    warning(message, duration) {}
    info(message, duration) {}
}

// Global instance
window.toast = new Toast();
```

## 🎯 Migration dari Alert ke Toast

### Before (Old):
```php
$this->dispatch('notify', title: 'success', message: 'Saved!');

// Script di blade:
<script>
    Livewire.on('notify', ({ title, message }) => {
        alert(`${title.toUpperCase()}: ${message}`); // ❌ Ugly!
    });
</script>
```

### After (New):
```php
$this->dispatch('notify', title: 'success', message: 'Saved!');

// Script di blade:
<script>
    Livewire.on('notify', ({ title, message, timeout = 3000 }) => {
        const type = title.toLowerCase();
        window.toast[type](message, timeout); // ✅ Beautiful!
    });
</script>
```

## 💡 Best Practices

### 1. Consistent Dark Mode Support
Selalu tambahkan `dark:` variant untuk setiap style:
```html
<!-- ✅ Good -->
<div class="bg-white dark:bg-zinc-800 text-gray-900 dark:text-gray-100">

<!-- ❌ Bad -->
<div class="bg-white text-gray-900">
```

### 2. Toast Duration Guidelines
```javascript
// Success: 3 seconds (default)
toast.success('Saved!');

// Error: 5 seconds (give time to read)
toast.error('Failed to save!', 5000);

// Warning: 4 seconds
toast.warning('Careful!', 4000);

// Info: 3 seconds
toast.info('Loading...');
```

### 3. Toast Messages
- ✅ Keep messages short and clear
- ✅ Use action-oriented language
- ✅ Include what happened
- ❌ Don't use technical jargon
- ❌ Don't make messages too long

**Examples:**
```javascript
// ✅ Good
toast.success('User created successfully');
toast.error('Failed to delete item');

// ❌ Bad
toast.success('The user has been successfully created in the database');
toast.error('Error: Database connection failed. Please check your configuration');
```

## 🚀 Testing Dark Mode

1. **Manual Toggle**: Click the moon/sun icon
2. **System Preference**: Change OS dark mode setting
3. **Persistence**: Refresh page, theme should persist
4. **Different Pages**: Navigate around, theme should stay consistent

## 🚀 Testing Toast Notifications

```javascript
// Test all types
window.toast.success('This is a success message');
window.toast.error('This is an error message');
window.toast.warning('This is a warning message');
window.toast.info('This is an info message');

// Test with different durations
window.toast.success('Quick toast', 1000);
window.toast.error('Long toast', 10000);

// Test stacking
window.toast.success('Toast 1');
window.toast.info('Toast 2');
window.toast.warning('Toast 3');
```

## 🎨 Customization

### Change Toast Position
Edit `toast.js`:
```javascript
// Current: top-right
this.container.className = 'fixed top-4 right-4 z-[9999]...';

// Options:
// Top-left: 'fixed top-4 left-4 z-[9999]...'
// Bottom-right: 'fixed bottom-4 right-4 z-[9999]...'
// Bottom-left: 'fixed bottom-4 left-4 z-[9999]...'
// Top-center: 'fixed top-4 left-1/2 -translate-x-1/2 z-[9999]...'
```

### Change Dark Mode Colors
Edit Tailwind classes:
```html
<!-- Change from zinc to slate -->
<div class="dark:bg-slate-900">  <!-- instead of dark:bg-zinc-900 -->
```

## 🐛 Troubleshooting

### Dark Mode Not Working
1. Check localStorage: `localStorage.getItem('theme')`
2. Check HTML class: `document.documentElement.classList.contains('dark')`
3. Clear cache and reload
4. Check browser console for errors

### Toast Not Showing
1. Check `window.toast` is defined: `console.log(window.toast)`
2. Check z-index conflicts
3. Check if toast container exists: `document.getElementById('toast-container')`
4. Check browser console for errors

### Toast Behind Elements
Increase z-index in `toast.js`:
```javascript
this.container.className = 'fixed top-4 right-4 z-[99999]...'; // Higher z-index
```

---

**Created**: November 2025
**Last Updated**: November 2025
**Version**: 1.0

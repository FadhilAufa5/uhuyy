# Changelog

## [2.0.0] - November 2025

### 🌙 Added - Dark Mode System
- **Full Dark Mode Support** untuk seluruh aplikasi
  - Auto-detection system preference
  - Persistent theme dengan localStorage
  - Smooth transitions between themes
  - Toggle buttons di header & sidebar (desktop + mobile)
  - Custom dark mode colors optimized untuk readability

### 🔔 Added - Modern Toast Notifications
- **Toast Notification System** menggantikan alert()
  - 4 types: success, error, warning, info
  - Auto-dismiss dengan custom duration
  - Manual close button
  - Smooth slide-in/out animations
  - Dark mode compatible
  - Multiple toast stacking support
  - Responsive design

### ♻️ Refactored - Table Components
- **BaseTableComponent** untuk eliminasi code duplication
  - Users/Table: 170 → 85 baris (-50%)
  - Vendors/Table: 121 → 40 baris (-67%)
  - Assets/Table: 121 → 49 baris (-60%)
  - Total reduction: -230 baris code

### 🔒 Security - API Endpoints
- **Search Select API** security improvements
  - Model whitelist implementation
  - Authentication middleware required
  - Better error handling (403 instead of 404)

### 🎨 Improved - Routes Organization
- Better route grouping dengan prefix()
- Consistent formatting
- Removed commented code
- More readable structure

### 📚 Documentation
- `DATABASE_OPTIMIZATION.md` - Query & index optimization guide
- `DARK_MODE_TOAST_GUIDE.md` - Dark mode & toast usage guide
- `ARCHITECTURE_GUIDE.md` - Development guidelines & best practices
- `REFACTORING_SUMMARY.md` - Complete refactoring details

### 🐛 Fixed
- Restored `branch_id` field dan `branch()` relationship di User model
- Restored `getSessionsProperty()` method di Users/Table
- Fixed dark mode toggle untuk multiple locations

### 🗑️ Removed
- `test_imagick.php` - Unused test file
- Old alert() system
- Code yang di-comment di routes & models

---

## [1.0.0] - Initial Release

### Features
- User Management
- Vendor Management
- Asset Management
- Branch Management
- Role & Permission System
- Media Library Integration
- Excel Export functionality

---

## Migration Notes

### From v1.0 to v2.0

#### Toast Notifications
**Before:**
```javascript
alert('Success!'); // Old way
```

**After:**
```javascript
window.toast.success('Success!'); // New way

// Or from Livewire:
$this->dispatch('notify', title: 'success', message: 'Success!');
```

#### Dark Mode
Dark mode automatically applied. No code changes needed.
Users can toggle via UI buttons in header/sidebar.

#### API Security
If you have custom API endpoints, follow the whitelist pattern:
```php
Route::get('/api/endpoint', function(Request $request) {
    $allowedModels = ['App\\Models\\YourModel'];
    abort_unless(in_array($request->model, $allowedModels), 403);
    // ... your logic
})->middleware('auth');
```

---

## Upgrading

### Step 1: Pull Changes
```bash
git pull origin main
```

### Step 2: Install Dependencies
```bash
npm install
```

### Step 3: Build Assets
```bash
npm run build
# or for development:
npm run dev
```

### Step 4: Clear Cache
```bash
php artisan optimize:clear
php artisan optimize
```

### Step 5: Test
- ✅ Test dark mode toggle
- ✅ Test toast notifications
- ✅ Test table components
- ✅ Test all CRUD operations

---

## Compatibility

- **PHP**: 8.2+
- **Laravel**: 12.0+
- **Node**: 18+
- **Browsers**: 
  - Chrome 90+
  - Firefox 88+
  - Safari 14+
  - Edge 90+

---

## Contributors

- Factory Droid AI Assistant
- [Your Name]

---

## Support

For issues or questions:
1. Check documentation in `/docs` folder
2. Review `ARCHITECTURE_GUIDE.md`
3. Check `DARK_MODE_TOAST_GUIDE.md`
4. Open an issue on repository

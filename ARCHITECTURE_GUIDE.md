# Architecture & Development Guide

## 📁 Project Structure

```
kfa_hl/
├── app/
│   ├── Enums/              # Enums untuk constants (Roles, Permissions, dll)
│   ├── Exports/            # Excel export classes
│   ├── Helpers/            # Helper classes & traits
│   ├── Http/
│   │   └── Controllers/    # Controllers (minimal, mostly using Volt)
│   ├── Jobs/               # Queue jobs
│   ├── Livewire/           # Livewire components
│   │   ├── BaseTableComponent.php  # Base class untuk semua table components
│   │   ├── Users/
│   │   ├── Vendors/
│   │   ├── Assets/
│   │   └── ...
│   ├── Models/             # Eloquent models
│   └── Providers/          # Service providers
├── resources/
│   └── views/
│       ├── livewire/       # Livewire Volt components
│       ├── components/     # Reusable Blade components
│       └── partials/       # Partial views
└── routes/
    ├── web.php             # Web routes (organized by permission)
    └── auth.php            # Authentication routes
```

## 🏗️ Architectural Patterns

### 1. Livewire Volt Components
Project menggunakan **Livewire Volt** untuk reactive components.

**Example**:
```php
// resources/views/livewire/users/index.blade.php
<?php
use Livewire\Volt\Component;

new class extends Component {
    public function render() {
        return view('livewire.users.index');
    }
}
?>
```

### 2. Table Components Pattern
Semua table components extends dari `BaseTableComponent`:

```php
class UsersTable extends BaseTableComponent 
{
    // Required methods
    protected function getModelClass(): string { return User::class; }
    protected function getQuery() { /* custom query logic */ }
    
    // Optional overrides
    protected function getRefreshEvent(): string { return 'refresh-users'; }
}
```

**Benefits**:
- DRY code
- Consistent behavior
- Easy to extend

### 3. Permission-based Routing
Routes diorganisir berdasarkan permissions:

```php
Route::middleware('can:' . Permissions::ManageUsers->value)->group(function () {
    Volt::route('users', 'users.index')->name('users.index');
    Route::get('users-export', ...)->name('users.export');
});
```

## 🔧 Development Guidelines

### Adding New Table Component

1. **Create Model** (jika belum ada)
```bash
php artisan make:model YourModel -m
```

2. **Create Livewire Component**
```php
// app/Livewire/YourModels/Table.php
namespace App\Livewire\YourModels;

use App\Livewire\BaseTableComponent;
use App\Models\YourModel;

class Table extends BaseTableComponent
{
    protected function getModelClass(): string 
    { 
        return YourModel::class; 
    }
    
    protected function getQuery()
    {
        return YourModel::with(['relations'])
            ->when($this->search, fn($q, $search) => 
                $q->whereAny(['column1', 'column2'], 'like', "%{$search}%")
            )
            ->orderByRaw("CASE WHEN {$this->sortField} IS NULL THEN 1 ELSE 0 END, 
                         {$this->sortField} {$this->sortDirection}");
    }
    
    public function render()
    {
        return view('livewire.your-models.table', [
            'records' => $this->getRecords(),
        ]);
    }
}
```

3. **Create View**
```blade
{{-- resources/views/livewire/your-models/table.blade.php --}}
<div>
    {{-- Search & filters --}}
    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search..." />
    
    {{-- Table --}}
    <flux:table>
        @foreach($records as $record)
            <flux:table.row>
                <flux:table.cell>{{ $record->name }}</flux:table.cell>
            </flux:table.row>
        @endforeach
    </flux:table>
    
    {{-- Pagination --}}
    {{ $records->links() }}
</div>
```

4. **Add Route**
```php
Route::middleware('can:' . Permissions::YourPermission->value)->group(function () {
    Volt::route('your-models', 'your-models.index')->name('your-models.index');
});
```

### Adding New Permission

1. **Update Enum**
```php
// app/Enums/Permissions.php
enum Permissions: string
{
    case ManageYourModels = 'manage your models';
}
```

2. **Create Seeder/Migration**
```bash
php artisan make:migration add_your_model_permission
```

### Best Practices

#### ✅ DO
- Use BaseTableComponent untuk table components
- Eager load relationships untuk avoid N+1 queries
- Use Enums untuk constants
- Cache data yang jarang berubah
- Validate user input
- Use query string untuk search/sort state
- Add database indexes untuk frequently searched columns

#### ❌ DON'T
- Duplicate table logic
- Use raw SQL queries tanpa parameter binding
- Fetch all records tanpa pagination
- Skip validation
- Hardcode permissions strings
- Allow unfiltered model access di API endpoints

## 🔐 Security Guidelines

### API Endpoints
Selalu gunakan:
1. **Authentication**: `->middleware('auth')`
2. **Model Whitelist**: Check allowed models
3. **Input Validation**: Validate semua user input
4. **Rate Limiting**: Protect dari abuse

```php
Route::get('/api/endpoint', function(Request $request) {
    $allowedModels = ['App\\Models\\User', ...];
    
    $request->validate([
        'model' => 'required|string',
        'q' => 'required|string|max:100',
    ]);
    
    abort_unless(in_array($request->model, $allowedModels), 403);
    
    // Process...
})->middleware(['auth', 'throttle:60,1']);
```

### File Uploads
- Validate file types & sizes
- Use secure storage paths
- Scan untuk malware jika possible

### Database Queries
- Always use parameter binding
- Never concatenate user input directly
- Use Laravel's query builder or Eloquent

## 🧪 Testing

### Unit Tests
```bash
php artisan make:test YourModelTest --unit
```

### Feature Tests
```bash
php artisan make:test YourFeatureTest
```

### Example Test
```php
public function test_user_can_view_users_list()
{
    $user = User::factory()->create();
    $user->givePermissionTo(Permissions::ManageUsers->value);
    
    $this->actingAs($user)
         ->get(route('users.index'))
         ->assertOk()
         ->assertSeeLivewire('users.index');
}
```

## 📊 Performance Tips

### Query Optimization
```php
// ❌ Bad: N+1 queries
$users = User::all();
foreach ($users as $user) {
    echo $user->role->name; // Queries for each user!
}

// ✅ Good: Eager loading
$users = User::with('role')->get();
foreach ($users as $user) {
    echo $user->role->name; // No additional queries
}
```

### Caching
```php
// Cache data yang jarang berubah
$roles = Cache::remember('roles', now()->addMinutes(10), fn() => 
    Role::all()
);
```

### Pagination
```php
// Always paginate large datasets
$users = User::paginate(10); // Not ->get()
```

## 🔄 Deployment Checklist

- [ ] Run migrations: `php artisan migrate --force`
- [ ] Clear caches: `php artisan optimize:clear`
- [ ] Rebuild cache: `php artisan optimize`
- [ ] Run seeders if needed
- [ ] Compile assets: `npm run build`
- [ ] Set correct permissions on storage/
- [ ] Check .env configuration
- [ ] Test critical features
- [ ] Enable maintenance mode during deployment
- [ ] Monitor logs after deployment

## 📚 Useful Commands

```bash
# Development
php artisan serve
npm run dev

# Database
php artisan migrate
php artisan db:seed
php artisan migrate:fresh --seed

# Cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Code Quality
php artisan pint           # Format code
php artisan test           # Run tests

# Production
php artisan optimize       # Cache everything
php artisan storage:link   # Link storage
```

## 🤝 Contributing

1. Follow PSR-12 coding standard
2. Write meaningful commit messages
3. Add tests untuk new features
4. Update documentation jika diperlukan
5. Use feature branches
6. Code review before merge

---

**For more details**, see:
- `DATABASE_OPTIMIZATION.md` - Database indexing & query optimization
- `REFACTORING_SUMMARY.md` - Recent changes & improvements

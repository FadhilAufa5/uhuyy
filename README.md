# KFA-HL Management System

![Laravel](https://img.shields.io/badge/Laravel-12.0-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue?logo=php)
![Livewire](https://img.shields.io/badge/Livewire-3.0-pink?logo=livewire)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-4.0-38bdf8?logo=tailwindcss)
![License](https://img.shields.io/badge/License-MIT-green)

Sistem manajemen terintegrasi untuk mengelola users, assets, dan branches dengan fitur activity logging, role-based access control, dan optimasi performa tinggi.

## 📋 Table of Contents

- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Configuration](#%EF%B8%8F-configuration)
- [Usage](#-usage)
- [User Roles & Permissions](#-user-roles--permissions)
- [Project Structure](#-project-structure)
- [Development](#-development)
- [Testing](#-testing)
- [Deployment](#-deployment)
- [Documentation](#-documentation)
- [Contributing](#-contributing)
- [License](#-license)

## ✨ Features

### Core Features
- 👥 **User Management** - Comprehensive user management dengan role-based permissions
- 📦 **Asset Management** - Track dan kelola company assets
- 🏪 **Branch (Apotek) Management** - Manage multiple branches/outlets
- 📊 **Activity Log System** - Automatic logging untuk semua user activities
- 📤 **Excel Export** - Export data ke Excel untuk reporting

### UI/UX Features
- 🌙 **Dark Mode** - Fully responsive dark mode dengan auto-detection
- 🔔 **Toast Notifications** - Modern toast system menggantikan alerts
- ⚡ **Performance Optimizations** - Loading progress bar, prefetching, lazy loading
- 📱 **Responsive Design** - Mobile-first design dengan Flux UI components
- 🎨 **Modern Interface** - Clean dan intuitive user interface

### Security Features
- 🔐 **Role-Based Access Control** - Fine-grained permissions system
- 🛡️ **API Security** - Model whitelist dan authentication middleware
- 📝 **Audit Trail** - Complete activity logging untuk compliance
- 🔒 **Secure File Upload** - Validated file uploads dengan proper storage

## 🛠 Tech Stack

### Backend
- **Framework**: Laravel 12.0
- **PHP**: 8.2+
- **Database**: SQLite (configurable untuk MySQL/PostgreSQL)
- **Authentication**: Laravel Breeze
- **Queue**: Database driver

### Frontend
- **Livewire**: 3.0 (Volt components)
- **UI Framework**: Flux UI 2.1
- **CSS**: TailwindCSS 4.0
- **JavaScript**: Vanilla JS dengan optimizations
- **Build Tool**: Vite 6.0

### Key Packages
- **spatie/laravel-permission** - Role & Permission management
- **spatie/laravel-medialibrary** - Media management
- **maatwebsite/excel** - Excel exports
- **spatie/pdf-to-image** - PDF processing

## 📋 Requirements

- PHP 8.2 or higher
- Composer 2.x
- Node.js 18+ & NPM
- SQLite extension enabled (atau MySQL 8.0+/PostgreSQL 13+)
- GD or Imagick extension for image processing

## 🚀 Installation

### 1. Clone Repository

```bash
git clone https://github.com/FadhilAufa5/uhuyy.git
cd kfa_hl
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Database Setup

```bash
# Create SQLite database (default)
touch database/database.sqlite

# Or configure MySQL/PostgreSQL di .env

# Run migrations
php artisan migrate

# Seed database dengan sample data
php artisan db:seed
```

### 6. Storage Setup

```bash
# Create storage link
php artisan storage:link

# Set permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
```

### 7. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 8. Start Development Server

```bash
# Option 1: Single command (recommended)
composer dev

# Option 2: Manual
php artisan serve
php artisan queue:listen
npm run dev
```

Aplikasi akan berjalan di `http://localhost:8000`

## ⚙️ Configuration

### Database Configuration

Edit `.env` file:

```env
# SQLite (default)
DB_CONNECTION=sqlite

# MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kfa_hl
DB_USERNAME=root
DB_PASSWORD=
```

### Queue Configuration

```env
QUEUE_CONNECTION=database
```

Jangan lupa run queue worker:

```bash
php artisan queue:listen
```

### Mail Configuration

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@kfa-hl.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### File Upload Configuration

Default storage menggunakan local disk. Untuk S3/cloud storage, configure di `config/filesystems.php`

## 📖 Usage

### Default Login Credentials

Setelah seeding, gunakan credentials berikut:

```
SuperAdmin:
Email: superadmin@example.com
Password: password

Admin:
Email: admin@example.com  
Password: password

User:
Email: user@example.com
Password: password
```

### Module Access

| Module | SuperAdmin | Admin | User |
|--------|-----------|-------|------|
| Dashboard | ✅ | ✅ | ✅ |
| User Management | ✅ | ✅ | ❌ |
| Asset Management | ✅ | ✅ | ❌ |
| Branch Management | ✅ | ✅ | ❌ |
| Activity Logs | ✅ | ❌ | ❌ |

### Activity Logs

Activity logs otomatis mencatat:
- Login/Logout
- Create/Update/Delete operations
- File uploads
- Custom events

Akses: **SuperAdmin only** via menu "Activity Logs"

## 👥 User Roles & Permissions

### Roles

#### 1. SuperAdmin
- Full system access
- Manage users, roles, permissions
- View activity logs
- Access all modules

#### 2. Admin
- Manage users (non-SuperAdmin)
- Manage assets, branches
- Export data
- Cannot view activity logs

#### 3. User
- Limited access
- View own profile
- Settings access only

### Permissions

```php
// User Management
Permissions::ManageUsers
Permissions::ManageRoles
Permissions::ManagePermissions

// Department/Branch Management
Permissions::ManageDepartments

// Asset Management
Permissions::ListAssets
Permissions::CreateAssets
Permissions::EditAssets
Permissions::DeleteAssets

// Procurement Management
Permissions::ListProcurements
Permissions::CreateProcurements
Permissions::EditProcurements
Permissions::DeleteProcurements
```

## 📁 Project Structure

```
kfa_hl/
├── app/
│   ├── Enums/                      # Enums (Roles, Permissions, dll)
│   ├── Exports/                    # Excel export classes
│   ├── Http/Controllers/           # Controllers
│   ├── Livewire/                   # Livewire components
│   │   ├── BaseTableComponent.php  # Base class untuk tables
│   │   ├── Users/                  # User management components
│   │   ├── Assets/                 # Asset management
│   │   └── ActivityLogs/           # Activity log viewer
│   ├── Models/                     # Eloquent models
│   │   ├── User.php
│   │   ├── Asset.php
│   │   ├── Branch.php
│   │   └── ActivityLog.php
│   └── Traits/                     # Reusable traits
│       └── LogsActivity.php        # Activity logging trait
├── database/
│   ├── migrations/                 # Database migrations
│   └── seeders/                    # Database seeders
├── resources/
│   ├── css/
│   │   └── app.css                 # TailwindCSS + custom styles
│   ├── js/
│   │   ├── app.js                  # Main JavaScript
│   │   └── performance.js          # Performance optimizations
│   └── views/
│       ├── livewire/               # Livewire Volt pages
│       ├── components/             # Blade components
│       └── layouts/                # Layout files
├── routes/
│   ├── web.php                     # Web routes
│   └── auth.php                    # Auth routes
├── public/                         # Public assets
├── storage/                        # File storage
├── tests/                          # PHPUnit tests
├── .env.example                    # Environment template
├── composer.json                   # PHP dependencies
├── package.json                    # Node dependencies
├── vite.config.js                  # Vite configuration
├── ARCHITECTURE_GUIDE.md           # Architecture documentation
├── ACTIVITY_LOG_GUIDE.md           # Activity log usage guide
├── PERFORMANCE_GUIDE.md            # Performance features guide
├── CHANGELOG.md                    # Version history
└── README.md                       # This file
```

## 💻 Development

### Code Standards

Project menggunakan **Laravel Pint** untuk code formatting (PSR-12):

```bash
# Format code
./vendor/bin/pint

# Check without fixing
./vendor/bin/pint --test
```

### Development Commands

```bash
# Start all services
composer dev

# Clear all caches
php artisan optimize:clear

# Rebuild caches
php artisan optimize

# Run queue worker
php artisan queue:listen

# Watch logs
php artisan pail

# Debug mode
php artisan serve --debug
```

### Adding New Table Component

1. **Create Model** (if needed):
```bash
php artisan make:model YourModel -m
```

2. **Create Livewire Component**:
```php
namespace App\Livewire\YourModels;

use App\Livewire\BaseTableComponent;

class Table extends BaseTableComponent
{
    protected function getModelClass(): string 
    { 
        return \App\Models\YourModel::class; 
    }
    
    protected function getQuery()
    {
        return $this->getModelClass()::query()
            ->when($this->search, fn($q, $search) => 
                $q->where('name', 'like', "%{$search}%")
            );
    }
}
```

3. **Add Route**:
```php
Route::middleware('can:permission.name')->group(function () {
    Volt::route('your-models', 'your-models.index')
        ->name('your-models.index');
});
```

### Adding Activity Logging

Tambahkan trait ke model:

```php
use App\Traits\LogsActivity;

class YourModel extends Model
{
    use LogsActivity;
    
    // Otomatis log created, updated, deleted events
}
```

### Custom Activity Log

```php
use App\Traits\LogsActivity;

LogsActivity::logCustomActivity(
    'custom_event',
    'Description of what happened',
    ['key' => 'value'] // Optional properties
);
```

## 🧪 Testing

### Run Tests

```bash
# All tests
php artisan test

# Specific test
php artisan test --filter=UserTest

# With coverage
php artisan test --coverage
```

### Writing Tests

```bash
# Create test
php artisan make:test YourFeatureTest

# Create unit test
php artisan make:test YourUnitTest --unit
```

Example test:
```php
public function test_user_can_view_dashboard()
{
    $user = User::factory()->create();
    
    $this->actingAs($user)
         ->get(route('dashboard'))
         ->assertOk()
         ->assertSeeLivewire('dashboard.index');
}
```

## 🚀 Deployment

### Production Checklist

```bash
# 1. Update dependencies
composer install --optimize-autoloader --no-dev
npm install --production

# 2. Environment
cp .env.example .env
# Edit .env dengan production settings
php artisan key:generate

# 3. Database
php artisan migrate --force

# 4. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build

# 5. Permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 6. Queue worker (supervisor)
php artisan queue:restart
```

### Supervisor Configuration

Create `/etc/supervisor/conf.d/kfa-hl.conf`:

```ini
[program:kfa-hl-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/kfa_hl/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/path/to/kfa_hl/storage/logs/worker.log
```

### Web Server Configuration

#### Nginx

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/kfa_hl/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Environment Variables (Production)

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=kfa_hl_prod
DB_USERNAME=your-username
DB_PASSWORD=your-secure-password

# Queue
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1

# Cache
CACHE_DRIVER=redis

# Session
SESSION_DRIVER=redis

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-mail-username
MAIL_PASSWORD=your-mail-password
MAIL_ENCRYPTION=tls
```

## 📚 Documentation

### Guide Documents

- **[ARCHITECTURE_GUIDE.md](ARCHITECTURE_GUIDE.md)** - Project architecture & development patterns
- **[ACTIVITY_LOG_GUIDE.md](ACTIVITY_LOG_GUIDE.md)** - Activity logging system usage
- **[PERFORMANCE_GUIDE.md](PERFORMANCE_GUIDE.md)** - Performance optimization features
- **[CHANGELOG.md](CHANGELOG.md)** - Version history & updates
- **[DATABASE_OPTIMIZATION.md](DATABASE_OPTIMIZATION.md)** - Database query optimization
- **[TESTING_ACTIVITY_LOG.md](TESTING_ACTIVITY_LOG.md)** - Activity log testing guide

### API Documentation

API endpoints (internal use):

```
GET /api/search-select
- Purpose: Dynamic search untuk select inputs
- Auth: Required
- Params: model, column, value, q
- Returns: JSON array
```

### Livewire Components

#### BaseTableComponent

Base class untuk semua table components dengan fitur:
- Search functionality
- Sorting (asc/desc)
- Pagination (10, 25, 50, 100 per page)
- Query string persistence
- Automatic refresh events

#### Usage Example

```php
class YourTable extends BaseTableComponent
{
    // Required
    protected function getModelClass(): string { ... }
    protected function getQuery() { ... }
    
    // Optional overrides
    protected function getRefreshEvent(): string { ... }
    public $perPageOptions = [10, 25, 50, 100];
}
```

## 🤝 Contributing

Contributions are welcome! Please follow these guidelines:

### Development Workflow

1. Fork the repository
2. Create feature branch: `git checkout -b feature/amazing-feature`
3. Commit changes: `git commit -m 'Add amazing feature'`
4. Push to branch: `git push origin feature/amazing-feature`
5. Open Pull Request

### Commit Message Convention

```
type(scope): subject

body (optional)

footer (optional)
```

Types:
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation
- `style`: Formatting
- `refactor`: Code restructuring
- `test`: Adding tests
- `chore`: Maintenance

Example:
```
feat(users): add email verification

- Implement email verification flow
- Add verification notification
- Update user table migration

Closes #123
```

### Code Review Guidelines

- ✅ Follow PSR-12 coding standards
- ✅ Write meaningful tests
- ✅ Update documentation
- ✅ No breaking changes without discussion
- ✅ Performance considerations
- ✅ Security best practices

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Authors

- **Development Team** - Initial work
- **Factory Droid** - AI Assistant for refactoring & optimization

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [Livewire](https://livewire.laravel.com) - Dynamic Laravel components
- [Flux UI](https://flux-ui.com) - Beautiful UI components
- [TailwindCSS](https://tailwindcss.com) - Utility-first CSS framework
- [Spatie](https://spatie.be) - Amazing Laravel packages

## 📞 Support

### Getting Help

- 📖 Check [Documentation](#-documentation)
- 🐛 Report bugs via [GitHub Issues](https://github.com/FadhilAufa5/uhuyy/issues)
- 💬 Discussions via [GitHub Discussions](https://github.com/FadhilAufa5/uhuyy/discussions)

### Troubleshooting

#### Common Issues

**1. Asset not loading?**
```bash
npm run build
php artisan optimize:clear
```

**2. Permission denied errors?**
```bash
chmod -R 775 storage bootstrap/cache
```

**3. Database errors?**
```bash
php artisan migrate:fresh --seed
```

**4. Queue not processing?**
```bash
php artisan queue:restart
php artisan queue:listen
```

**5. Dark mode not working?**
- Clear browser cache
- Check localStorage for `theme` key
- Rebuild assets: `npm run build`

---

**Made with ❤️ using detoouhuy**

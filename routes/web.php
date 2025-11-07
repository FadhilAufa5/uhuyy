<?php

use App\Enums\Permissions;
use App\Enums\Roles;
use App\Exports\UserExport;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', fn() => view('welcome'))->name('home');

Volt::route('register-vendor', 'guest.register-with-vendor')
    ->name('vendor.register')
    ->middleware('guest');

Volt::route('resources', 'guest.resources-download')->name('resources.download');

Route::middleware(['auth', 'verified'])->group(function () {
    Volt::route('dashboard', 'dashboard.index')->name('dashboard');

    Route::middleware('can:' . Permissions::ManageUsers->value)->group(function () {
        Volt::route('users', 'users.index')->name('users.index');
        Route::get('users-export', fn() => Excel::download(new UserExport, 'users.xlsx'))
            ->name('users.export');
    });

    Route::middleware('can:' . Permissions::ManageDepartments->value)->group(function () {
        Volt::route('branches', 'branches.index')->name('branches.index');
        Route::get('branches-export', fn() => Excel::download(new UserExport, 'branches.xlsx'))
            ->name('branches.export');

        Volt::route('apoteks', 'apoteks.index')->name('apoteks.index');
        Route::get('apoteks-export', fn() => Excel::download(new UserExport, 'apoteks.xlsx'))
            ->name('apoteks.export');
    });

    Route::middleware('can:' . Permissions::ListVendors->value)->group(function () {
        Volt::route('vendors', 'vendors.index')->name('vendors.index');
        Route::get('vendors-export', fn() => Excel::download(new UserExport, 'vendors.xlsx'))
            ->name('vendors.export');
    });

    Route::middleware('can:' . Permissions::ListAssets->value)->group(function () {
        Volt::route('assets', 'assets.index')->name('assets.index');
        Route::get('assets-export', fn() => Excel::download(new UserExport, 'assets.xlsx'))
            ->name('assets.export');
    });

    Route::prefix('settings')->middleware('role:' . Roles::Vendor->value)->group(function () {
        Volt::route('vendor', 'vendor-settings.index')->name('settings.vendor');
        Volt::route('vendor-persons', 'vendor-settings.persons')->name('settings.vendor-pics');
        Volt::route('vendor-bank', 'vendor-settings.bank')->name('settings.vendor-banks');
        Volt::route('vendor-documents', 'vendor-settings.documents')->name('settings.vendor-documents');
        Volt::route('vendor-experiences', 'vendor-settings.experiences')->name('settings.vendor-experiences');
    });
});

Route::middleware('auth')->prefix('settings')->group(function () {
    Route::redirect('/', 'settings/profile');
    Volt::route('profile', 'settings.profile')->name('settings.profile');
    Volt::route('password', 'settings.password')->name('settings.password');
    Volt::route('appearance', 'settings.appearance')->name('settings.appearance');
});

Route::get('/api/search-select', function (\Illuminate\Http\Request $request) {
    $allowedModels = [
        'App\\Models\\User',
        'App\\Models\\Branch',
        'App\\Models\\Vendor',
        'App\\Models\\Asset',
        'App\\Models\\Apotek',
        'App\\Models\\Bank',
        'App\\Models\\Category',
        'App\\Models\\Subcategory',
    ];

    $model = urldecode($request->model);
    $searchColumn = $request->column;
    $valueColumn = $request->value;
    $query = $request->q;

    abort_unless(in_array($model, $allowedModels) && class_exists($model), 403, 'Model not allowed');

    return response()->json(
        $model::where($searchColumn, 'like', "%{$query}%")
            ->limit(10)
            ->get([$valueColumn, $searchColumn])
    );
})->middleware('auth');

require __DIR__.'/auth.php';

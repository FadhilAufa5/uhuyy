<?php

use App\Enums\Permissions;
use App\Enums\Roles;
use App\Exports\UserExport;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', fn() => view('welcome'))->name('home');

Volt::route('resources', 'guest.resources-download')->name('resources.download');

Route::middleware(['auth', 'verified'])->group(function () {
    Volt::route('dashboard', 'dashboard.index')->name('dashboard');

    Route::middleware('can:' . Permissions::ManageUsers->value)->group(function () {
        Volt::route('users', 'users.index')->name('users.index');
        Route::get('users-export', fn() => Excel::download(new UserExport, 'users.xlsx'))
            ->name('users.export');
    });

    Route::middleware('role:' . Roles::SuperAdmin->value)->group(function () {
        Volt::route('activity-logs', 'activity-logs.index')->name('activity-logs.index');
    });

    Route::middleware('can:' . Permissions::ManageDepartments->value)->group(function () {
        Volt::route('branches', 'branches.index')->name('branches.index');
        Route::get('branches-export', fn() => Excel::download(new UserExport, 'branches.xlsx'))
            ->name('branches.export');

        Volt::route('apoteks', 'apoteks.index')->name('apoteks.index');
        Route::get('apoteks-export', fn() => Excel::download(new UserExport, 'apoteks.xlsx'))
            ->name('apoteks.export');
    });

    Route::middleware('can:' . Permissions::ListAssets->value)->group(function () {
        Volt::route('assets', 'assets.index')->name('assets.index');
        Route::get('assets-export', fn() => Excel::download(new UserExport, 'assets.xlsx'))
            ->name('assets.export');
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

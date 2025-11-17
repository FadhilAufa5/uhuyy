<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke()
    {
        $user = Auth::user();
        
        // Log logout activity before logging out
        if ($user) {
            \App\Traits\LogsActivity::logCustomActivity(
                'logged_out',
                $user->name . ' logged out from the system'
            );
        }

        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        return redirect('/');
    }
}

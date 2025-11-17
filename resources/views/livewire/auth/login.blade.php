<?php

use App\Helpers\WithToast;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    use WithToast;

    #[Validate('required|string')]
    public string $creds = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        $credentials = filter_var($this->creds, FILTER_VALIDATE_EMAIL)
            ? ['email' => $this->creds, 'password' => $this->password]
            : ['username' => $this->creds, 'password' => $this->password];

        if (!Auth::attempt($credentials, $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'creds' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        // Log login activity
        \App\Traits\LogsActivity::logCustomActivity(
            'logged_in',
            auth()->user()->name . ' logged in to the system'
        );

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        $this->toast('Selamat datang, ' . auth()->user()->name . '.', 'success');
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'creds' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->creds) . '|' . request()->ip());
    }
}; ?>

<div class="flex flex-col gap-8">
    <x-auth-header :title="__('Login')" :description="__('')"/>

    <x-auth-session-status class="text-center" :status="session('status')"/>

    <form wire:submit="login" class="flex flex-col gap-5">
        <div>
            <flux:input
                wire:model="creds"
                type="text"
                name="creds"
                required
                autofocus
                autocomplete="creds"
                placeholder="Username or email"
            />
        </div>

        <div>
            {{-- <div class="flex items-center justify-between mb-1.5">
                <flux:label>Password</flux:label>
                @if (Route::has('password.request'))
                    <flux:link class="text-xs" :href="route('password.request')" wire:navigate>
                        Forgot?
                    </flux:link>
                @endif
            </div> --}}
            <flux:input
                wire:model="password"
                type="password"
                required
                autocomplete="current-password"
                placeholder="Enter password"
                viewable
            />
        </div>

        <flux:checkbox wire:model="remember" label="Remember me"/>

        <flux:button 
            variant="primary" 
            type="submit" 
            class="w-full mt-2"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove wire:target="login">Login</span>
            <span wire:loading wire:target="login">
                <svg class="animate-spin h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
        </flux:button>
    </form>
</div>

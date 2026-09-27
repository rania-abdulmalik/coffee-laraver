<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

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

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
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
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div class="flex flex-col gap-6">
    
    <!-- النصوص العلوية مطابقة للصورة -->
    <div class="text-center mb-2">
        <h2 class="text-3xl font-normal mb-1" style="color: #4E342E;">Login</h2>
        <p class="text-base mb-6" style="color: #4E342E;">Welcome Back ☕</p>
        <h3 class="text-xl font-bold" style="color: #795548;">Login to continue to Mazaj</h3>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input 
            wire:model="email" 
            label="{{ __('Email address') }}" 
            type="email" 
            name="email" 
            required 
            autofocus 
            autocomplete="email" 
            placeholder="test@gmail.com" 
            style="border-color: #8D6E63 !important;" 
            class="focus:!border-[#4E342E]" 
        />

        <!-- Password -->
        <div class="relative">
            <flux:input
                wire:model="password"
                label="{{ __('Password') }}"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="........"
                style="border-color: #8D6E63 !important;"
                class="focus:!border-[#4E342E]"
            />

            @if (Route::has('password.request'))
                <x-text-link class="absolute right-0 top-0" href="{{ route('password.request') }}" style="color: #795548; text-decoration: underline;">
                    {{ __('Forgot your password?') }}
                </x-text-link>
            @endif
        </div>

        <!-- Remember Me -->
        <flux:checkbox wire:model="remember" label="{{ __('Remember me') }}" />

        <div class="flex items-center justify-end mt-2">
            <!-- زر تسجيل الدخول بلون بني صريح -->
            <flux:button variant="primary" type="submit" class="w-full text-lg font-bold text-white border-none py-3 rounded-xl" style="background-color: #4E342E !important;">
                {{ __('Login') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm mt-2" style="color: #795548;">
        Don't have an account?
        <x-text-link href="{{ route('register') }}" style="color: #4E342E; font-weight: bold;">Sign up</x-text-link>
    </div>
</div>
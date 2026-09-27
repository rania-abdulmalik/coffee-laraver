<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    
    <!-- النصوص والصورة -->
    <div class="text-center mb-2">
        <h2 class="text-4xl font-bold mb-4" style="color: #4E342E;">Mazaj</h2>
        <img src="{{asset('storage/images/coffee_cup.png') }}" alt="Mazaj Coffee" class="w-48 h-auto mx-auto mb-4 object-contain">
        <p class="mt-2 font-semibold text-xl" style="color: #795548;">Brewed for Your Mood</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <div class="grid gap-2">
            <flux:input wire:model="name" id="name" label="{{ __('Name') }}" type="text" name="name" required autofocus autocomplete="name" placeholder="Full name" style="border-color: #8D6E63 !important;" class="focus:!border-[#4E342E]" />
        </div>

        <!-- Email Address -->
        <div class="grid gap-2">
            <flux:input wire:model="email" id="email" label="{{ __('Email address') }}" type="email" name="email" required autocomplete="email" placeholder="email@example.com" style="border-color: #8D6E63 !important;" class="focus:!border-[#4E342E]" />
        </div>

        <!-- Password -->
        <div class="grid gap-2">
            <flux:input
                wire:model="password"
                id="password"
                label="{{ __('Password') }}"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Password"
                style="border-color: #8D6E63 !important;"
                class="focus:!border-[#4E342E]"
            />
        </div>

        <!-- Confirm Password -->
        <div class="grid gap-2">
            <flux:input
                wire:model="password_confirmation"
                id="password_confirmation"
                label="{{ __('Confirm password') }}"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Confirm password"
                style="border-color: #8D6E63 !important;"
                class="focus:!border-[#4E342E]"
            />
        </div>

        <div class="flex items-center justify-end mt-4">
            <!-- زر التسجيل بلون بني صريح -->
            <flux:button type="submit" variant="primary" class="w-full text-lg font-bold text-white border-none py-3 rounded-xl" style="background-color: #4E342E !important;">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm mt-2" style="color: #795548;">
        Already have an account?
        <x-text-link href="{{ route('login') }}" style="color: #4E342E; font-weight: bold;">Log in</x-text-link>
    </div>
</div>
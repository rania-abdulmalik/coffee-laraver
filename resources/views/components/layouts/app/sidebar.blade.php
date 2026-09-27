<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        @include('partials.head')
        <style>
            .mazaj-sidebar {
                background-color: #ffffff !important;
                border-right: 1px solid #e5e7eb !important;
                width: 280px !important;
                padding: 0 !important;
            }
            /* صندوق الترويسة العلوي بلون بني بالكامل */
            .mazaj-header-section {
                background-color: #5c3a21 !important;
                padding: 30px 16px 20px 16px !important;
                text-align: center;
                width: 100%;
                margin-bottom: 15px;
            }
            .mazaj-logo-box {
                background-color: #ffffff !important;
                width: 70px;
                height: 70px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 10px auto;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0,0,0,0.15);
            }
            .mazaj-title {
                font-weight: 600;
                color: #ffffff !important;
                font-size: 1.25rem;
                text-align: center;
                margin-bottom: 2px;
            }
            .mazaj-subtitle {
                color: #f3e8df !important;
                font-size: 0.8rem;
                text-align: center;
            }
            
            /* محتوى القائمة بالأسفل */
            .mazaj-body-content {
                padding: 0 16px 24px 16px !important;
                display: flex;
                flex-direction: column;
                height: calc(100% - 155px);
            }

            /* إزالة المربعات والخطوط التحتية وجعل اللون بني */
            .custom-nav-item {
                border: none !important;
                background: transparent !important;
                box-shadow: none !important;
                margin-bottom: 4px !important;
                border-radius: 8px !important;
                transition: background-color 0.2s ease;
            }
            .custom-nav-item:hover {
                background-color: #f5f0eb !important;
            }
            /* تلوين النص والأيقونات باللون البني وإزالة أي خط تحتي */
            .custom-nav-item span, 
            .custom-nav-item svg, 
            .custom-nav-item a,
            .custom-nav-item button {
                color: #5c3a21 !important;
                text-decoration: none !important;
            }
            .platform-heading {
                color: #9ca3af !important;
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-bottom: 8px;
                padding-left: 12px;
            }
        </style>
    </head>
    <body class="min-h-screen bg-white">
        <flux:sidebar sticky stashable class="mazaj-sidebar">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <!-- الترويسة العلوية باللون البني والصورة والاسم بداخلها -->
            <div class="mazaj-header-section">
                <div class="mazaj-logo-box">
                    <img src="{{ asset('storage/images/coffee_cup.png') }}" alt="Mazaj Logo" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <h3 class="mazaj-title">Mazaj</h3>
                <span class="mazaj-subtitle">Brewed for Your Mood</span>
            </div>

            <div class="mazaj-body-content">
                <!-- كلمة Platform -->
                <div class="platform-heading">Platform</div>

                <!-- قائمة التنقل الأساسية (بدون مربعات وبخط بني) -->
                <div class="flex flex-col gap-1">
                    <div class="custom-nav-item">
                        <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>Home</flux:navlist.item>
                    </div>
                    
                
                    
                    <div class="custom-nav-item">
                     <flux:navlist.item 
                        icon="heart" 
                        :href="route('favorites')"
                        :current="request()->routeIs('favorites')"
                        wire:navigate>
                        Favorites
                    </flux:navlist.item>    
                    </div>
                    
                     <div class="custom-nav-item">
                        <flux:navlist.item 
                            icon="shopping-cart" 
                            :href="route('cart')"
                            :current="request()->routeIs('cart')"
                            wire:navigate
                         >
                            My Cart
                        </flux:navlist.item>
                    </div>
                    
                    <div class="custom-nav-item">
                        <flux:navlist.item icon="information-circle" href="#" onclick="alert('About Mazaj')">About Mazaj</flux:navlist.item>
                    </div>
                </div>

                <flux:spacer />

                <!-- الروابط الإضافية -->
                <div class="flex flex-col gap-1 mt-4">
                    <div class="custom-nav-item">
                        <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                            Repository
                        </flux:navlist.item>
                    </div>

                    <div class="custom-nav-item">
                        <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits" target="_blank">
                            Documentation
                        </flux:navlist.item>
                    </div>
                </div>

                <!-- خط فاصل سفلي -->
                <div class="w-full my-4">
                    <div class="border-t border-zinc-200"></div>
                </div>

                <!-- زر الخروج (Exit) -->
                <div class="mb-2 custom-nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:navlist.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-zinc-700">
                            Exit
                        </flux:navlist.item>
                    </form>
                </div>
            </div>

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>
                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>Settings</flux:menu.item>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            <flux:dropdown position="top" align="end">
                <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />
                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>
                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>Settings</flux:menu.item>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
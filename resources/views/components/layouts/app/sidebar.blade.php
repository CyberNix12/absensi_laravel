<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" data-theme="flux">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
<flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
        <x-app-logo />
    </a>

    <flux:navlist variant="outline">
        <flux:navlist.group :heading="__('Menu')" class="grid">
            <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                wire:navigate>{{ __('Dashboard') }}
            </flux:navlist.item>
            <flux:navlist.item icon="calendar-days" :href="route('data-presensi')"
                :current="request()->routeIs('data-presensi')" wire:navigate>
                {{ __('Data Presensi') }}
            </flux:navlist.item>
            @if (auth()->user()->isAdmin())
                <flux:navlist.item icon="user-group" :href="route('karyawan')"
                    :current="request()->routeIs('karyawan')" wire:navigate>
                    {{ __('Karyawan') }}
                </flux:navlist.item>
            @endif
            {{-- Menu Pengajuan Izin untuk karyawan --}}
            @if(auth()->user()->role == 'karyawan')
                <flux:navlist.item icon="clipboard-document-list" :href="route('pengajuan-izin.index')" :current="request()->routeIs('pengajuan-izin.*')" wire:navigate>
                    {{ __('Pengajuan Izin') }}
                </flux:navlist.item>
            @endif
            {{-- Menu Rekap Pengajuan Izin untuk admin dan manager --}}
            @if(auth()->user()->role == 'admin' || auth()->user()->role == 'manager')
                <flux:navlist.item icon="clipboard-document-list" :href="route('pengajuan-izin.index')" :current="request()->routeIs('pengajuan-izin.index')" wire:navigate>
                    {{ __('Rekap Pengajuan Izin') }}
                </flux:navlist.item>
            @endif
            {{-- Menu Rekap Kehadiran untuk manager --}}
            @if (auth()->user()->isManager && function_exists('route') && Route::has('manager.rekap-kehadiran'))
                <flux:navlist.item icon="chart-bar" :href="route('manager.rekap-kehadiran')" :current="request()->routeIs('manager.rekap-kehadiran')" wire:navigate>
                    {{ __('Rekap Kehadiran') }}
                </flux:navlist.item>
            @endif
        </flux:navlist.group>
    </flux:navlist>

    <flux:spacer />

    <!-- Desktop User Menu -->
    <flux:dropdown position="bottom" align="start">
        <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
            icon-trailing="chevrons-up-down"
            class="bg-neutral-300 hover:!bg-neutral-200 dark:bg-neutral-800 hover:dark:!bg-neutral-700 transition duration-300 ease-in-out" />

        <flux:menu class="w-[220px]">
            <flux:menu.radio.group>
                <div class="p-0 text-sm font-normal">
                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                        <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                            <span
                                class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                {{ auth()->user()->initials() }}
                            </span>
                        </span>

                        <div class="grid flex-1 text-start text-sm leading-tight">
                            <span class="truncate font-semibold text-base-300 dark:text-white">{{ auth()->user()->name }}</span>
                            <span class="truncate text-xs text-base-100 dark:text-neutral-300">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                </div>
            </flux:menu.radio.group>

            <flux:menu.separator />

            <flux:menu.radio.group>
                <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}
                </flux:menu.item>
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

    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
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

    @stack('modals')
    @stack('scripts')
</body>

</html>
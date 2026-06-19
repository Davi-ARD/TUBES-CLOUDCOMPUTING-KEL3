<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Direktorat Kemahasiswaan</title>
    <link rel="icon" type="image/webp" href="{{ asset('img/logo-telkom-iconweb.webp') }}">
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ sidebarOpen: false }" class="bg-gray-50" style="background-color: var(--surface-alt);">

    {{-- ===== TOPBAR ===== --}}
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-6 bg-white border-b border-gray-100"
            style="height: var(--topbar-height); box-shadow: 0 1px 4px rgba(0,0,0,0.06);">

        {{-- Kiri: Hamburger (mobile) + Logo --}}
        <div class="flex items-center gap-3">
            <button type="button" @click="sidebarOpen = true"
                    class="app-hamburger items-center justify-center rounded-lg"
                    aria-label="Buka menu"
                    style="width: 40px; height: 40px; border: none; background: transparent; cursor: pointer; color: var(--ink-700);">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3" style="text-decoration:none;">
                <img src="{{ asset('img/logo-direktorat.png') }}" alt="Direktorat Kemahasiswaan" style="height: 36px; width: auto;">
            </a>
        </div>

        {{-- Kanan: Dropdown user --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.outside="open = false"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-all"
                    style="color: var(--ink-700);">
                <img src="{{ auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : asset('img/default-avatar.svg') }}"
                     alt="Foto profil"
                     class="w-8 h-8 rounded-full object-cover" style="border: 1px solid var(--surface-muted);">

                <span class="hidden md:block max-w-xs truncate" style="max-width: 128px;">{{ explode(' ', auth()->user()->name)[0] }}</span>
                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-52 bg-white rounded-xl border border-gray-100 py-1"
                 style="box-shadow: 0 8px 24px rgba(0,0,0,0.1); z-index:100;"
                 x-cloak>
                <div class="px-4 py-2 border-b" style="border-color: var(--surface-muted);">
                    <p class="text-xs font-semibold truncate" style="color: var(--ink-900);">{{ auth()->user()->name }}</p>
                    <p class="text-xs truncate" style="color: var(--ink-500);">{{ auth()->user()->email }}</p>
                </div>
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-50 transition-all"
                   style="color: var(--ink-700); text-decoration:none;">
                    <i data-lucide="user" class="w-4 h-4"></i> Profil Saya
                </a>
                <div class="border-t mt-1" style="border-color: var(--surface-muted);">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-2 px-4 py-2 w-full text-left text-sm hover:bg-red-50 transition-all"
                                style="color: #D32F2F; background:transparent; border:none; cursor:pointer;">
                            <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="flex" style="padding-top: var(--topbar-height);">

        {{-- ===== OVERLAY (mobile) ===== --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="sidebar-overlay fixed inset-0"
             style="background: rgba(0,0,0,0.45); z-index: 45;"
             x-cloak></div>

        {{-- ===== SIDEBAR ===== --}}
        <aside class="app-sidebar fixed left-0 bottom-0 bg-white border-r"
               :class="{ 'is-open': sidebarOpen }"
               style="top: var(--topbar-height); width: var(--sidebar-width); border-color: var(--ink-300); overflow-y: auto; z-index: 46;">

            {{-- Tombol tutup (hanya mobile) --}}
            <div class="flex items-center justify-end px-3 pt-3 md:hidden">
                <button type="button" @click="sidebarOpen = false" aria-label="Tutup menu"
                        class="inline-flex items-center justify-center rounded-lg"
                        style="width:40px; height:40px; border:none; background:transparent; cursor:pointer; color:var(--ink-700);">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <nav x-data="{ openLaporan: {{ request()->routeIs('proposal.*', 'lpj.*') ? 'true' : 'false' }} }"
                 class="p-4 space-y-1">

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                   class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0"></i>
                    <span>Dashboard</span>
                </a>

                {{-- Generate Dokumen (expandable) --}}
                <div>
                    <button @click="openLaporan = !openLaporan"
                            class="nav-item {{ request()->routeIs('proposal.*', 'lpj.*') ? 'active' : '' }}">
                        <i data-lucide="file-text" class="w-4 h-4 shrink-0"></i>
                        <span class="flex-1 text-left">Generate Dokumen</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 shrink-0 transition-transform duration-200"
                           :class="{ 'rotate-180': openLaporan }"></i>
                    </button>

                    <div x-show="openLaporan" x-collapse class="pl-4 mt-1 space-y-0.5">
                        <a href="{{ route('proposal.create') }}"
                           class="nav-sub-item {{ request()->routeIs('proposal.create') ? 'active' : '' }}">
                            <span class="flex items-center gap-2">
                                <i data-lucide="file-plus-2" class="w-3.5 h-3.5"></i>
                                Generate Proposal
                            </span>
                        </a>
                        <a href="{{ route('lpj.create') }}"
                           class="nav-sub-item {{ request()->routeIs('lpj.create') ? 'active' : '' }}">
                            <span class="flex items-center gap-2">
                                <i data-lucide="clipboard-check" class="w-3.5 h-3.5"></i>
                                Generate LPJ
                            </span>
                        </a>
                    </div>
                </div>

                {{-- Profil --}}
                <a href="{{ route('profile.edit') }}"
                   class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i data-lucide="user" class="w-4 h-4 shrink-0"></i>
                    <span>Profil</span>
                </a>

                <div class="py-2">
                    <div class="border-t" style="border-color: var(--ink-300);"></div>
                </div>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-item w-full" style="color: #D32F2F;">
                        <i data-lucide="log-out" class="w-4 h-4 shrink-0"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </nav>
        </aside>

        {{-- ===== MAIN CONTENT ===== --}}
        <main class="app-main flex-1 min-w-0 overflow-x-hidden min-h-screen page-enter flex flex-col"
              style="margin-left: var(--sidebar-width);">

            <div class="app-content flex-1">
                <x-flash-message />

                @yield('content')
            </div>

            @include('partials.footer')
        </main>
    </div>

    @stack('scripts')
</body>
</html>

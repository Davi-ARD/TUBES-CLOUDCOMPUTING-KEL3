@extends('layouts.guest')
@section('title', 'Masuk')

@section('content')
    <h2 style="font-size: 24px; font-weight: 700; color: var(--ink-900); margin-bottom: 8px;">
        Selamat Datang
    </h2>
    <p style="font-size: 14px; color: var(--ink-500); margin-bottom: 32px;">
        Masuk ke akun Anda untuk melanjutkan
    </p>

    {{-- Error umum (kredensial salah) --}}
    @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
        <div class="alert-error mb-4">
            <i data-lucide="alert-circle" class="w-4 h-4"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    {{-- Status session (misal setelah reset password) --}}
    @if (session('status'))
        <div class="alert-success mb-4">
            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate
          x-data="{ loading: false }" @submit="loading = true">
        @csrf

        {{-- Email --}}
        <div>
            <label class="form-label" for="email">Alamat Email</label>
            <input id="email" type="email" name="email"
                   class="form-input @error('email') border-red-400 @enderror"
                   value="{{ old('email') }}"
                   required autofocus autocomplete="username">
            @error('email')
                <p class="field-error">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ $message }}</span>
                </p>
            @enderror
        </div>

        {{-- Password dengan toggle --}}
        <div x-data="{ showPass: false }">
            <label class="form-label" for="password">Kata Sandi</label>
            <div class="relative">
                <input id="password"
                       :type="showPass ? 'text' : 'password'"
                       name="password"
                       class="form-input @error('password') border-red-400 @enderror"
                       required autocomplete="current-password"
                       style="padding-right: 44px;">
                <button type="button"
                        @click="showPass = !showPass"
                        class="absolute right-3 top-1/2 -translate-y-1/2"
                        style="background:none; border:none; cursor:pointer; color: var(--ink-500); padding: 4px;">
                    <i :data-lucide="showPass ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                </button>
            </div>
            @error('password')
                <p class="field-error">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ $message }}</span>
                </p>
            @enderror
        </div>

        {{-- Remember me --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer" style="font-size: 13px; color: var(--ink-500);">
                <input type="checkbox" name="remember" id="remember_me"
                       class="rounded"
                       style="accent-color: var(--telkom-red); width: 15px; height: 15px;">
                <span>Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   style="font-size: 13px; color: var(--telkom-red); text-decoration: none;">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-primary w-full justify-center" :disabled="loading"
                style="width: 100%; justify-content: center; margin-top: 8px; gap: 10px;">
            <span x-show="loading" class="btn-spinner" x-cloak></span>
            <span x-text="loading ? 'Memproses…' : 'Masuk'">Masuk</span>
        </button>

        {{-- Link register --}}
        <p style="text-align: center; font-size: 13px; color: var(--ink-500); margin-top: 16px;">
            Belum punya akun?
            <a href="{{ route('register') }}" style="color: var(--telkom-red); font-weight: 600; text-decoration: none;">
                Daftar di sini
            </a>
        </p>
    </form>
@endsection

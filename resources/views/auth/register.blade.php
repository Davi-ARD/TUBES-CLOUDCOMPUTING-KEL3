@extends('layouts.guest')
@section('title', 'Daftar')

@section('content')
    <h2 style="font-size: 24px; font-weight: 700; color: var(--ink-900); margin-bottom: 8px;">
        Buat Akun Baru
    </h2>
    <p style="font-size: 14px; color: var(--ink-500); margin-bottom: 20px;">
        Isi data di bawah untuk mendaftar
    </p>

    <form method="POST" action="{{ route('register') }}" class="space-y-3" novalidate>
        @csrf

        {{-- Nama Lengkap --}}
        <div>
            <label class="form-label" for="name">Nama Lengkap <span style="color: var(--telkom-red);">*</span></label>
            <input id="name" type="text" name="name"
                   class="form-input @error('name') border-red-400 @enderror"
                   value="{{ old('name') }}"
                   required autofocus autocomplete="name">
            @error('name')
                <p class="field-error">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ $message }}</span>
                </p>
            @enderror
        </div>

        {{-- UKM & Jabatan (2 kolom) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- UKM --}}
            <div>
                <label class="form-label" for="organization">UKM <span style="color: var(--telkom-red);">*</span></label>
                <input id="organization" type="text" name="organization"
                       class="form-input @error('organization') border-red-400 @enderror"
                       value="{{ old('organization') }}"
                       required>
                @error('organization')
                    <p class="field-error">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            {{-- Jabatan --}}
            <div>
                <label class="form-label" for="position">Jabatan <span style="color: var(--telkom-red);">*</span></label>
                <input id="position" type="text" name="position"
                       class="form-input @error('position') border-red-400 @enderror"
                       value="{{ old('position') }}"
                       required>
                @error('position')
                    <p class="field-error">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>
        </div>

        {{-- Email --}}
        <div>
            <label class="form-label" for="email">Alamat Email <span style="color: var(--telkom-red);">*</span></label>
            <input id="email" type="email" name="email"
                   class="form-input @error('email') border-red-400 @enderror"
                   value="{{ old('email') }}"
                   required autocomplete="username">
            @error('email')
                <p class="field-error">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ $message }}</span>
                </p>
            @enderror
        </div>

        {{-- Password & Konfirmasi (2 kolom) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Password dengan toggle --}}
            <div x-data="{ showPass: false }">
                <label class="form-label" for="password">Kata Sandi <span style="color: var(--telkom-red);">*</span></label>
                <div class="relative">
                    <input id="password"
                           :type="showPass ? 'text' : 'password'"
                           name="password"
                           class="form-input @error('password') border-red-400 @enderror"
                           required autocomplete="new-password"
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

            {{-- Konfirmasi Password --}}
            <div x-data="{ showPass2: false }">
                <label class="form-label" for="password_confirmation">Konfirmasi <span style="color: var(--telkom-red);">*</span></label>
                <div class="relative">
                    <input id="password_confirmation"
                           :type="showPass2 ? 'text' : 'password'"
                           name="password_confirmation"
                           class="form-input"
                           required autocomplete="new-password"
                           style="padding-right: 44px;">
                    <button type="button"
                            @click="showPass2 = !showPass2"
                            class="absolute right-3 top-1/2 -translate-y-1/2"
                            style="background:none; border:none; cursor:pointer; color: var(--ink-500); padding: 4px;">
                        <i :data-lucide="showPass2 ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="field-error">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; margin-top: 8px;">
            Daftar Sekarang
        </button>

        {{-- Link login --}}
        <p style="text-align: center; font-size: 13px; color: var(--ink-500); margin-top: 16px;">
            Sudah punya akun?
            <a href="{{ route('login') }}" style="color: var(--telkom-red); font-weight: 600; text-decoration: none;">
                Masuk di sini
            </a>
        </p>
    </form>
@endsection

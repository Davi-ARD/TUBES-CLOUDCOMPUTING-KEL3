@extends('layouts.guest')
@section('title', 'Reset Password')

@section('content')
    <h2 style="font-size: 24px; font-weight: 700; color: var(--ink-900); margin-bottom: 8px;">
        Buat Password Baru
    </h2>
    <p style="font-size: 14px; color: var(--ink-500); margin-bottom: 32px;">
        Pastikan password baru kamu cukup kuat dan mudah diingat.
    </p>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5" novalidate
          x-data="{ loading: false }" @submit="loading = true">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Email --}}
        <div>
            <label class="form-label" for="email">Alamat Email</label>
            <input id="email" type="email" name="email"
                   class="form-input @error('email') border-red-400 @enderror"
                   value="{{ old('email', $request->email) }}"
                   placeholder="contoh@email.com"
                   required autofocus autocomplete="username">
            @error('email')
                <p class="field-error">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ $message }}</span>
                </p>
            @enderror
        </div>

        {{-- Password Baru --}}
        <div x-data="{ showPass: false }">
            <label class="form-label" for="password">Password Baru</label>
            <div class="relative">
                <input id="password"
                       :type="showPass ? 'text' : 'password'"
                       name="password"
                       class="form-input @error('password') border-red-400 @enderror"
                       placeholder="Minimal 8 karakter"
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
        <div x-data="{ showConfirm: false }">
            <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
            <div class="relative">
                <input id="password_confirmation"
                       :type="showConfirm ? 'text' : 'password'"
                       name="password_confirmation"
                       class="form-input"
                       placeholder="Ulangi password baru"
                       required autocomplete="new-password"
                       style="padding-right: 44px;">
                <button type="button"
                        @click="showConfirm = !showConfirm"
                        class="absolute right-3 top-1/2 -translate-y-1/2"
                        style="background:none; border:none; cursor:pointer; color: var(--ink-500); padding: 4px;">
                    <i :data-lucide="showConfirm ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-primary w-full justify-center" :disabled="loading"
                style="width: 100%; justify-content: center; margin-top: 8px; gap: 10px;">
            <span x-show="loading" class="btn-spinner" x-cloak></span>
            <span x-text="loading ? 'Menyimpan…' : 'Simpan Password Baru'">Simpan Password Baru</span>
        </button>
    </form>
@endsection

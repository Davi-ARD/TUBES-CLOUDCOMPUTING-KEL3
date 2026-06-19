<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk') | Direktorat Kemahasiswaan</title>
    <link rel="icon" type="image/webp" href="{{ asset('img/logo-telkom-iconweb.webp') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data style="font-family: 'Poppins', 'Segoe UI', system-ui, sans-serif; margin: 0; padding: 0; height: 100dvh; overflow: hidden; display: flex;">

    {{-- ===== KIRI: Area Form (mobile: 100%, desktop: 40%) ===== --}}
    <div class="flex flex-col justify-center w-full md:w-2/5 px-6 py-10 md:px-12 md:py-16"
         style="height: 100dvh; overflow-y: auto; background: #fff; box-shadow: 4px 0 24px rgba(0,0,0,0.04);">

        {{-- Logo & Tagline --}}
        <div class="mb-6">
            <a href="{{ route('home') }}" style="text-decoration:none; display: inline-block; margin-bottom: 16px;">
                <img src="{{ asset('img/logo-direktorat.png') }}" alt="Direktorat Kemahasiswaan" style="height: 48px; width: auto;">
            </a>
            <p style="font-size: 13px; color: var(--ink-500); margin-top: 8px;">
                Sistem Dokumentasi Kegiatan Mahasiswa
            </p>
        </div>

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="alert-success mb-4 flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert-error mb-4 flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                {{ session('error') }}
            </div>
        @endif

        {{-- Form content --}}
        @yield('content')
    </div>

    {{-- ===== KANAN: Panel Dekoratif (60%) ===== --}}
    <div class="hidden md:flex flex-col items-center justify-center flex-1 relative overflow-hidden"
         style="background-image: url('{{ asset('img/bg_ditmawa.webp') }}'); background-size: cover; background-position: center;">

        {{-- Overlay merah-gelap di atas foto agar teks putih terbaca --}}
        <div class="absolute inset-0"
             style="background: linear-gradient(135deg, rgba(224,58,62,0.82) 0%, rgba(146,43,33,0.80) 45%, rgba(26,26,26,0.70) 100%);"></div>

        {{-- Konten teks tengah --}}
        <div class="relative z-10 text-center text-white px-12">
            <div class="mb-8">
                <img src="{{ asset('img/logo-telkom.png') }}" alt="Telkom University" style="height: 42px; width: auto; display: block; margin: 0 auto;">
            </div>
            <h1 style="font-family: 'Poppins', 'Segoe UI', sans-serif; font-size: 36px; font-weight: 600; line-height: 1.2; margin-bottom: 16px; color: #fff;">
                Direktorat<br>Kemahasiswaan,<br>Karier, dan Alumni
            </h1>
            <p style="font-size: 15px; opacity: 0.85; line-height: 1.6; margin-bottom: 8px;">
                Universitas Telkom
            </p>
            <p style="font-size: 13px; opacity: 0.65; max-width: 320px; margin: 0 auto; line-height: 1.6; text-align: center;">
                Platform terpadu untuk pembuatan dokumen administratif kegiatan mahasiswa
            </p>
        </div>

        {{-- Ornamen dekoratif bawah --}}
        <div class="absolute bottom-0 left-0 right-0 h-32 opacity-10"
             style="background: linear-gradient(to top, rgba(255,255,255,0.3), transparent);"></div>
    </div>

</body>
</html>

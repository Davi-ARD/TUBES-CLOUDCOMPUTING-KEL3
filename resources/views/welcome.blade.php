<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Direktorat Kemahasiswaan, Karier, dan Alumni | Telkom University</title>
    <link rel="icon" type="image/webp" href="{{ asset('img/logo-telkom-iconweb.webp') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data style="margin: 0; padding: 0; background: #fff;">

    {{-- ===== NAVBAR ===== --}}
    <nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-4 sm:px-8 bg-white border-b border-gray-100"
         style="height: 64px; box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
        <a href="{{ route('home') }}" style="text-decoration:none;">
            <img src="{{ asset('img/logo-direktorat.png') }}" alt="Direktorat Kemahasiswaan" style="height: 36px; width: auto;">
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="btn-secondary" style="padding: 8px 20px;">
                Masuk
            </a>
            <a href="{{ route('register') }}" class="btn-primary" style="padding: 8px 20px;">
                Daftar
            </a>
        </div>
    </nav>

    {{-- ===== HERO SECTION ===== --}}
    <section style="min-height: 100vh; display: flex; align-items: center; padding-top: 64px;
                    position: relative; overflow: hidden;">

        {{-- Fallback gradient (tampil jika video gagal load) --}}
        <div style="position: absolute; inset: 0; background: linear-gradient(135deg, #1a1a1a 0%, #7B241C 50%, #1a1a1a 100%); z-index: 0;"></div>

        {{-- Video background --}}
        <video autoplay muted loop playsinline
               style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;">
            <source src="{{ asset('videos/bg.mp4') }}" type="video/mp4">
        </video>

        {{-- Overlay gelap di atas video --}}
        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.58); z-index: 2;"></div>

        {{-- Konten --}}
        <div class="relative z-10 max-w-3xl mx-auto px-5 sm:px-8 py-16 sm:py-24" style="z-index: 3; text-align: center;">

            <div class="mb-6">
                <img src="{{ asset('img/logo-telkom.png') }}" alt="Telkom University"
                     style="height: 56px; width: auto; display: block; margin: 0 auto;">
            </div>
            <h1 style="font-family: 'Poppins', 'Segoe UI', sans-serif; font-size: clamp(32px, 5vw, 52px);
                        font-weight: 600; color: #fff; line-height: 1.15; margin-bottom: 20px;">
                Direktorat<br>Kemahasiswaan,<br>Karier, dan Alumni
            </h1>
            <p style="font-size: 17px; color: rgba(255,255,255,0.85); line-height: 1.7; max-width: 540px; margin: 0 auto 36px;">
                Platform terpadu untuk pembuatan Proposal Kegiatan dan
                Laporan Pertanggungjawaban (LPJ) sesuai template resmi
                Direktorat Kemahasiswaan Telkom University.
            </p>
            <div class="flex items-center gap-3 flex-wrap" style="justify-content: center;">
                <a href="{{ route('register') }}"
                   style="display:inline-flex; align-items:center; gap:8px; background:#fff; color:var(--telkom-red);
                          padding:12px 28px; border-radius:9999px; font-size:15px; font-weight:700;
                          text-decoration:none; transition: all 0.2s ease-out; box-shadow: 0 4px 16px rgba(0,0,0,0.15);"
                   onmouseover="this.style.transform='scale(1.02)'; this.style.boxShadow='0 6px 20px rgba(0,0,0,0.2)';"
                   onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 16px rgba(0,0,0,0.15)';">
                    <i data-lucide="user-plus" style="width:18px; height:18px;"></i>
                    Mulai Sekarang
                </a>
                <a href="{{ route('login') }}"
                   style="display:inline-flex; align-items:center; gap:8px; background:transparent; color:#fff;
                          padding:12px 28px; border-radius:9999px; font-size:15px; font-weight:600;
                          text-decoration:none; border: 2px solid rgba(255,255,255,0.5); transition: all 0.2s ease-out;"
                   onmouseover="this.style.borderColor='rgba(255,255,255,0.9)'; this.style.background='rgba(255,255,255,0.1)';"
                   onmouseout="this.style.borderColor='rgba(255,255,255,0.5)'; this.style.background='transparent';">
                    Sudah punya akun? Masuk
                </a>
            </div>
        </div>
    </section>

</body>
</html>

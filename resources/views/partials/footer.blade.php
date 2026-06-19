{{-- ===== FOOTER (area konten utama, di bawah halaman) ===== --}}
<footer class="bg-[#E03A3E] text-white mt-auto">
    <div class="px-6 md:px-10 py-10">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-10">

            {{-- Kolom kiri: identitas direktorat --}}
            <div class="md:col-span-4 space-y-4">
                <div style="display:inline-flex; align-items:center; gap:12px;">
                    <img src="{{ asset('img/logo-telkom.png') }}" alt="Telkom University"
                         class="w-auto" style="height: 40px;">
                    <span class="block w-px h-7" style="background:rgba(255,255,255,0.4);"></span>
                    <img src="{{ asset('img/logo-direktorat-new.png') }}" alt="Direktorat Kemahasiswaan"
                         class="w-auto" style="height: 30px;">
                </div>
                <p class="text-sm font-semibold leading-snug">
                    Direktorat Kemahasiswaan, Karier, dan Alumni
                </p>
                <p class="text-xs leading-relaxed text-white/85">
                    Gedung Pelampong Telkom University, Jl. Telekomunikasi, Terusan Buah Batu,
                    Indonesia 40257, Bandung, Indonesia
                </p>

                {{-- Sosial media (ikon brand polos, tanpa latar/border) --}}
                <div class="flex items-center gap-5 pt-2">
                    <a href="https://www.instagram.com/ditmawa_univtelkom/" target="_blank" rel="noopener noreferrer"
                       aria-label="Instagram" class="text-white/85 hover:text-white transition-colors">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                    </a>
                    <a href="https://www.tiktok.com/@ditmawa_univtelkom" target="_blank" rel="noopener noreferrer"
                       aria-label="TikTok" class="text-white/85 hover:text-white transition-colors">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                        </svg>
                    </a>
                    <a href="https://www.youtube.com/channel/UC1rBn2q8ZeR5FZlnsOwFtew" target="_blank" rel="noopener noreferrer"
                       aria-label="YouTube" class="text-white/85 hover:text-white transition-colors">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Kolom kanan: 3 sub-kolom tautan --}}
            <div class="md:col-span-8 grid grid-cols-1 sm:grid-cols-3 gap-8">

                {{-- Halaman --}}
                <div>
                    <h3 class="text-sm font-semibold mb-3 uppercase tracking-wide">Halaman</h3>
                    <ul class="space-y-2 text-sm text-white/85">
                        <li><a href="{{ route('profile.edit') }}" class="hover:text-white transition-colors">Profil</a></li>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Dashboard</a></li>
                        <li><a href="{{ route('proposal.create') }}" class="hover:text-white transition-colors">Generate Proposal</a></li>
                        <li><a href="{{ Route::has('lpj.create') ? route('lpj.create') : '#' }}"
                               class="hover:text-white transition-colors {{ Route::has('lpj.create') ? '' : 'opacity-50 cursor-not-allowed' }}">Generate LPJ</a></li>
                    </ul>
                </div>

                {{-- Tautan Cepat --}}
                <div>
                    <h3 class="text-sm font-semibold mb-3 uppercase tracking-wide">Tautan Cepat</h3>
                    <ul class="space-y-2 text-sm text-white/85">
                        <li>
                            <a href="https://wa.me/6281323323677" target="_blank" rel="noopener noreferrer"
                               class="hover:text-white transition-colors">Hotline Satgas PPKPT</a>
                        </li>
                        <li>
                            <a href="https://wa.me/6282130155601" target="_blank" rel="noopener noreferrer"
                               class="hover:text-white transition-colors">Konseling</a>
                        </li>
                        <li>
                            <a href="https://wa.me/6281214242600" target="_blank" rel="noopener noreferrer"
                               class="hover:text-white transition-colors">Beasiswa</a>
                        </li>
                        <li>
                            <a href="https://wa.me/6281321115302" target="_blank" rel="noopener noreferrer"
                               class="hover:text-white transition-colors">Pelaporan Prestasi</a>
                        </li>
                        <li>
                            <a href="https://studentaffairs.telkomuniversity.ac.id/links" target="_blank" rel="noopener noreferrer"
                               class="hover:text-white transition-colors">Links</a>
                        </li>                       
                    </ul>
                </div>

                {{-- Direktori --}}
                <div>
                    <h3 class="text-sm font-semibold mb-3 uppercase tracking-wide">Direktori</h3>
                    <ul class="space-y-2 text-sm text-white/85">
                        <li>
                            <a href="https://telkomuniversity.ac.id" target="_blank" rel="noopener noreferrer"
                               class="hover:text-white transition-colors">Website Telkom University</a>
                        </li>
                        <li>
                            <a href="https://igracias.telkomuniversity.ac.id/" target="_blank" rel="noopener noreferrer"
                               class="hover:text-white transition-colors">iGracias</a>
                        </li>
                        <li>
                            <a href="https://studentaffairs.telkomuniversity.ac.id/asrama/" target="_blank" rel="noopener noreferrer"
                               class="hover:text-white transition-colors">Asrama</a>
                        </li>
                        <li>
                            <a href="https://studentaffairs.telkomuniversity.ac.id/pkkmb/" target="_blank" rel="noopener noreferrer"
                               class="hover:text-white transition-colors">PKKMB</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Baris bawah: copyright + credit --}}
    <div class="border-t border-white/20">
        <div class="px-6 md:px-10 py-4 flex flex-col md:flex-row items-center justify-between gap-2 text-xs text-white/90">
            <p>&copy; {{ date('Y') }} Direktorat Kemahasiswaan, Karier dan Alumni</p>
            <p>
                Developed by
                <a href="https://www.instagram.com/cciunitel/" target="_blank" rel="noopener noreferrer"
                   class="text-white hover:opacity-80 transition-opacity">Central Computer Improvement</a>
            </p>
        </div>
    </div>
</footer>

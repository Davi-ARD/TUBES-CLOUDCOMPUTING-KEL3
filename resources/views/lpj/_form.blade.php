{{--
    Shared form partial for LPJ (create & edit).
    Expects:
      $mode           'create' | 'edit'
      $action         form action URL
      $formMethod     'POST' | 'PUT'
      $model          Lpj|null
      $seed           array|null  (seed state lpjForm)
      $danaKeluarSeed array|null  (seed divisions danaKeluarForm)
--}}
@php
    $mode           = $mode ?? 'create';
    $formMethod     = $formMethod ?? 'POST';
    $model          = $model ?? null;
    $isEdit         = $mode === 'edit';
    $danaKeluarSeed = $danaKeluarSeed ?? null;
@endphp

@push('styles')
<style>
.step-bar { display: flex; gap: 4px; margin-bottom: 32px; }
.step-dot {
    flex: 1; height: 6px; border-radius: 3px;
    background: var(--surface-muted); transition: background 0.3s;
}
.step-dot.done { background: var(--success); }
.step-dot.active { background: var(--telkom-red); animation: stepPulse 1.6s ease-in-out infinite; }
.step-label { font-size: 12px; color: var(--ink-500); margin-bottom: 20px; font-weight: 600; }

.repeater-table { width: 100%; border-collapse: collapse; }
.repeater-table th {
    background: var(--surface-alt); font-size: 12px; font-weight: 600;
    padding: 8px 10px; text-align: left; border-bottom: 2px solid var(--ink-300);
    color: var(--ink-700);
}
.repeater-table td { padding: 6px 4px; vertical-align: top; }
.repeater-table .form-input { font-size: 13px; padding: 6px 8px; }

.section-heading {
    font-size: 16px; font-weight: 600; color: var(--ink-800);
    padding-bottom: 10px; margin-bottom: 18px;
    border-bottom: 1px solid var(--surface-muted);
}
</style>
@endpush

<div x-data="lpjForm(@js($seed ?? null), @js($mode))" x-init="init()">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 style="font-family:'Poppins','Segoe UI',sans-serif; font-size:24px; font-weight:600; color:var(--ink-900); margin-bottom:4px;">
                {{ $isEdit ? 'Edit Laporan Pertanggungjawaban' : 'Buat Laporan Pertanggungjawaban' }}
            </h1>
            <p style="font-size:14px; color:var(--ink-500);">
                Langkah <span x-text="currentStep"></span> dari <span x-text="totalSteps"></span>:
                <span x-text="stepLabels[currentStep - 1]"></span>
            </p>
        </div>
        <a href="{{ $isEdit ? route('lpj.show', $model) : route('dashboard') }}" class="btn-secondary text-sm" @click="clearDraft()">
            <i data-lucide="x" class="w-4 h-4"></i> Batal
        </a>
    </div>

    {{-- Step progress bar --}}
    <div class="step-bar mb-2">
        <template x-for="i in totalSteps" :key="i">
            <div class="step-dot"
                 :class="{ 'done': i < currentStep, 'active': i === currentStep }"></div>
        </template>
    </div>
    <p class="step-label mb-6" x-text="stepLabels[currentStep - 1]"></p>

    <form action="{{ $action }}" method="POST" enctype="multipart/form-data" id="lpjForm" novalidate data-fv-manual="true">
        @csrf
        @if($formMethod === 'PUT')
            @method('PUT')
        @endif

        @if ($errors->any())
            <div class="alert-error mb-6">
                <div class="flex items-center gap-2" style="font-weight:600; margin-bottom:8px;">
                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                    Terdapat kesalahan pada formulir. Periksa kembali isian berikut.
                </div>
                <ul style="margin:0; padding-left:20px; list-style:disc;">
                    @foreach (array_unique($errors->all()) as $error)
                        <li style="font-size:13px;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ===== STEP 1: Identitas Kegiatan ===== --}}
        <div x-show="currentStep === 1" class="card">
            <h2 class="section-heading">Identitas Kegiatan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="form-label">Nama Kegiatan <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="nama_kegiatan" class="form-input" required
                           :value="data.nama_kegiatan"
                           @input="data.nama_kegiatan = $event.target.value">
                </div>
                <div>
                    <label class="form-label">Akronim / Singkatan <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="akronim" class="form-input" required
                           :value="data.akronim"
                           @input="data.akronim = $event.target.value">
                </div>
                <div>
                    <label class="form-label">Tema Kegiatan <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="tema_kegiatan" class="form-input" required
                           :value="data.tema_kegiatan"
                           @input="data.tema_kegiatan = $event.target.value">
                </div>
                <div>
                    <label class="form-label">Penyelenggara (Nama UKM/Organisasi) <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="penyelenggara" class="form-input"
                           :value="data.penyelenggara"
                           @input="data.penyelenggara = $event.target.value">
                </div>
                <div>
                    <label class="form-label">Afiliasi</label>
                    <input type="text" name="afiliasi" class="form-input"
                           :value="data.afiliasi"
                           @input="data.afiliasi = $event.target.value">
                </div>
                <div class="md:col-span-2" x-data="dateRangePicker(data)" @keydown.escape.window="open = false">
                    <label class="form-label">Tanggal Pelaksanaan <span style="color:var(--telkom-red)">*</span></label>
                    <div class="relative" @click.outside="open = false">
                        <button type="button" class="form-input dp-trigger" @click="toggle()">
                            <i data-lucide="calendar" class="w-4 h-4 shrink-0" style="color:var(--ink-500);"></i>
                            <span class="truncate" x-text="label()" :style="from ? 'color:var(--ink-700)' : 'color:var(--ink-300)'"></span>
                        </button>

                        {{-- Nilai yang disubmit (sama persis seperti sebelumnya: format Y-m-d) --}}
                        <input type="hidden" name="tanggal_mulai" :value="data.tanggal_mulai">
                        <input type="hidden" name="tanggal_selesai" :value="data.tanggal_selesai">

                        <div x-show="open" x-cloak class="dp-popover">
                            <div class="flex items-center gap-2" style="margin-bottom:10px;">
                                <button type="button" class="dp-nav-btn" @click="prevMonth(); mOpen=false; yOpen=false;" aria-label="Bulan sebelumnya">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </button>

                                {{-- Custom month dropdown --}}
                                <div style="position:relative; flex:1;" @click.outside="mOpen=false">
                                    <button type="button" class="dp-select-btn" @click="mOpen=!mOpen; yOpen=false;">
                                        <span x-text="months[viewMonth]"></span>
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; transition:transform 0.15s;" :style="mOpen ? 'transform:rotate(180deg)' : ''"><polyline points="6 9 12 15 18 9"/></svg>
                                    </button>
                                    <div x-show="mOpen" x-cloak class="dp-opts"
                                         x-effect="if(mOpen) $nextTick(() => { const s=$el.querySelector('.is-selected'); if(s) $el.scrollTop=s.offsetTop-$el.clientHeight/2+s.clientHeight/2; })">
                                        <template x-for="(mn, mi) in months" :key="mi">
                                            <button type="button" class="dp-opt" :class="{'is-selected': viewMonth===mi}" @click="viewMonth=mi; mOpen=false;" x-text="mn"></button>
                                        </template>
                                    </div>
                                </div>

                                {{-- Custom year dropdown --}}
                                <div style="position:relative; flex:0 0 82px;" @click.outside="yOpen=false">
                                    <button type="button" class="dp-select-btn" @click="yOpen=!yOpen; mOpen=false;">
                                        <span x-text="viewYear"></span>
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; transition:transform 0.15s;" :style="yOpen ? 'transform:rotate(180deg)' : ''"><polyline points="6 9 12 15 18 9"/></svg>
                                    </button>
                                    <div x-show="yOpen" x-cloak class="dp-opts"
                                         x-effect="if(yOpen) $nextTick(() => { const s=$el.querySelector('.is-selected'); if(s) $el.scrollTop=s.offsetTop-$el.clientHeight/2+s.clientHeight/2; })">
                                        <template x-for="y in years" :key="y">
                                            <button type="button" class="dp-opt" :class="{'is-selected': viewYear===y}" @click="viewYear=y; yOpen=false;" x-text="y"></button>
                                        </template>
                                    </div>
                                </div>

                                <button type="button" class="dp-nav-btn" @click="nextMonth(); mOpen=false; yOpen=false;" aria-label="Bulan berikutnya">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </button>
                            </div>

                            <div class="dp-grid" @click="mOpen=false; yOpen=false;">
                                <template x-for="d in dow" :key="d">
                                    <div class="dp-dow" x-text="d"></div>
                                </template>
                                <template x-for="(d, idx) in cells" :key="idx">
                                    <button type="button" class="dp-day"
                                            x-text="d || ''"
                                            :disabled="!d"
                                            :style="!d ? 'visibility:hidden' : ''"
                                            :class="{ 'is-range': d && isInRange(d) && !isEnd(d), 'is-end': d && isEnd(d), 'is-today': d && isToday(d) }"
                                            @click="d && select(d)"></button>
                                </template>
                            </div>

                            <div class="flex justify-between items-center" style="margin-top:12px; padding-top:12px; border-top:1px solid var(--surface-muted);">
                                <button type="button" class="btn-secondary" style="padding:6px 14px; font-size:13px;" @click="clear()" :disabled="!from">Hapus</button>
                                <button type="button" class="btn-primary" style="padding:6px 18px; font-size:13px;" @click="apply()" :disabled="!from">Terapkan</button>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs" style="color:var(--ink-500); margin-top:6px;">Pilih tanggal mulai lalu tanggal selesai kegiatan.</p>
                </div>
                @foreach(['waktu_mulai' => 'Waktu Mulai', 'waktu_selesai' => 'Waktu Selesai'] as $tkey => $tlabel)
                <div x-data="timePicker(data, '{{ $tkey }}')" @click.outside="open = false" @keydown.escape="open = false">
                    <label class="form-label">{{ $tlabel }} <span style="color:var(--telkom-red)">*</span></label>
                    <div class="relative">
                        <button type="button" class="form-input tp-trigger" @click="toggle()">
                            <i data-lucide="clock" class="w-4 h-4 shrink-0" style="color:var(--ink-500);"></i>
                            <span x-text="label()" :style="value ? 'color:var(--ink-700)' : 'color:var(--ink-300)'"></span>
                        </button>
                        <input type="hidden" name="{{ $tkey }}" :value="data.{{ $tkey }}">
                        <div x-show="open" x-cloak class="tp-popover">
                            <div class="tp-head"><span>Jam</span><span>Menit</span></div>
                            <div class="tp-cols">
                                <div class="tp-col">
                                    <template x-for="h in hours" :key="h">
                                        <button type="button" class="tp-cell" :class="{ 'is-active': isHour(h) }" @click="pickHour(h)" x-text="h"></button>
                                    </template>
                                </div>
                                <div class="tp-col">
                                    <template x-for="m in minutes" :key="m">
                                        <button type="button" class="tp-cell" :class="{ 'is-active': isMinute(m) }" @click="pickMinute(m)" x-text="m"></button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="md:col-span-2">
                    <label class="form-label">Tempat Kegiatan <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="tempat_kegiatan" class="form-input" required
                           :value="data.tempat_kegiatan"
                           @input="data.tempat_kegiatan = $event.target.value">
                </div>
                <div>
                    <label class="form-label">Kota <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="kota" class="form-input" required
                           :value="data.kota || 'BANDUNG'"
                           @input="data.kota = $event.target.value">
                </div>
                <div>
                    <label class="form-label">Tahun <span style="color:var(--telkom-red)">*</span></label>
                    <input type="number" name="tahun" class="form-input" required
                           :value="data.tahun || new Date().getFullYear()"
                           @input="data.tahun = $event.target.value">
                </div>
            </div>
        </div>

        {{-- ===== STEP 2: Cover & Logo ===== --}}
        <div x-show="currentStep === 2" class="card">
            <h2 class="section-heading">Cover & Logo Organisasi</h2>
            <p style="font-size:13px; color:var(--ink-500); margin-bottom:16px;">
                Logo akan ditampilkan di cover PDF di bawah logo Telkom University. Format: PNG/JPG, maks 2MB.@if($isEdit) Biarkan kosong untuk mempertahankan logo saat ini.@endif
            </p>
            <div>
                <label class="form-label">Logo Organisasi/UKM @if(!$isEdit)<span style="color:var(--telkom-red)">*</span>@endif</label>
                <input type="file" name="logo_organisasi" accept="image/png,image/jpeg,image/jpg" @if(!$isEdit) required @endif
                       class="form-input"
                       @change="previewLogo($event)">
                <template x-if="logoPreview">
                    <div class="mt-3">
                        <p class="form-label" x-text="logoIsExisting ? 'Logo saat ini:' : 'Preview:'"></p>
                        <img :src="logoPreview" alt="Preview Logo"
                             style="max-height:120px; object-fit:contain; border:1px solid var(--ink-300); border-radius:8px; padding:8px;">
                    </div>
                </template>
            </div>

            {{-- Logo UKM Kolaborasi (opsional) - item #4 --}}
            <div class="mt-8 pt-6" style="border-top:1px solid var(--ink-300);"
                 x-data="{
                     previews: [],
                     handle(e) {
                         this.previews.forEach(p => URL.revokeObjectURL(p.url));
                         this.previews = Array.from(e.target.files).map(f => ({ name: f.name, url: URL.createObjectURL(f) }));
                     }
                 }">
                <label class="form-label">Logo UKM Kolaborasi <span style="font-size:12px; font-weight:400; color:var(--ink-500);">(opsional)</span></label>
                <p style="font-size:13px; color:var(--ink-500); margin:4px 0 12px;">
                    Jika kegiatan ini berkolaborasi dengan UKM/organisasi lain, upload logo mereka di sini (boleh lebih dari satu).
                    Semua logo akan tampil di cover. Kosongkan jika tidak ada kolaborasi. Format: PNG/JPG, maks 2MB per logo.
                </p>

                @if($isEdit && $model->collabLogos->count())
                    <div class="mb-4" x-data="{ deleted: [] }">
                        <p class="form-label">Logo Kolaborasi Saat Ini</p>
                        <div class="flex flex-wrap gap-3 mt-2">
                            @foreach($model->collabLogos as $logo)
                                <div style="position:relative; width:120px; border:1px solid var(--ink-300); border-radius:10px; padding:10px; background:var(--surface);">
                                    <div x-show="!deleted.includes({{ $logo->id }})">
                                        <img src="{{ Storage::url($logo->file_path) }}" style="width:100%; height:70px; object-fit:contain;">
                                        <button type="button" title="Hapus logo ini" @click="deleted.push({{ $logo->id }})"
                                                style="position:absolute; top:6px; right:6px; display:inline-flex; align-items:center; justify-content:center; width:24px; height:24px; border:none; border-radius:6px; background:rgba(255,255,255,0.92); color:var(--danger); box-shadow:var(--shadow-card); cursor:pointer;">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </div>
                                    <div x-show="deleted.includes({{ $logo->id }})" x-cloak style="text-align:center; padding:18px 0;">
                                        <span style="font-size:11px; font-weight:600; color:var(--ink-500);">Akan dihapus</span><br>
                                        <button type="button" @click="deleted = deleted.filter(i => i !== {{ $logo->id }})"
                                                style="font-size:12px; font-weight:600; color:var(--telkom-red); background:none; border:none; cursor:pointer;">Urungkan</button>
                                    </div>
                                    <input type="hidden" name="hapus_lampiran[]" value="{{ $logo->id }}" :disabled="!deleted.includes({{ $logo->id }})">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <input type="file" name="logo_kolaborasi[]" multiple accept="image/png,image/jpeg,image/jpg"
                       class="form-input" @change="handle($event)">
                <div class="flex flex-wrap gap-3 mt-3">
                    <template x-for="(p, idx) in previews" :key="idx">
                        <div style="width:120px; border:1px solid var(--ink-300); border-radius:10px; padding:10px;">
                            <img :src="p.url" style="width:100%; height:70px; object-fit:contain;">
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- ===== STEP 3: A. Latar Belakang ===== --}}
        <div x-show="currentStep === 3" class="card">
            <h2 class="section-heading">Latar Belakang</h2>
            <div>
                <label class="form-label">Latar Belakang <span style="color:var(--telkom-red)">*</span></label>
                <p style="font-size:12px; color:var(--ink-500); margin-bottom:8px;">Ceritakan latar belakang dan alasan pelaksanaan kegiatan ini (bisa beberapa paragraf).</p>
                <textarea name="latar_belakang" rows="14" required class="form-input" data-rich
                          x-model="data.latar_belakang"></textarea>
            </div>
        </div>

        {{-- ===== STEP 4: B-G ===== --}}
        <div x-show="currentStep === 4" class="card">
            <h2 class="section-heading">Nama, Tema, Tujuan, Sasaran, Waktu &amp; Tempat, Bentuk Kegiatan</h2>
            <div class="space-y-5">
                <div style="background:var(--surface-alt); padding:12px; border-radius:8px; font-size:13px; color:var(--ink-600);">
                    <strong>Nama Kegiatan</strong>, diambil otomatis: "<span x-text="data.nama_kegiatan || '(belum diisi)'"></span>"
                </div>
                <div style="background:var(--surface-alt); padding:12px; border-radius:8px; font-size:13px; color:var(--ink-600);">
                    <strong>Tema Kegiatan</strong>, diambil otomatis: "<span x-text="data.tema_kegiatan || '(belum diisi)'"></span>"
                </div>

                <div>
                    <label class="form-label">Tujuan Kegiatan <span style="color:var(--telkom-red)">*</span></label>
                    <p style="font-size:12px; color:var(--ink-500); margin-bottom:8px;">Tambahkan tujuan satu per satu. Akan ditampilkan sebagai daftar bernomor di PDF.</p>
                    <template x-for="(tujuan, idx) in data.tujuan_kegiatan" :key="idx">
                        <div class="flex items-center gap-2 mb-2">
                            <span style="font-size:13px; color:var(--ink-500); min-width:20px;" x-text="(idx+1)+'.'"></span>
                            <input type="text" :name="`tujuan_kegiatan[${idx}]`"
                                   class="form-input flex-1" required
                                   x-model="data.tujuan_kegiatan[idx]">
                            <button type="button" @click="data.tujuan_kegiatan.splice(idx, 1)"
                                    x-show="data.tujuan_kegiatan.length > 1"
                                    class="p-2 rounded-lg hover:bg-red-50 transition-all" style="color:var(--danger);">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="data.tujuan_kegiatan.push('')"
                            class="btn-secondary text-sm mt-1">
                        <i data-lucide="plus" class="w-4 h-4"></i> Tambah Tujuan
                    </button>
                </div>

                <div>
                    <label class="form-label">Sasaran Kegiatan <span style="color:var(--telkom-red)">*</span></label>
                    <textarea name="sasaran_kegiatan" rows="3" required class="form-input" data-rich
                              x-model="data.sasaran_kegiatan"></textarea>
                </div>

                <div style="background:var(--surface-alt); padding:12px; border-radius:8px; font-size:13px; color:var(--ink-600);">
                    <strong>Waktu dan Tempat</strong>, diambil otomatis dari Identitas Kegiatan.
                </div>

                <div>
                    <label class="form-label">Bentuk Kegiatan <span style="color:var(--telkom-red)">*</span></label>
                    <textarea name="bentuk_kegiatan" rows="5" required class="form-input" data-rich
                              x-model="data.bentuk_kegiatan"></textarea>
                </div>
            </div>
        </div>

        {{-- ===== STEP 5: H. Deskripsi Pelaksanaan ===== --}}
        <div x-show="currentStep === 5" class="card">
            <h2 class="section-heading">Deskripsi Pelaksanaan Kegiatan</h2>
            <div>
                <label class="form-label">Deskripsi Pelaksanaan <span style="color:var(--telkom-red)">*</span></label>
                <p style="font-size:12px; color:var(--ink-500); margin-bottom:8px;">Ceritakan bagaimana kegiatan berjalan secara aktual, dari persiapan hingga penutupan.</p>
                <textarea name="deskripsi_pelaksanaan" rows="14" required class="form-input" data-rich
                          x-model="data.deskripsi_pelaksanaan"></textarea>
            </div>
        </div>

        {{-- ===== STEP 6: I. Rundown ===== --}}
        <div x-show="currentStep === 6" class="card">
            <h2 class="section-heading">Susunan Acara (Rundown)</h2>
            <div style="overflow:visible;">
                <table class="repeater-table">
                    <thead>
                        <tr>
                            <th style="width:220px;">Pukul <span style="color:var(--telkom-red)">*</span></th>
                            <th style="width:110px;">Durasi</th>
                            <th>Detail Kegiatan <span style="color:var(--telkom-red)">*</span></th>
                            <th style="width:140px;">PIC <span style="color:var(--telkom-red)">*</span></th>
                            <th style="width:36px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, idx) in data.rundowns" :key="idx">
                            <tr>
                                <td style="overflow:visible;">
                                    <div style="display:flex; align-items:center; gap:4px;">
                                        {{-- Waktu Mulai --}}
                                        <div class="relative" style="flex:1; min-width:0;"
                                             @click.outside="row._tpMulaiOpen = false">
                                            <button type="button" class="form-input tp-trigger repeater-table" style="width:100%;"
                                                    @click="row._tpMulaiOpen = !row._tpMulaiOpen; row._tpSelesaiOpen = false">
                                                <i data-lucide="clock" class="w-3 h-3 shrink-0" style="color:var(--ink-500);"></i>
                                                <span :style="row.waktu_mulai ? 'color:var(--ink-700); font-size:12px;' : 'color:var(--ink-300); font-size:12px;'"
                                                      x-text="row.waktu_mulai || 'Pilih'"></span>
                                            </button>
                                            <input type="hidden" :name="`rundowns[${idx}][waktu_mulai]`" :value="row.waktu_mulai">
                                            <div x-show="row._tpMulaiOpen" x-cloak class="tp-popover">
                                                <div class="tp-head"><span>Jam</span><span>Menit</span></div>
                                                <div class="tp-cols">
                                                    <div class="tp-col">
                                                        <template x-for="h in tpHours" :key="h">
                                                            <button type="button" class="tp-cell"
                                                                    :class="{'is-active': (row.waktu_mulai||'').split(':')[0] === h}"
                                                                    @mousedown.prevent="pickRdTime(idx, 'mulai', 'h', h)"
                                                                    x-text="h"></button>
                                                        </template>
                                                    </div>
                                                    <div class="tp-col">
                                                        <template x-for="m in tpMinutes" :key="m">
                                                            <button type="button" class="tp-cell"
                                                                    :class="{'is-active': (row.waktu_mulai||'').split(':')[1] === m}"
                                                                    @mousedown.prevent="pickRdTime(idx, 'mulai', 'm', m)"
                                                                    x-text="m"></button>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span style="color:var(--ink-300); font-weight:600; flex-shrink:0;">-</span>
                                        {{-- Waktu Selesai --}}
                                        <div class="relative" style="flex:1; min-width:0;"
                                             @click.outside="row._tpSelesaiOpen = false">
                                            <button type="button" class="form-input tp-trigger repeater-table" style="width:100%;"
                                                    @click="row._tpSelesaiOpen = !row._tpSelesaiOpen; row._tpMulaiOpen = false">
                                                <i data-lucide="clock" class="w-3 h-3 shrink-0" style="color:var(--ink-500);"></i>
                                                <span :style="row.waktu_selesai ? 'color:var(--ink-700); font-size:12px;' : 'color:var(--ink-300); font-size:12px;'"
                                                      x-text="row.waktu_selesai || 'Pilih'"></span>
                                            </button>
                                            <input type="hidden" :name="`rundowns[${idx}][waktu_selesai]`" :value="row.waktu_selesai">
                                            <div x-show="row._tpSelesaiOpen" x-cloak class="tp-popover">
                                                <div class="tp-head"><span>Jam</span><span>Menit</span></div>
                                                <div class="tp-cols">
                                                    <div class="tp-col">
                                                        <template x-for="h in tpHours" :key="h">
                                                            <button type="button" class="tp-cell"
                                                                    :class="{'is-active': (row.waktu_selesai||'').split(':')[0] === h}"
                                                                    @mousedown.prevent="pickRdTime(idx, 'selesai', 'h', h)"
                                                                    x-text="h"></button>
                                                        </template>
                                                    </div>
                                                    <div class="tp-col">
                                                        <template x-for="m in tpMinutes" :key="m">
                                                            <button type="button" class="tp-cell"
                                                                    :class="{'is-active': (row.waktu_selesai||'').split(':')[1] === m}"
                                                                    @mousedown.prevent="pickRdTime(idx, 'selesai', 'm', m)"
                                                                    x-text="m"></button>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <input type="text"
                                           :name="`rundowns[${idx}][durasi]`"
                                           class="form-input repeater-table"
                                           x-model="row.durasi"
                                           readonly
                                           style="background:var(--surface-muted); color:var(--ink-500); cursor:not-allowed;">
                                </td>
                                <td><input type="text" :name="`rundowns[${idx}][detail_kegiatan]`" class="form-input repeater-table" x-model="row.detail_kegiatan" required></td>
                                <td><input type="text" :name="`rundowns[${idx}][pic]`" class="form-input repeater-table" x-model="row.pic" required></td>
                                <td>
                                    <button type="button" @click="data.rundowns.splice(idx,1)"
                                            x-show="data.rundowns.length > 1"
                                            class="p-1 hover:bg-red-50 rounded" style="color:var(--danger);">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <button type="button"
                    @click="data.rundowns.push({waktu_mulai:'',waktu_selesai:'',durasi:'',detail_kegiatan:'',pic:'',_tpMulaiOpen:false,_tpSelesaiOpen:false})"
                    class="btn-secondary text-sm mt-3">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Baris
            </button>
        </div>

        {{-- ===== STEP 7: J. Analisis Risiko ===== --}}
        <div x-show="currentStep === 7" class="card">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                <h2 class="section-heading" style="margin-bottom:0;">Analisis Risiko</h2>
                <button type="button" @click="showRiskGuide = true"
                        class="p-1 rounded hover:bg-gray-100"
                        style="color:var(--ink-500); flex-shrink:0; background:transparent; border:none; cursor:pointer;"
                        title="Panduan pengisian skala risiko">
                    <i data-lucide="info" class="w-4 h-4"></i>
                </button>
            </div>
            <div style="overflow-x:auto;">
                <table class="repeater-table">
                    <thead>
                        <tr>
                            <th>Uraian Kegiatan <span style="color:var(--telkom-red)">*</span></th>
                            <th style="width:90px;">Identifikasi Bahaya <span style="color:var(--telkom-red)">*</span></th>
                            <th style="width:80px;">Peluang/<br>kemungkinan <span style="color:var(--telkom-red)">*</span></th>
                            <th style="width:170px;">Akibat/<br>keparahan <span style="color:var(--telkom-red)">*</span></th>
                            <th style="width:64px;">Tingkat Risiko</th>
                            <th>Pengendalian <span style="color:var(--telkom-red)">*</span></th>
                            <th style="width:120px;">Penanggung Jawab <span style="color:var(--telkom-red)">*</span></th>
                            <th style="width:36px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, idx) in data.risks" :key="idx">
                            <tr>
                                <td><input type="text" :name="`risks[${idx}][uraian_kegiatan]`" class="form-input repeater-table" x-model="row.uraian_kegiatan" required></td>
                                <td>
                                    <select :name="`risks[${idx}][identifikasi_bahaya]`" class="form-input repeater-table ui-select" x-model="row.identifikasi_bahaya" required style="width:100%;">
                                        <option value="">Pilih</option>
                                        <option value="1/5">1/5</option>
                                        <option value="2/5">2/5</option>
                                        <option value="3/5">3/5</option>
                                        <option value="4/5">4/5</option>
                                        <option value="5/5">5/5</option>
                                    </select>
                                </td>
                                <td>
                                    <select :name="`risks[${idx}][peluang]`" class="form-input repeater-table ui-select" x-model="row.peluang" required style="width:100%;">
                                        <option value="">Pilih</option>
                                        <option value="1/5">1/5</option>
                                        <option value="2/5">2/5</option>
                                        <option value="3/5">3/5</option>
                                        <option value="4/5">4/5</option>
                                        <option value="5/5">5/5</option>
                                    </select>
                                </td>
                                <td><input type="text" :name="`risks[${idx}][akibat]`" class="form-input repeater-table" x-model="row.akibat" required></td>
                                <td style="text-align:center; vertical-align:middle;">
                                    <span x-text="computeTingkatRisiko(row.peluang, row.identifikasi_bahaya)" style="font-weight:600; font-size:13px; color:var(--ink-700);"></span>
                                    <input type="hidden" :name="`risks[${idx}][tingkat_risiko]`" :value="computeTingkatRisiko(row.peluang, row.identifikasi_bahaya)">
                                </td>
                                <td><input type="text" :name="`risks[${idx}][pengendalian_risiko]`" class="form-input repeater-table" x-model="row.pengendalian_risiko" required></td>
                                <td><input type="text" :name="`risks[${idx}][penanggung_jawab]`" class="form-input repeater-table" x-model="row.penanggung_jawab" required></td>
                                <td>
                                    <button type="button" @click="data.risks.splice(idx,1)"
                                            x-show="data.risks.length > 1"
                                            class="p-1 hover:bg-red-50 rounded" style="color:var(--danger);">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <button type="button"
                    @click="data.risks.push({uraian_kegiatan:'',identifikasi_bahaya:'',peluang:'',akibat:'',tingkat_risiko:'',pengendalian_risiko:'',penanggung_jawab:''})"
                    class="btn-secondary text-sm mt-3">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Baris
            </button>
        </div>

        {{-- ===== STEP 8: K. Monitoring dan Evaluasi ===== --}}
        <div x-show="currentStep === 8" class="card">
            <h2 class="section-heading">Monitoring dan Evaluasi</h2>
            <p style="font-size:12px; color:var(--ink-500); margin-bottom:14px;">
                Kelompokkan kegiatan monitoring per tanggal/fase. Satu tanggal/fase dapat memiliki
                beberapa detail kegiatan dengan PIC dan keterangan yang berbeda-beda.
            </p>

            <template x-for="(group, gIdx) in data.monitoring_groups" :key="gIdx">
                <div style="border:1px solid var(--ink-300); border-radius:8px; padding:16px; margin-bottom:16px; position:relative;">
                    <button type="button" x-show="data.monitoring_groups.length > 1"
                            @click="hapusGroup(gIdx)" title="Hapus tanggal/fase ini"
                            style="position:absolute; top:10px; right:10px; display:inline-flex; align-items:center; justify-content:center; width:30px; height:30px; border:1px solid var(--telkom-red); border-radius:6px; background:none; color:var(--telkom-red); cursor:pointer; font-size:16px; line-height:1;">
                        &times;
                    </button>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:14px; padding-right:44px;">
                        <div>
                            <label class="form-label">Tanggal <span style="color:var(--telkom-red)">*</span></label>
                            <input type="date" :name="`monitoring_groups[${gIdx}][tanggal]`"
                                   class="form-input" x-model="group.tanggal" required>
                        </div>
                        <div>
                            <label class="form-label">Fase <span style="color:var(--telkom-red)">*</span></label>
                            <input type="text" :name="`monitoring_groups[${gIdx}][fase]`"
                                   class="form-input" placeholder="Contoh: Pra-Acara" x-model="group.fase" required
                                   data-label="Fase">
                        </div>
                    </div>

                    <div style="overflow-x:auto;">
                        <table class="repeater-table">
                            <thead>
                                <tr>
                                    <th>Detail Kegiatan <span style="color:var(--telkom-red)">*</span></th>
                                    <th style="width:130px;">PIC <span style="color:var(--telkom-red)">*</span></th>
                                    <th style="width:170px;">Keterangan <span style="color:var(--telkom-red)">*</span></th>
                                    <th style="width:36px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, iIdx) in group.items" :key="iIdx">
                                    <tr>
                                        <td><input type="text" :name="`monitoring_groups[${gIdx}][items][${iIdx}][detail_kegiatan]`" class="form-input repeater-table" x-model="item.detail_kegiatan" required></td>
                                        <td><input type="text" :name="`monitoring_groups[${gIdx}][items][${iIdx}][pic]`" class="form-input repeater-table" x-model="item.pic" required></td>
                                        <td><input type="text" :name="`monitoring_groups[${gIdx}][items][${iIdx}][keterangan]`" class="form-input repeater-table" x-model="item.keterangan" required></td>
                                        <td>
                                            <button type="button" x-show="group.items.length > 1"
                                                    @click="hapusItem(gIdx, iIdx)" title="Hapus detail kegiatan"
                                                    style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border:1px solid var(--telkom-red); border-radius:6px; background:none; color:var(--telkom-red); cursor:pointer; font-size:16px; line-height:1;">
                                                &times;
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <button type="button" @click="tambahItem(gIdx)"
                            style="margin-top:12px; display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border:1px solid var(--ink-300); border-radius:6px; background:none; color:var(--ink-700); font-size:13px; cursor:pointer;">
                        <i data-lucide="plus" style="width:15px; height:15px;"></i> Tambah Detail Kegiatan
                    </button>
                </div>
            </template>

            <button type="button" @click="tambahGroup()"
                    style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border:1px solid var(--ink-300); border-radius:6px; background:none; color:var(--ink-700); font-size:14px; cursor:pointer;">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Tanggal/Fase
            </button>
        </div>

        {{-- ===== STEP 9: L. Simpulan dan Rekomendasi ===== --}}
        <div x-show="currentStep === 9" class="card">
            <h2 class="section-heading">Simpulan dan Rekomendasi</h2>
            <div>
                <label class="form-label">Simpulan dan Rekomendasi <span style="color:var(--telkom-red)">*</span></label>
                <p style="font-size:12px; color:var(--ink-500); margin-bottom:8px;">Tuliskan kesimpulan dari pelaksanaan kegiatan dan rekomendasi untuk kegiatan serupa di masa mendatang.</p>
                <textarea name="simpulan_rekomendasi" rows="10" required class="form-input" data-rich
                          x-model="data.simpulan_rekomendasi"></textarea>
            </div>
        </div>

        {{-- ===== STEP 10: M. Struktur Kepanitiaan ===== --}}
        <div x-show="currentStep === 10" class="card">
            <h2 class="section-heading">Struktur Kepanitiaan</h2>
            <p class="text-xs text-gray-400 mt-1 sm:hidden">Geser ke kanan untuk melihat semua kolom</p>
            <div class="overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0">
                <div class="min-w-[640px] sm:min-w-0">
                <table class="repeater-table">
                    <thead>
                        <tr>
                            <th style="width:120px;">Jabatan <span style="color:var(--telkom-red)">*</span></th>
                            <th>Nama <span style="color:var(--telkom-red)">*</span></th>
                            <th style="width:100px;">NIM <span style="color:var(--telkom-red)">*</span></th>
                            <th>Jurusan <span style="color:var(--telkom-red)">*</span></th>
                            <th style="width:80px;">Angkatan <span style="color:var(--telkom-red)">*</span></th>
                            <th>Fakultas <span style="color:var(--telkom-red)">*</span></th>
                            <th style="width:36px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, idx) in data.committees" :key="idx">
                            <tr>
                                <td><input type="text" :name="`committees[${idx}][jabatan]`" class="form-input repeater-table" x-model="row.jabatan" required></td>
                                <td><input type="text" :name="`committees[${idx}][nama]`" class="form-input repeater-table" x-model="row.nama" required></td>
                                <td><input type="text" :name="`committees[${idx}][nim]`" class="form-input repeater-table" x-model="row.nim" required></td>
                                <td><input type="text" :name="`committees[${idx}][jurusan]`" class="form-input repeater-table" x-model="row.jurusan" required></td>
                                <td><input type="text" :name="`committees[${idx}][angkatan]`" class="form-input repeater-table" x-model="row.angkatan" required></td>
                                <td><input type="text" :name="`committees[${idx}][fakultas]`" class="form-input repeater-table" x-model="row.fakultas" required></td>
                                <td>
                                    <button type="button" @click="data.committees.splice(idx,1)"
                                            x-show="data.committees.length > 1"
                                            class="p-1 hover:bg-red-50 rounded" style="color:var(--danger);">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                </div>
            </div>
            <button type="button"
                    @click="data.committees.push({jabatan:'',nama:'',nim:'',jurusan:'',angkatan:'',fakultas:''})"
                    class="btn-secondary text-sm mt-3">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Anggota
            </button>
        </div>

        {{-- ===== STEP 11: N. Realisasi Anggaran ===== --}}
        <div x-show="currentStep === 11" class="card">
            <h2 class="section-heading">Realisasi Anggaran Biaya</h2>

            {{-- Dana Masuk --}}
            <h3 style="font-size:14px; font-weight:700; color:var(--ink-900); margin-bottom:10px;">Dana Masuk</h3>
            <table class="repeater-table mb-4">
                <thead>
                    <tr>
                        <th style="width:42px;">No</th>
                        <th>Sumber Dana <span style="color:var(--telkom-red)">*</span></th>
                        <th style="width:140px;">Target (Rp) <span style="color:var(--telkom-red)">*</span></th>
                        <th style="width:140px;">Jumlah (Rp) <span style="color:var(--telkom-red)">*</span></th>
                        <th style="width:140px;">Total (Rp)</th>
                        <th style="width:36px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(row, idx) in data.dana_masuk" :key="idx">
                        <tr>
                            <td style="text-align:center; color:var(--ink-500);" x-text="idx + 1"></td>
                            <td><input type="text" :name="`dana_masuk[${idx}][sumber_dana]`" class="form-input repeater-table" x-model="row.sumber_dana" required></td>
                            <td><input type="number" :name="`dana_masuk[${idx}][target]`" class="form-input repeater-table" x-model="row.target" min="0" required></td>
                            <td><input type="number" :name="`dana_masuk[${idx}][jumlah_total]`" class="form-input repeater-table" x-model="row.jumlah_total" min="0" required></td>
                            <td style="text-align:right; white-space:nowrap; color:var(--ink-700);"
                                x-text="'Rp' + (parseFloat(row.jumlah_total) || 0).toLocaleString('id-ID')"></td>
                            <td>
                                <button type="button" @click="data.dana_masuk.splice(idx,1)"
                                        x-show="data.dana_masuk.length > 1"
                                        class="p-1 hover:bg-red-50 rounded" style="color:var(--danger);">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr>
                        <td colspan="4" style="text-align:right; font-weight:700; color:var(--ink-900);">Total Keseluruhan</td>
                        <td style="text-align:right; font-weight:700; white-space:nowrap; color:var(--ink-900);"
                            x-text="'Rp' + data.dana_masuk.reduce((t, r) => t + (parseFloat(r.jumlah_total) || 0), 0).toLocaleString('id-ID')"></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <button type="button"
                    @click="data.dana_masuk.push({sumber_dana:'',target:0,jumlah_total:0})"
                    class="btn-secondary text-sm mb-6">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Dana Masuk
            </button>

            {{-- Dana Keluar: 3-Level Hierarchy --}}
            <div x-data="danaKeluarForm(@js($danaKeluarSeed ?? null))" class="space-y-4 mt-2">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
                    <h3 style="font-size:14px; font-weight:700; color:var(--ink-900);">Dana Keluar</h3>
                    <button type="button" @click="tambahDivisi()"
                            style="display:inline-flex; align-items:center; gap:6px; padding:6px 14px; background:var(--telkom-red); color:#fff; border:none; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer;">
                        <i data-lucide="plus" style="width:12px; height:12px;"></i>
                        Tambah Divisi
                    </button>
                </div>

                <template x-for="(divisi, dIdx) in divisions" :key="'d-' + dIdx">
                    <div style="border:1px solid var(--ink-300); border-radius:10px; overflow:hidden;">

                        <div style="display:flex; align-items:center; gap:8px; background:var(--surface-alt); padding:10px 14px; border-bottom:1px solid var(--ink-300);">
                            <span style="font-size:11px; font-weight:700; color:var(--ink-500); text-transform:uppercase; letter-spacing:0.5px; white-space:nowrap;">Divisi</span>
                            <input type="text" x-model="divisi.nama_divisi"
                                   style="flex:1; border:1px solid var(--ink-300); border-radius:6px; padding:6px 10px; font-size:13px; outline:none;">
                            <button type="button" @click="hapusDivisi(dIdx)"
                                    style="background:none; border:none; cursor:pointer; color:var(--danger); padding:2px 4px; font-size:18px; line-height:1;">&times;</button>
                        </div>

                        <div style="padding:12px; display:flex; flex-direction:column; gap:12px;">

                            <template x-for="(kategori, kIdx) in divisi.categories" :key="'k-' + dIdx + '-' + kIdx">
                                <div style="border:1px solid var(--ink-300); border-radius:8px; overflow:hidden;">

                                    <div style="display:flex; align-items:center; gap:8px; background:#fdf2f2; padding:8px 12px; border-bottom:1px solid var(--ink-300);">
                                        <span style="font-size:11px; font-weight:700; color:var(--telkom-red); background:#fff; border:1px solid #f5b5b7; border-radius:4px; padding:1px 8px; white-space:nowrap;">Kategori</span>
                                        <input type="text" x-model="kategori.nama_kategori"
                                               style="flex:1; border:1px solid var(--ink-300); border-radius:6px; padding:5px 10px; font-size:13px; outline:none; background:#fff;">
                                        <button type="button" @click="hapusKategori(dIdx, kIdx)"
                                                style="background:none; border:none; cursor:pointer; color:var(--ink-300); padding:2px 4px; font-size:18px; line-height:1;">&times;</button>
                                    </div>

                                    <div style="padding:10px 12px;">
                                        <div style="display:grid; grid-template-columns:3fr 80px 80px 130px 24px; gap:6px; padding:0 2px; margin-bottom:4px;">
                                            <div style="font-size:11px; color:var(--ink-500); font-weight:600;">Rincian Kebutuhan</div>
                                            <div style="font-size:11px; color:var(--ink-500); font-weight:600;">Jumlah</div>
                                            <div style="font-size:11px; color:var(--ink-500); font-weight:600;">Satuan</div>
                                            <div style="font-size:11px; color:var(--ink-500); font-weight:600;">Harga Satuan (Rp)</div>
                                            <div></div>
                                        </div>

                                        <template x-for="(sub, sIdx) in kategori.subitems" :key="'s-' + dIdx + '-' + kIdx + '-' + sIdx">
                                            <div style="margin-bottom:4px;">
                                                <div style="display:grid; grid-template-columns:3fr 80px 80px 130px 24px; gap:6px; align-items:center;">
                                                    <input type="text" x-model="sub.rincian_kebutuhan"
                                                           style="border:1px solid var(--ink-300); border-radius:6px; padding:5px 8px; font-size:12px; width:100%; box-sizing:border-box; outline:none;">
                                                    <input type="number" x-model="sub.jumlah" min="0"
                                                           @input="hitungTotal()"
                                                           style="border:1px solid var(--ink-300); border-radius:6px; padding:5px 8px; font-size:12px; width:100%; box-sizing:border-box; outline:none;">
                                                    <input type="text" x-model="sub.satuan"
                                                           style="border:1px solid var(--ink-300); border-radius:6px; padding:5px 8px; font-size:12px; width:100%; box-sizing:border-box; outline:none;">
                                                    <input type="number" x-model="sub.harga_satuan" min="0"
                                                           @input="hitungTotal()"
                                                           style="border:1px solid var(--ink-300); border-radius:6px; padding:5px 8px; font-size:12px; width:100%; box-sizing:border-box; outline:none;">
                                                    <button type="button" @click="hapusSubitem(dIdx, kIdx, sIdx)"
                                                            x-show="kategori.subitems.length > 1"
                                                            style="background:none; border:none; cursor:pointer; color:var(--ink-300); font-size:16px; line-height:1; padding:0;">&times;</button>
                                                </div>
                                                <div style="font-size:11px; color:var(--ink-500); margin-top:2px; padding-left:2px;"
                                                     x-show="sub.jumlah > 0 && sub.harga_satuan > 0"
                                                     x-text="'= Rp ' + formatRupiah(sub.jumlah * sub.harga_satuan)">
                                                </div>
                                            </div>
                                        </template>

                                        <button type="button" @click="tambahSubitem(dIdx, kIdx)"
                                                style="margin-top:6px; background:none; border:none; cursor:pointer; color:var(--telkom-red); font-size:12px; font-weight:600; display:inline-flex; align-items:center; gap:4px; padding:0;">
                                            <i data-lucide="plus" style="width:11px; height:11px;"></i>
                                            Tambah Rincian
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <button type="button" @click="tambahKategori(dIdx)"
                                    style="width:100%; border:2px dashed var(--ink-300); border-radius:8px; padding:8px; font-size:12px; color:var(--ink-500); background:none; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px;">
                                <i data-lucide="plus" style="width:13px; height:13px;"></i>
                                Tambah Kategori di Divisi <span style="font-weight:700;" x-text="divisi.nama_divisi || 'ini'"></span>
                            </button>

                            <div style="text-align:right; font-size:12px; color:var(--ink-500); border-top:1px solid var(--ink-300); padding-top:8px;">
                                Total Divisi <span style="font-weight:600;" x-text="divisi.nama_divisi"></span>:
                                <span style="font-weight:700; color:var(--ink-900);" x-text="'Rp ' + formatRupiah(totalDivisi(dIdx))"></span>
                            </div>
                        </div>
                    </div>
                </template>

                <div style="text-align:right;">
                    <div style="display:inline-block; background:var(--surface-alt); border:1px solid var(--ink-300); border-radius:8px; padding:10px 20px; font-size:13px;">
                        <span style="color:var(--ink-600);">Total Dana Keluar:</span>
                        <span style="font-weight:700; color:var(--ink-900); font-size:15px; margin-left:8px;" x-text="'Rp ' + formatRupiah(grandTotal)"></span>
                    </div>
                </div>

                <input type="hidden" name="dana_keluar_json" :value="JSON.stringify(divisions)">
            </div>

            <p x-show="danaKeluarError" x-cloak id="danaKeluarError"
               class="field-error" role="alert" style="margin-top:8px;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>Lengkapi semua rincian Dana Keluar (nama divisi, kategori, rincian kebutuhan, jumlah, dan harga satuan) sebelum melanjutkan.</span>
            </p>
        </div>

        {{-- ===== STEP 12: O. Penutup & Lembar Pengesahan ===== --}}
        <div x-show="currentStep === 12" class="card">
            <h2 class="section-heading">Penutup &amp; Lembar Pengesahan</h2>

            <div class="mb-6">
                <label class="form-label">Penutup <span style="color:var(--telkom-red)">*</span></label>
                <textarea name="penutup" rows="5" required class="form-input" data-rich
                          x-model="data.penutup"></textarea>
            </div>

            <h3 class="font-semibold mb-4" style="font-size:15px; color:var(--ink-900);">Lembar Pengesahan: Data Penandatangan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-lg border" style="border-color:var(--ink-300);">
                    <p class="text-sm font-semibold mb-3" style="color:var(--ink-700);">Ketua UKM</p>
                    <label class="form-label">Nama <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="ketua_ukm_nama" class="form-input mb-2" required
                           x-model="data.ketua_ukm_nama">
                    <label class="form-label">NIM <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="ketua_ukm_nim" class="form-input" required
                           inputmode="numeric"
                           data-fv-digits-min="8" data-fv-digits-max="20"
                           data-fv-message="NIM harus berupa angka dan minimal 8 digit."
                           x-model="data.ketua_ukm_nim">
                </div>
                <div class="p-4 rounded-lg border" style="border-color:var(--ink-300);">
                    <p class="text-sm font-semibold mb-3" style="color:var(--ink-700);">Ketua Pelaksana</p>
                    <label class="form-label">Nama <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="ketua_pelaksana_nama" class="form-input mb-2" required
                           x-model="data.ketua_pelaksana_nama">
                    <label class="form-label">NIM <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="ketua_pelaksana_nim" class="form-input" required
                           inputmode="numeric"
                           data-fv-digits-min="8" data-fv-digits-max="20"
                           data-fv-message="NIM harus berupa angka dan minimal 8 digit."
                           x-model="data.ketua_pelaksana_nim">
                </div>
                <div class="p-4 rounded-lg border" style="border-color:var(--ink-300);">
                    <p class="text-sm font-semibold mb-3" style="color:var(--ink-700);">Pembina 1</p>
                    <label class="form-label">Nama <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="pembina_1_nama" class="form-input mb-2" required
                           x-model="data.pembina_1_nama">
                    <label class="form-label">NIP <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="pembina_1_nip" class="form-input" required
                           inputmode="numeric"
                           data-fv-digits-min="8" data-fv-digits-max="20"
                           data-fv-message="NIP harus berupa angka dan minimal 8 digit."
                           x-model="data.pembina_1_nip">
                </div>
                <div class="p-4 rounded-lg border" style="border-color:var(--ink-300);">
                    <p class="text-sm font-semibold mb-3" style="color:var(--ink-700);">Pembina 2 <span style="font-weight:400; color:var(--ink-500);">(opsional)</span></p>
                    <label class="form-label">Nama</label>
                    <input type="text" name="pembina_2_nama" class="form-input mb-2"
                           x-model="data.pembina_2_nama">
                    <label class="form-label">NIP</label>
                    <input type="text" name="pembina_2_nip" class="form-input"
                           inputmode="numeric"
                           data-fv-digits-min="8" data-fv-digits-max="20"
                           data-fv-message="NIP harus berupa angka dan minimal 8 digit."
                           x-model="data.pembina_2_nip">
                </div>
                <div class="md:col-span-2 p-4 rounded-lg border" style="border-color:var(--ink-300); background:var(--surface-alt);">
                    <p class="text-sm font-semibold mb-3" style="color:var(--ink-700);">Direktur Kemahasiswaan (dapat diubah jika perlu)</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nama Direktur <span style="color:var(--telkom-red)">*</span></label>
                            <input type="text" name="direktur_nama" class="form-input" required
                                   x-model="data.direktur_nama">
                        </div>
                        <div>
                            <label class="form-label">NIP Direktur <span style="color:var(--telkom-red)">*</span></label>
                            <input type="text" name="direktur_nip" class="form-input" required
                                   x-model="data.direktur_nip">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== STEP 13: Q. Lampiran (satu berkas PDF gabungan) ===== --}}
        <div x-show="currentStep === 13" class="card">
            <h2 class="section-heading">Lampiran</h2>
            <div style="margin-bottom:16px; padding:16px; border:1px solid var(--ink-300); border-radius:10px; background:var(--surface-alt);">
                <p style="font-size:13px; color:var(--ink-700); display:flex; align-items:flex-start; gap:8px; margin:0;">
                    <i data-lucide="info" class="w-4 h-4 shrink-0" style="color:var(--ink-500); margin-top:2px;"></i>
                    <span>
                        Gabungkan seluruh lampiran (nota, bukti pembayaran, dokumentasi foto, poster, dll)
                        menjadi <strong>satu berkas PDF</strong>, lalu upload di sini. Halaman PDF lampiran akan
                        digabung otomatis di akhir dokumen LPJ hasil generate. Format: <strong>PDF</strong>. Maks 50MB.
                    </span>
                </p>
            </div>

            @php $lampiranSaatIni = $isEdit ? $model->lampiranAttachments()->orderBy('urutan')->first() : null; @endphp
            @if($lampiranSaatIni)
                <div style="margin-bottom:20px;" x-data="{ deleted: false }">
                    <p class="form-label">Lampiran PDF Saat Ini</p>
                    <div class="flex items-center gap-3 p-3 rounded-lg border mt-1" style="border-color:var(--ink-300);">
                        <i data-lucide="file-text" class="w-7 h-7 shrink-0" style="color:var(--telkom-red);"></i>
                        <div class="flex-1" :style="deleted ? 'text-decoration:line-through; opacity:0.6;' : ''">
                            <a href="{{ Storage::url($lampiranSaatIni->file_path) }}" target="_blank"
                               class="text-sm font-medium" style="color:var(--ink-700);">{{ basename($lampiranSaatIni->file_path) }}</a>
                        </div>
                        <button type="button" x-show="!deleted" @click="deleted = true"
                                class="p-2 hover:bg-red-50 rounded" style="color:var(--danger);" title="Hapus lampiran">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                        <button type="button" x-show="deleted" x-cloak @click="deleted = false"
                                class="text-sm font-semibold" style="color:var(--telkom-red); background:none; border:none; cursor:pointer;">Urungkan</button>
                    </div>
                    <p class="text-xs mt-1" style="color:var(--ink-500);">Upload berkas baru di bawah untuk mengganti lampiran ini.</p>
                    <input type="hidden" name="hapus_lampiran[]" value="{{ $lampiranSaatIni->id }}" :disabled="!deleted">
                </div>
            @endif

            <div x-data="{ name: '' }">
                <label class="form-label">{{ $lampiranSaatIni ? 'Ganti Lampiran (PDF)' : 'Upload Lampiran (PDF)' }}</label>
                <input type="file" name="lampiran_pdf" accept="application/pdf" class="form-input"
                       @change="name = $event.target.files.length ? $event.target.files[0].name : ''">
                <p class="text-sm mt-2" x-show="name" x-cloak style="color:var(--ink-700);">
                    <i data-lucide="file-text" class="w-4 h-4 inline" style="color:var(--telkom-red);"></i>
                    <span x-text="name"></span>
                </p>
            </div>
        </div>

        {{-- ===== STEP 14: Review & Generate ===== --}}
        <div x-show="currentStep === 14" class="card">
            <h2 class="section-heading">Review &amp; {{ $isEdit ? 'Simpan' : 'Generate PDF' }}</h2>

            <div class="space-y-4 mb-6">
                <div style="background:var(--surface); border:1px solid var(--ink-300); border-radius:12px; padding:20px;">
                    <h3 style="font-size:14px; font-weight:700; color:var(--ink-900); margin-bottom:12px; display:flex; align-items:center; gap:8px;">
                        <svg style="width:16px; height:16px; color:var(--telkom-red); flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Identitas Kegiatan
                    </h3>
                    <table style="width:100%; font-size:13px; border-collapse:collapse;">
                        <tr style="border-bottom:1px solid var(--ink-300);">
                            <td style="padding:6px 0; color:var(--ink-500); width:150px;">Nama Kegiatan</td>
                            <td style="padding:6px 0; font-weight:600;" x-text="data.nama_kegiatan || '-'"></td>
                        </tr>
                        <tr style="border-bottom:1px solid var(--ink-300);">
                            <td style="padding:6px 0; color:var(--ink-500);">Akronim</td>
                            <td style="padding:6px 0;" x-text="data.akronim || '-'"></td>
                        </tr>
                        <tr style="border-bottom:1px solid var(--ink-300);">
                            <td style="padding:6px 0; color:var(--ink-500);">Penyelenggara</td>
                            <td style="padding:6px 0;" x-text="data.penyelenggara || '-'"></td>
                        </tr>
                        <tr style="border-bottom:1px solid var(--ink-300);">
                            <td style="padding:6px 0; color:var(--ink-500);">Tanggal</td>
                            <td style="padding:6px 0;" x-text="(data.tanggal_mulai || '-') + ' s/d ' + (data.tanggal_selesai || '-')"></td>
                        </tr>
                        <tr style="border-bottom:1px solid var(--ink-300);">
                            <td style="padding:6px 0; color:var(--ink-500);">Tempat</td>
                            <td style="padding:6px 0;" x-text="data.tempat_kegiatan || '-'"></td>
                        </tr>
                        <tr style="border-bottom:1px solid var(--ink-300);">
                            <td style="padding:6px 0; color:var(--ink-500);">Rundown</td>
                            <td style="padding:6px 0;" x-text="data.rundowns.length + ' item'"></td>
                        </tr>
                        <tr style="border-bottom:1px solid var(--ink-300);">
                            <td style="padding:6px 0; color:var(--ink-500);">Kepanitiaan</td>
                            <td style="padding:6px 0;" x-text="data.committees.length + ' orang'"></td>
                        </tr>
                        <tr>
                            <td style="padding:6px 0; color:var(--ink-500);">Total Dana Keluar</td>
                            <td style="padding:6px 0; font-weight:600;" x-text="window._danaKeluarTotal ? 'Rp ' + window._danaKeluarTotal.toLocaleString('id-ID') : '-'"></td>
                        </tr>
                    </table>
                </div>

                <div style="background:var(--surface-alt); border:1px solid var(--ink-300); border-radius:12px; padding:16px; display:flex; align-items:flex-start; gap:12px;">
                    <svg style="width:20px; height:20px; color:var(--ink-500); flex-shrink:0; margin-top:2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div style="font-size:13px; color:var(--ink-700);">
                        @if($isEdit)
                            <p style="font-weight:700; margin-bottom:4px;">Pastikan semua data sudah benar sebelum menyimpan.</p>
                            <p>Setelah disimpan, status LPJ kembali menjadi Draf. Generate ulang PDF untuk mengunduh versi terbaru.</p>
                        @else
                            <p style="font-weight:700; margin-bottom:4px;">Pastikan semua data sudah benar sebelum generate.</p>
                            <p>LPJ akan tersimpan. Generate PDF dari halaman detail untuk mengunduh.</p>
                        @endif
                    </div>
                </div>
            </div>

            <x-btn-generate-pdf :label="$isEdit ? 'Simpan Perubahan' : 'Simpan LPJ'" />
        </div>

        {{-- Navigasi --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:justify-between mt-6">
            <button type="button" x-show="currentStep > 1" @click="prevStep()"
                    class="btn-secondary w-full sm:w-auto justify-center">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Sebelumnya
            </button>

            <button type="button" @click="nextStep()"
                    x-show="currentStep < totalSteps"
                    class="btn-primary w-full sm:w-auto justify-center sm:ml-auto">
                Selanjutnya <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </button>
        </div>
    </form>

    {{-- Modal: Panduan Pengisian Skala Risiko --}}
    <div x-show="showRiskGuide" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="showRiskGuide = false"
         @click.self="showRiskGuide = false"
         style="position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.45);">
        <div style="display:flex; align-items:center; justify-content:center; min-height:100vh; padding:16px;">
            <div style="background:#fff; border-radius:12px; padding:28px 32px; max-width:520px; width:100%; max-height:85vh; overflow-y:auto; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative;">
                <button type="button" @click="showRiskGuide = false"
                        style="position:absolute; top:16px; right:16px; color:var(--ink-500); padding:4px; border-radius:6px; background:transparent; border:none; cursor:pointer;"
                        class="hover:bg-gray-100">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
                <h3 style="font-size:15px; font-weight:700; color:var(--ink-900); margin-bottom:16px; padding-right:28px;">Panduan Pengisian Skala Risiko</h3>

                <p style="font-size:13px; font-weight:600; color:var(--ink-700); margin-bottom:5px;">Identifikasi Bahaya</p>
                <p style="font-size:12px; color:var(--ink-500); margin-bottom:8px;">Seberapa besar tingkat keparahan bahaya yang teridentifikasi pada kegiatan ini:</p>
                <ul style="font-size:12px; color:var(--ink-700); line-height:1.8; padding-left:0; list-style:none; margin-bottom:18px;">
                    <li><strong>1/5 Sangat Rendah</strong>: bahaya hampir tidak berdampak</li>
                    <li><strong>2/5 Rendah</strong>: bahaya kecil, berdampak minimal</li>
                    <li><strong>3/5 Sedang</strong>: bahaya cukup signifikan</li>
                    <li><strong>4/5 Tinggi</strong>: bahaya besar, perlu penanganan serius</li>
                    <li><strong>5/5 Sangat Tinggi</strong>: bahaya kritis atau fatal</li>
                </ul>

                <p style="font-size:13px; font-weight:600; color:var(--ink-700); margin-bottom:5px;">Peluang / Kemungkinan</p>
                <p style="font-size:12px; color:var(--ink-500); margin-bottom:8px;">Seberapa besar kemungkinan risiko ini benar-benar terjadi:</p>
                <ul style="font-size:12px; color:var(--ink-700); line-height:1.8; padding-left:0; list-style:none; margin-bottom:18px;">
                    <li><strong>1/5 Sangat Jarang</strong>: hampir mustahil terjadi</li>
                    <li><strong>2/5 Jarang</strong>: kemungkinan kecil</li>
                    <li><strong>3/5 Kadang</strong>: mungkin terjadi</li>
                    <li><strong>4/5 Sering</strong>: kemungkinan besar terjadi</li>
                    <li><strong>5/5 Sangat Sering</strong>: hampir pasti terjadi</li>
                </ul>

                <p style="font-size:13px; font-weight:600; color:var(--ink-700); margin-bottom:5px;">Akibat / Keparahan</p>
                <p style="font-size:12px; color:var(--ink-500); margin-bottom:18px;">Tuliskan deskripsi akibat atau konsekuensi yang terjadi jika bahaya tersebut benar-benar terjadi (contoh: "Kurangnya persiapan panitia", "Peserta tidak dapat mengikuti sesi materi").</p>

                <p style="font-size:12px; color:var(--ink-500); border-top:1px solid var(--ink-300); padding-top:12px; margin-bottom:0;">
                    Tingkat Risiko dihitung otomatis dari <strong>Peluang &times; Identifikasi Bahaya</strong> lalu dipetakan ke kategori matrix grading Tel-U:
                    LOW (skor 1-2), MEDIUM (3-9), HIGH (10-14), DANGER (&ge;15).
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function lpjForm(seed, mode) {
    return {
        currentStep: 1,
        totalSteps: 14,
        logoPreview: null,
        logoIsExisting: false,
        danaKeluarError: false,
        showRiskGuide: false,
        isEdit: mode === 'edit',
        stepLabels: [
            'Identitas Kegiatan',
            'Cover & Logo',
            'Latar Belakang',
            'Narasi Utama',
            'Deskripsi Pelaksanaan',
            'Susunan Acara',
            'Analisis Risiko',
            'Monitoring dan Evaluasi',
            'Simpulan & Rekomendasi',
            'Struktur Kepanitiaan',
            'Realisasi Anggaran',
            'Penutup & Pengesahan',
            'Lampiran',
            'Review & Generate',
        ],
        data: {
            nama_kegiatan: '',
            akronim: '',
            tema_kegiatan: '',
            penyelenggara: '',
            afiliasi: '',
            tanggal_mulai: '',
            tanggal_selesai: '',
            waktu_mulai: '',
            waktu_selesai: '',
            tempat_kegiatan: '',
            kota: 'BANDUNG',
            tahun: new Date().getFullYear(),
            latar_belakang: '',
            tujuan_kegiatan: [''],
            sasaran_kegiatan: '',
            bentuk_kegiatan: '',
            deskripsi_pelaksanaan: '',
            simpulan_rekomendasi: '',
            penutup: '',
            ketua_pelaksana_nama: '',
            ketua_pelaksana_nim: '',
            ketua_ukm_nama: '',
            ketua_ukm_nim: '',
            pembina_1_nama: '',
            pembina_1_nip: '',
            pembina_2_nama: '',
            pembina_2_nip: '',
            direktur_nama: 'Dr. Maulana Rezi Ramadhana, S.Psi., M.Psi., Psikolog',
            direktur_nip: '20820005',
            rundowns: [{waktu_mulai:'',waktu_selesai:'',durasi:'',detail_kegiatan:'',pic:'',_tpMulaiOpen:false,_tpSelesaiOpen:false}],
            risks: [{uraian_kegiatan:'',identifikasi_bahaya:'',peluang:'',akibat:'',tingkat_risiko:'',pengendalian_risiko:'',penanggung_jawab:''}],
            monitoring_groups: [{tanggal:'',fase:'',items:[{detail_kegiatan:'',pic:'',keterangan:''}]}],
            committees: [{jabatan:'',nama:'',nim:'',jurusan:'',angkatan:'',fakultas:''}],
            dana_masuk: [{sumber_dana:'',target:0,jumlah_total:0}],
        },

        draftKey: 'lpj_draft_{{ auth()->id() }}',
        tpHours: Array.from({ length: 24 }, (_, i) => String(i).padStart(2,'0')),
        tpMinutes: Array.from({ length: 12 }, (_, i) => String(i*5).padStart(2,'0')),

        init() {
            // Mode edit: prefill dari server, TANPA restore/autosave draft localStorage.
            if (this.isEdit && seed && seed.data) {
                this.data = { ...this.data, ...seed.data };
                if (seed.logoUrl) { this.logoPreview = seed.logoUrl; this.logoIsExisting = true; }
            } else {
                // Mode create: bersihkan draft user lain & restore draft milik user ini.
                Object.keys(localStorage)
                    .filter(k => k.startsWith('lpj_draft') && k !== this.draftKey)
                    .forEach(k => localStorage.removeItem(k));

                const saved = localStorage.getItem(this.draftKey);
                if (saved) {
                    try {
                        const parsed = JSON.parse(saved);
                        this.data = { ...this.data, ...parsed };
                    } catch(e) {}
                }
            }

            // Pastikan repeater selalu punya minimal satu baris (konsisten dengan mode create).
            if (!Array.isArray(this.data.tujuan_kegiatan) || this.data.tujuan_kegiatan.length === 0) {
                this.data.tujuan_kegiatan = [''];
            }
            if (!Array.isArray(this.data.rundowns) || this.data.rundowns.length === 0) {
                this.data.rundowns = [{waktu_mulai:'',waktu_selesai:'',durasi:'',detail_kegiatan:'',pic:'',_tpMulaiOpen:false,_tpSelesaiOpen:false}];
            }
            if (!Array.isArray(this.data.risks) || this.data.risks.length === 0) {
                this.data.risks = [{uraian_kegiatan:'',identifikasi_bahaya:'',peluang:'',akibat:'',tingkat_risiko:'',pengendalian_risiko:'',penanggung_jawab:''}];
            }
            if (!Array.isArray(this.data.committees) || this.data.committees.length === 0) {
                this.data.committees = [{jabatan:'',nama:'',nim:'',jurusan:'',angkatan:'',fakultas:''}];
            }
            if (!Array.isArray(this.data.dana_masuk) || this.data.dana_masuk.length === 0) {
                this.data.dana_masuk = [{sumber_dana:'',target:0,jumlah_total:0}];
            }

            // Normalisasi monitoring_groups agar tahan terhadap data lama/rusak.
            if (!Array.isArray(this.data.monitoring_groups) || this.data.monitoring_groups.length === 0) {
                this.data.monitoring_groups = [{ tanggal: '', fase: '', items: [{ detail_kegiatan: '', pic: '', keterangan: '' }] }];
            } else {
                this.data.monitoring_groups.forEach(g => {
                    if (!Array.isArray(g.items) || g.items.length === 0) {
                        g.items = [{ detail_kegiatan: '', pic: '', keterangan: '' }];
                    }
                });
            }
            delete this.data.monitoring;

            // Autosave draft hanya untuk mode create.
            if (!this.isEdit) {
                this.$watch('data', (val) => {
                    localStorage.setItem(this.draftKey, JSON.stringify(val));
                }, { deep: true });
            }

            this.$watch('currentStep', () => {
                this.$nextTick(() => {
                    if (window.lucide) lucide.createIcons();
                    const form = document.getElementById('lpjForm');
                    if (form) { form.classList.remove('step-pane'); void form.offsetWidth; form.classList.add('step-pane'); }
                });
            });

            const lpjFormEl = document.getElementById('lpjForm');
            if (lpjFormEl) {
                lpjFormEl.addEventListener('submit', (e) => {
                    if (!window.FormValidator) return;
                    const invalid = window.FormValidator.validateAll(lpjFormEl)
                        || this.checkTanggal();
                    if (invalid) {
                        e.preventDefault();
                        const step = this.stepOfField(invalid);
                        if (step) this.currentStep = step;
                        window.scrollTo(0, 0);
                        this.$nextTick(() => invalid.focus({ preventScroll: true }));
                        return;
                    }
                    if (!this.validateDanaKeluar()) {
                        e.preventDefault();
                        this.currentStep = 11;
                        return;
                    }
                    if (!this.isEdit) localStorage.removeItem(this.draftKey);
                });
            }
        },

        clearDraft() {
            if (!this.isEdit) localStorage.removeItem(this.draftKey);
            const form = document.getElementById('lpjForm');
            if (form && window.FormValidator) window.FormValidator.clearScope(form);
        },

        stepOfField(field) {
            let el = field.closest ? field.closest('[x-show]') : null;
            while (el) {
                const m = (el.getAttribute('x-show') || '').match(/currentStep\s*===\s*(\d+)/);
                if (m) return parseInt(m[1], 10);
                el = el.parentElement ? el.parentElement.closest('[x-show]') : null;
            }
            return null;
        },

        checkTanggal() {
            const a = this.data.tanggal_mulai, b = this.data.tanggal_selesai;
            const trigger = document.querySelector('#lpjForm .dp-trigger');
            let msg = null;
            if (!a || !b) {
                msg = 'Tanggal pelaksanaan (mulai dan selesai) wajib diisi.';
            } else if (b < a) {
                msg = 'Tanggal Selesai tidak boleh sebelum Tanggal Mulai.';
            }
            if (msg) {
                if (trigger && window.FormValidator) window.FormValidator.setError(trigger, msg);
                return trigger;
            }
            if (trigger && window.FormValidator) window.FormValidator.clearError(trigger);
            return null;
        },

        nextStep() {
            if (!this.validateStep()) return;
            if (this.currentStep < this.totalSteps) this.currentStep++;
            window.scrollTo(0,0);
        },
        prevStep() { if (this.currentStep > 1) this.currentStep--; window.scrollTo(0,0); },

        validateStep() {
            const form = document.getElementById('lpjForm');
            let ok = window.FormValidator ? window.FormValidator.validateScope(form) : true;

            if (this.currentStep === 1 && this.checkTanggal()) {
                ok = false;
            }
            if (this.currentStep === 11 && !this.validateDanaKeluar()) {
                ok = false;
            }
            return ok;
        },

        validateDanaKeluar() {
            const el = document.querySelector('#lpjForm [name="dana_keluar_json"]');
            let divisions = [];
            try { divisions = JSON.parse((el && el.value) || '[]'); } catch (e) {}

            let valid = Array.isArray(divisions) && divisions.length > 0;
            divisions.forEach((d) => {
                if (!d.nama_divisi || !String(d.nama_divisi).trim()) valid = false;
                (d.categories || []).forEach((k) => {
                    if (!k.nama_kategori || !String(k.nama_kategori).trim()) valid = false;
                    (k.subitems || []).forEach((s) => {
                        if (!s.rincian_kebutuhan || !String(s.rincian_kebutuhan).trim()) valid = false;
                        if (!s.jumlah || parseFloat(s.jumlah) <= 0) valid = false;
                        if (!s.harga_satuan || parseFloat(s.harga_satuan) <= 0) valid = false;
                    });
                });
            });

            this.danaKeluarError = !valid;
            if (!valid) {
                this.$nextTick(() => {
                    const errEl = document.getElementById('danaKeluarError');
                    if (errEl) errEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            }
            return valid;
        },

        hitungDurasi(row) {
            if (!row.waktu_mulai || !row.waktu_selesai) { row.durasi = ''; return; }
            const [jm, mm] = row.waktu_mulai.split(':').map(Number);
            const [js, ms] = row.waktu_selesai.split(':').map(Number);
            const selisih = (js * 60 + ms) - (jm * 60 + mm);
            if (selisih <= 0) { row.durasi = ''; return; }
            const jam = Math.floor(selisih / 60);
            const menit = selisih % 60;
            if (jam > 0 && menit > 0) row.durasi = jam + ' jam ' + menit + ' menit';
            else if (jam > 0) row.durasi = jam + ' jam';
            else row.durasi = menit + ' menit';
        },

        pickRdTime(idx, field, part, val) {
            const r = this.data.rundowns[idx];
            const key = field === 'mulai' ? 'waktu_mulai' : 'waktu_selesai';
            const cur = (r[key] || '00:00').split(':');
            r[key] = part === 'h' ? (val + ':' + (cur[1] || '00')) : ((cur[0] || '00') + ':' + val);
            this.hitungDurasi(r);
        },

        tambahGroup() {
            this.data.monitoring_groups.push({ tanggal: '', fase: '', items: [{ detail_kegiatan: '', pic: '', keterangan: '' }] });
            this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
        },
        hapusGroup(gIdx) {
            if (this.data.monitoring_groups.length > 1) this.data.monitoring_groups.splice(gIdx, 1);
        },
        tambahItem(gIdx) {
            this.data.monitoring_groups[gIdx].items.push({ detail_kegiatan: '', pic: '', keterangan: '' });
            this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
        },
        hapusItem(gIdx, iIdx) {
            const items = this.data.monitoring_groups[gIdx].items;
            if (items.length > 1) items.splice(iIdx, 1);
        },

        computeTingkatRisiko(p, i) {
            // Peluang x Identifikasi Bahaya -> kategori matrix grading Tel-U.
            if (!p || !i) return '-';
            const pv = parseInt(p.split('/')[0]);
            const iv = parseInt(i.split('/')[0]);
            if (isNaN(pv) || isNaN(iv)) return '-';
            const score = pv * iv;
            if (score <= 2) return 'LOW';
            if (score <= 9) return 'MEDIUM';
            if (score <= 14) return 'HIGH';
            return 'DANGER';
        },

        previewLogo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => { this.logoPreview = e.target.result; this.logoIsExisting = false; };
                reader.readAsDataURL(file);
            }
        },
    };
}

function danaKeluarForm(seed) {
    return {
        divisions: [],
        grandTotal: 0,

        init() {
            if (seed && Array.isArray(seed) && seed.length > 0) {
                this.divisions = seed;
            } else if (this.divisions.length === 0) {
                this.tambahDivisi();
            }
            this.hitungTotal();
            this.$watch('divisions', () => {
                this.hitungTotal();
                window._danaKeluarTotal = this.grandTotal;
            }, { deep: true });
        },

        tambahDivisi() {
            this.divisions.push({ nama_divisi: '', categories: [this._kategoriKosong()] });
            this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
        },

        hapusDivisi(dIdx) {
            if (this.divisions.length === 1) { alert('Minimal harus ada satu divisi.'); return; }
            if (confirm('Hapus divisi ini beserta semua kategori dan rincian di dalamnya?')) {
                this.divisions.splice(dIdx, 1);
            }
        },

        tambahKategori(dIdx) {
            this.divisions[dIdx].categories.push(this._kategoriKosong());
            this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
        },

        hapusKategori(dIdx, kIdx) {
            if (this.divisions[dIdx].categories.length === 1) { alert('Minimal harus ada satu kategori.'); return; }
            this.divisions[dIdx].categories.splice(kIdx, 1);
        },

        tambahSubitem(dIdx, kIdx) {
            this.divisions[dIdx].categories[kIdx].subitems.push(this._subitemKosong());
        },

        hapusSubitem(dIdx, kIdx, sIdx) {
            const subs = this.divisions[dIdx].categories[kIdx].subitems;
            if (subs.length > 1) subs.splice(sIdx, 1);
        },

        hitungTotal() {
            this.grandTotal = this.divisions.reduce((t, d) =>
                t + d.categories.reduce((td, k) =>
                    td + k.subitems.reduce((tk, s) =>
                        tk + (parseFloat(s.jumlah) || 0) * (parseFloat(s.harga_satuan) || 0), 0), 0), 0);
            window._danaKeluarTotal = this.grandTotal;
        },

        totalDivisi(dIdx) {
            return this.divisions[dIdx].categories.reduce((td, k) =>
                td + k.subitems.reduce((tk, s) =>
                    tk + (parseFloat(s.jumlah) || 0) * (parseFloat(s.harga_satuan) || 0), 0), 0);
        },

        formatRupiah(n) {
            if (!n || isNaN(n)) return '0';
            return new Intl.NumberFormat('id-ID').format(Math.round(n));
        },

        _kategoriKosong() {
            return { nama_kategori: '', subitems: [this._subitemKosong()] };
        },

        _subitemKosong() {
            return { rincian_kebutuhan: '', jumlah: '', satuan: '', harga_satuan: '' };
        },
    };
}
</script>
@endpush

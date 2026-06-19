{{--
    Shared form partial for Proposal (create & edit).
    Expects:
      $mode       'create' | 'edit'
      $action     form action URL
      $formMethod 'POST' | 'PUT'
      $model      Proposal|null (untuk prefill field non-Alpine)
      $seed       array|null (seed state Alpine.js)
--}}
@php
    $mode       = $mode ?? 'create';
    $formMethod = $formMethod ?? 'POST';
    $model      = $model ?? null;
    $isEdit     = $mode === 'edit';
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

<div x-data="proposalForm(@js($seed ?? null))" x-init="init()">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 style="font-family:'Poppins','Segoe UI',sans-serif; font-size:24px; font-weight:600; color:var(--ink-900); margin-bottom:4px;">
                {{ $isEdit ? 'Edit Proposal Kegiatan' : 'Buat Proposal Kegiatan' }}
            </h1>
            <p style="font-size:14px; color:var(--ink-500);">
                Langkah <span x-text="currentStep"></span> dari <span x-text="totalSteps"></span>:
                <span x-text="stepLabels[currentStep - 1]"></span>
            </p>
        </div>
        <a href="{{ $isEdit ? route('proposal.show', $model) : route('dashboard') }}" class="btn-secondary text-sm">
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

    {{-- Form --}}
    <form action="{{ $action }}" method="POST" enctype="multipart/form-data" id="proposalForm" novalidate data-fv-manual="true">
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

        {{-- ========== STEP 1: Identitas Kegiatan ========== --}}
        <div x-show="currentStep === 1" class="card">
            <h2 class="section-heading">Identitas Kegiatan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="form-label">Nama Kegiatan <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="nama_kegiatan" class="form-input" required
                           :value="data.nama_kegiatan"
                           @input="data.nama_kegiatan = $event.target.value">
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Tema Kegiatan <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="tema_kegiatan" class="form-input" required
                           :value="data.tema_kegiatan"
                           @input="data.tema_kegiatan = $event.target.value">
                </div>
                <div>
                    <label class="form-label">Penyelenggara <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="penyelenggara" class="form-input" required
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

        {{-- ========== STEP 2: Cover & Logo ========== --}}
        <div x-show="currentStep === 2" class="card">
            <h2 class="section-heading">Cover & Logo Organisasi</h2>
            <div class="mb-4 p-4 rounded-lg" style="background:var(--surface-alt); border:1px solid var(--ink-300);">
                <p class="text-sm flex items-start gap-2" style="color:var(--ink-700);">
                    <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5" style="color:var(--ink-500);"></i>
                    <span>Logo Telkom University sudah otomatis disertakan. Upload logo UKM/organisasi kamu di sini.</span>
                </p>
            </div>
            <div>
                <label class="form-label">Logo UKM / Organisasi @if(!$isEdit)<span style="color:var(--telkom-red)">*</span>@endif</label>
                <input type="file" name="logo_organisasi" accept="image/png,image/jpeg,image/jpg" @if(!$isEdit) required @endif
                       class="form-input" style="padding: 8px;"
                       @change="previewLogo($event)">
                <p class="text-xs mt-1" style="color:var(--ink-500);">
                    Format: PNG, JPG. Maks 2MB.@if($isEdit) Biarkan kosong untuk mempertahankan logo saat ini.@endif
                </p>
            </div>
            <div x-show="logoPreview" class="mt-4">
                <p class="form-label" x-text="logoIsExisting ? 'Logo saat ini:' : 'Preview:'"></p>
                <img :src="logoPreview" class="mt-2 rounded-lg" style="max-height:160px; object-fit:contain; border:1px solid var(--ink-300); padding:8px;">
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
                <label class="form-label">Logo UKM Kolaborasi <span class="text-xs font-normal" style="color:var(--ink-500);">(opsional)</span></label>
                <div class="mb-3 p-4 rounded-lg" style="background:var(--surface-alt); border:1px solid var(--ink-300);">
                    <p class="text-sm flex items-start gap-2" style="color:var(--ink-700);">
                        <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5" style="color:var(--ink-500);"></i>
                        <span>Jika kegiatan ini berkolaborasi dengan UKM/organisasi lain, upload logo mereka di sini (boleh lebih dari satu). Semua logo akan tampil di cover. Kosongkan jika tidak ada kolaborasi.</span>
                    </p>
                </div>

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
                       class="form-input" style="padding:8px;" @change="handle($event)">
                <p class="text-xs mt-1" style="color:var(--ink-500);">Format: PNG, JPG. Maks 2MB per logo.</p>

                <div class="flex flex-wrap gap-3 mt-3">
                    <template x-for="(p, idx) in previews" :key="idx">
                        <div style="width:120px; border:1px solid var(--ink-300); border-radius:10px; padding:10px;">
                            <img :src="p.url" style="width:100%; height:70px; object-fit:contain;">
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- ========== STEP 3: Narasi Kegiatan ========== --}}
        <div x-show="currentStep === 3" class="card">
            <h2 class="section-heading">Narasi Kegiatan</h2>

            <div class="mb-6">
                <label class="form-label">Latar Belakang <span style="color:var(--telkom-red)">*</span></label>
                <textarea name="latar_belakang" rows="8" class="form-input" required data-rich
                          x-model="data.latar_belakang"></textarea>
            </div>

            <div class="mb-6">
                <label class="form-label">Tujuan Kegiatan <span style="color:var(--telkom-red)">*</span></label>
                <div class="space-y-2">
                    <template x-for="(tujuan, idx) in tujuanList" :key="idx">
                        <div class="flex items-center gap-2">
                            <span x-text="(idx + 1) + '.'" class="text-sm font-semibold" style="color:var(--ink-500); min-width:20px;"></span>
                            <input type="text" :name="'tujuan_kegiatan[' + idx + ']'"
                                   x-model="tujuanList[idx]" required
                                   class="form-input flex-1">
                            <button type="button" @click="removeTujuan(idx)"
                                    x-show="tujuanList.length > 1"
                                    class="p-2 rounded-lg hover:bg-red-50 transition-all"
                                    style="color:var(--danger);">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </template>
                </div>
                <button type="button" @click="addTujuan()" class="btn-secondary mt-3 text-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Tujuan
                </button>
            </div>

            <div class="mb-6">
                <label class="form-label">Sasaran Kegiatan <span style="color:var(--telkom-red)">*</span></label>
                <textarea name="sasaran_kegiatan" rows="3" class="form-input" required data-rich
                          x-model="data.sasaran_kegiatan"></textarea>
            </div>

            <div>
                <label class="form-label">Bentuk Kegiatan <span style="color:var(--telkom-red)">*</span></label>
                <textarea name="bentuk_kegiatan" rows="4" class="form-input" required data-rich
                          x-model="data.bentuk_kegiatan"></textarea>
            </div>
        </div>

        {{-- ========== STEP 4: Materi & Narasumber ========== --}}
        <div x-show="currentStep === 4" class="card">
            <h2 class="section-heading">Materi & Narasumber</h2>

            <div class="mb-6">
                <label class="form-label">Materi Kegiatan <span style="color:var(--telkom-red)">*</span></label>
                <div class="space-y-4">
                    <template x-for="(materi, idx) in materiList" :key="idx">
                        <div class="p-4 rounded-lg border" style="border-color:var(--ink-300);">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold" style="color:var(--ink-700);">
                                    Materi <span x-text="idx + 1"></span>
                                </span>
                                <button type="button" @click="removeMateri(idx)"
                                        x-show="materiList.length > 1"
                                        class="p-1 hover:bg-red-50 rounded" style="color:var(--danger);">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <div class="mb-2">
                                <input type="text" :name="'materi_kegiatan[' + idx + '][judul]'"
                                       x-model="materiList[idx].judul" required
                                       class="form-input">
                            </div>
                            <div>
                                <textarea :name="'materi_kegiatan[' + idx + '][deskripsi]'"
                                          x-model="materiList[idx].deskripsi" required
                                          rows="3" class="form-input"></textarea>
                            </div>
                        </div>
                    </template>
                </div>
                <button type="button" @click="addMateri()" class="btn-secondary mt-3 text-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Materi
                </button>
            </div>

            <div>
                <label class="form-label">Narasumber Kegiatan <span style="color:var(--telkom-red)">*</span></label>
                <textarea name="narasumber_kegiatan" rows="3" class="form-input" required
                          x-model="data.narasumber_kegiatan"></textarea>
            </div>
        </div>

        {{-- ========== STEP 5: Rundown ========== --}}
        <div x-show="currentStep === 5" class="card">
            <h2 class="section-heading">Susunan Acara (Rundown)</h2>
            <div style="overflow:visible;">
                <table class="repeater-table">
                    <thead>
                        <tr>
                            <th style="width:130px;">Waktu Mulai <span style="color:var(--telkom-red)">*</span></th>
                            <th style="width:130px;">Waktu Selesai <span style="color:var(--telkom-red)">*</span></th>
                            <th style="width:80px;">Durasi (menit)</th>
                            <th>Aktivitas <span style="color:var(--telkom-red)">*</span></th>
                            <th style="width:40px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, idx) in rundowns" :key="idx">
                            <tr>
                                <td style="overflow:visible;">
                                    <div class="relative" @click.outside="rundowns[idx]._tpMulaiOpen = false">
                                        <button type="button" class="form-input tp-trigger"
                                                @click="rundowns[idx]._tpMulaiOpen = !rundowns[idx]._tpMulaiOpen; rundowns[idx]._tpSelesaiOpen = false">
                                            <i data-lucide="clock" class="w-4 h-4 shrink-0" style="color:var(--ink-500);"></i>
                                            <span :style="rundowns[idx].waktu_mulai ? 'color:var(--ink-700)' : 'color:var(--ink-300)'"
                                                  x-text="rundowns[idx].waktu_mulai || 'Pilih waktu'"></span>
                                        </button>
                                        <input type="hidden" :name="'rundowns[' + idx + '][waktu_mulai]'" :value="rundowns[idx].waktu_mulai">
                                        <div x-show="rundowns[idx]._tpMulaiOpen" x-cloak class="tp-popover">
                                            <div class="tp-head"><span>Jam</span><span>Menit</span></div>
                                            <div class="tp-cols">
                                                <div class="tp-col">
                                                    <template x-for="h in tpHours" :key="h">
                                                        <button type="button" class="tp-cell"
                                                                :class="{'is-active': (rundowns[idx].waktu_mulai||'').split(':')[0] === h}"
                                                                @mousedown.prevent="pickRdTime(idx, 'mulai', 'h', h)"
                                                                x-text="h"></button>
                                                    </template>
                                                </div>
                                                <div class="tp-col">
                                                    <template x-for="m in tpMinutes" :key="m">
                                                        <button type="button" class="tp-cell"
                                                                :class="{'is-active': (rundowns[idx].waktu_mulai||'').split(':')[1] === m}"
                                                                @mousedown.prevent="pickRdTime(idx, 'mulai', 'm', m)"
                                                                x-text="m"></button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td style="overflow:visible;">
                                    <div class="relative" @click.outside="rundowns[idx]._tpSelesaiOpen = false">
                                        <button type="button" class="form-input tp-trigger"
                                                @click="rundowns[idx]._tpSelesaiOpen = !rundowns[idx]._tpSelesaiOpen; rundowns[idx]._tpMulaiOpen = false">
                                            <i data-lucide="clock" class="w-4 h-4 shrink-0" style="color:var(--ink-500);"></i>
                                            <span :style="rundowns[idx].waktu_selesai ? 'color:var(--ink-700)' : 'color:var(--ink-300)'"
                                                  x-text="rundowns[idx].waktu_selesai || 'Pilih waktu'"></span>
                                        </button>
                                        <input type="hidden" :name="'rundowns[' + idx + '][waktu_selesai]'" :value="rundowns[idx].waktu_selesai">
                                        <div x-show="rundowns[idx]._tpSelesaiOpen" x-cloak class="tp-popover">
                                            <div class="tp-head"><span>Jam</span><span>Menit</span></div>
                                            <div class="tp-cols">
                                                <div class="tp-col">
                                                    <template x-for="h in tpHours" :key="h">
                                                        <button type="button" class="tp-cell"
                                                                :class="{'is-active': (rundowns[idx].waktu_selesai||'').split(':')[0] === h}"
                                                                @mousedown.prevent="pickRdTime(idx, 'selesai', 'h', h)"
                                                                x-text="h"></button>
                                                    </template>
                                                </div>
                                                <div class="tp-col">
                                                    <template x-for="m in tpMinutes" :key="m">
                                                        <button type="button" class="tp-cell"
                                                                :class="{'is-active': (rundowns[idx].waktu_selesai||'').split(':')[1] === m}"
                                                                @mousedown.prevent="pickRdTime(idx, 'selesai', 'm', m)"
                                                                x-text="m"></button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <input type="number" :name="'rundowns[' + idx + '][durasi_menit]'"
                                           x-model="rundowns[idx].durasi_menit"
                                           readonly class="form-input text-center"
                                           style="background:var(--surface-muted);">
                                </td>
                                <td>
                                    <input type="text" :name="'rundowns[' + idx + '][aktivitas]'"
                                           x-model="rundowns[idx].aktivitas" required
                                           class="form-input">
                                </td>
                                <td>
                                    <button type="button" @click="removeRundown(idx)"
                                            x-show="rundowns.length > 1"
                                            class="p-1 hover:bg-red-50 rounded" style="color:var(--danger);">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <button type="button" @click="addRundown()" class="btn-secondary mt-3 text-sm">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Baris
            </button>
        </div>

        {{-- ========== STEP 6: Analisis Risiko ========== --}}
        <div x-show="currentStep === 6" class="card">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                <h2 class="section-heading" style="margin-bottom:0;">Analisis Risiko</h2>
                <button type="button" @click="showRiskGuide = true"
                        class="p-1 rounded hover:bg-gray-100"
                        style="color:var(--ink-500); flex-shrink:0; background:transparent; border:none; cursor:pointer;"
                        title="Panduan pengisian skala risiko">
                    <i data-lucide="info" class="w-4 h-4"></i>
                </button>
            </div>
            <p class="text-sm mb-4 mt-1" style="color:var(--ink-500);">
                Tambahkan potensi risiko kegiatan beserta penanganannya.
            </p>
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
                        <template x-for="(row, idx) in risks" :key="idx">
                            <tr>
                                <td><input type="text" :name="'risks[' + idx + '][uraian_kegiatan]'" class="form-input repeater-table" x-model="risks[idx].uraian_kegiatan" required></td>
                                <td>
                                    <select :name="'risks[' + idx + '][identifikasi_bahaya]'" class="form-input repeater-table ui-select" x-model="risks[idx].identifikasi_bahaya" required style="width:100%;">
                                        <option value="">Pilih</option>
                                        <option value="1/5">1/5</option>
                                        <option value="2/5">2/5</option>
                                        <option value="3/5">3/5</option>
                                        <option value="4/5">4/5</option>
                                        <option value="5/5">5/5</option>
                                    </select>
                                </td>
                                <td>
                                    <select :name="'risks[' + idx + '][peluang]'" class="form-input repeater-table ui-select" x-model="risks[idx].peluang" required style="width:100%;">
                                        <option value="">Pilih</option>
                                        <option value="1/5">1/5</option>
                                        <option value="2/5">2/5</option>
                                        <option value="3/5">3/5</option>
                                        <option value="4/5">4/5</option>
                                        <option value="5/5">5/5</option>
                                    </select>
                                </td>
                                <td><input type="text" :name="'risks[' + idx + '][akibat]'" class="form-input repeater-table" x-model="risks[idx].akibat" required></td>
                                <td style="text-align:center; vertical-align:middle;">
                                    <span x-text="computeTingkatRisiko(risks[idx].peluang, risks[idx].identifikasi_bahaya)" style="font-weight:600; font-size:13px; color:var(--ink-700);"></span>
                                    <input type="hidden" :name="'risks[' + idx + '][tingkat_risiko]'" :value="computeTingkatRisiko(risks[idx].peluang, risks[idx].identifikasi_bahaya)">
                                </td>
                                <td><input type="text" :name="'risks[' + idx + '][pengendalian_risiko]'" class="form-input repeater-table" x-model="risks[idx].pengendalian_risiko" required></td>
                                <td><input type="text" :name="'risks[' + idx + '][penanggung_jawab]'" class="form-input repeater-table" x-model="risks[idx].penanggung_jawab" required></td>
                                <td>
                                    <button type="button" @click="removeRisk(idx)"
                                            x-show="risks.length > 1"
                                            class="p-1 hover:bg-red-50 rounded" style="color:var(--danger);">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <button type="button" @click="addRisk()" class="btn-secondary mt-3 text-sm">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Risiko
            </button>
        </div>

        {{-- ========== STEP 7: Monitoring & Evaluasi ========== --}}
        <div x-show="currentStep === 7" class="card">
            <h2 class="section-heading">Monitoring dan Evaluasi <span style="color:var(--telkom-red)">*</span></h2>
            <textarea name="monitoring_evaluasi" rows="8" class="form-input" required data-rich
                      x-model="data.monitoring_evaluasi"></textarea>
            <p class="text-xs mt-2" style="color:var(--ink-500);">
                Tuliskan indikator keberhasilan, ketepatan waktu, kepuasan peserta, dan aspek evaluasi lainnya.
            </p>
        </div>

        {{-- ========== STEP 8: Struktur Kepanitiaan ========== --}}
        <div x-show="currentStep === 8" class="card">
            <h2 class="section-heading">Struktur Kepanitiaan</h2>
            <p class="text-xs text-gray-400 mt-1 sm:hidden">Geser ke kanan untuk melihat semua kolom</p>
            <div class="overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0">
                <div class="min-w-[640px] sm:min-w-0">
                <table class="repeater-table">
                    <thead>
                        <tr>
                            <th style="min-width:130px;">Jabatan <span style="color:var(--telkom-red)">*</span></th>
                            <th style="min-width:140px;">Nama <span style="color:var(--telkom-red)">*</span></th>
                            <th style="min-width:120px;">Jurusan <span style="color:var(--telkom-red)">*</span></th>
                            <th style="width:80px;">Angkatan <span style="color:var(--telkom-red)">*</span></th>
                            <th style="min-width:100px;">Fakultas <span style="color:var(--telkom-red)">*</span></th>
                            <th style="width:100px;">NIM <span style="color:var(--telkom-red)">*</span></th>
                            <th style="width:40px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, idx) in committees" :key="idx">
                            <tr>
                                <td><input type="text" :name="'committees[' + idx + '][jabatan]'"
                                           x-model="committees[idx].jabatan" required class="form-input"></td>
                                <td><input type="text" :name="'committees[' + idx + '][nama]'"
                                           x-model="committees[idx].nama" required class="form-input"></td>
                                <td><input type="text" :name="'committees[' + idx + '][jurusan]'"
                                           x-model="committees[idx].jurusan" required class="form-input"></td>
                                <td><input type="text" :name="'committees[' + idx + '][tahun_angkatan]'"
                                           x-model="committees[idx].tahun_angkatan" required class="form-input"></td>
                                <td><input type="text" :name="'committees[' + idx + '][fakultas]'"
                                           x-model="committees[idx].fakultas" required class="form-input"></td>
                                <td><input type="text" :name="'committees[' + idx + '][nim]'"
                                           x-model="committees[idx].nim" required class="form-input"></td>
                                <td>
                                    <button type="button" @click="removeCommittee(idx)"
                                            x-show="committees.length > 1"
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
            <button type="button" @click="addCommittee()" class="btn-secondary mt-3 text-sm">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Anggota
            </button>
        </div>

        {{-- ========== STEP 9: Rencana Anggaran Biaya ========== --}}
        <div x-show="currentStep === 9" class="card space-y-8">
            <h2 class="section-heading">Rencana Anggaran Biaya</h2>

            {{-- Pemasukan --}}
            <div>
                <h3 class="font-semibold mb-3" style="color:var(--ink-900); font-size:15px;">1. Pemasukan</h3>
                <table class="repeater-table">
                    <thead>
                        <tr><th style="width:40px;">No.</th><th>Keterangan <span style="color:var(--telkom-red)">*</span></th><th style="width:160px;">Jumlah (Rp) <span style="color:var(--telkom-red)">*</span></th><th style="width:40px;"></th></tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, idx) in budgetsPemasukan" :key="idx">
                            <tr>
                                <td class="text-center text-sm" x-text="idx + 1"></td>
                                <td><input type="text" :name="'budgets_pemasukan[' + idx + '][keterangan]'"
                                           x-model="budgetsPemasukan[idx].keterangan" required class="form-input"></td>
                                <td><input type="number" :name="'budgets_pemasukan[' + idx + '][total]'"
                                           x-model="budgetsPemasukan[idx].total" required class="form-input text-right" min="0"></td>
                                <td>
                                    <button type="button" @click="budgetsPemasukan.splice(idx,1)"
                                            x-show="budgetsPemasukan.length > 1"
                                            class="p-1 hover:bg-red-50 rounded" style="color:var(--danger);">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <tr>
                            <td colspan="2" class="text-right font-semibold text-sm pr-4">Total Pemasukan</td>
                            <td class="text-right font-semibold text-sm">
                                Rp <span x-text="formatRp(budgetsPemasukan.reduce((s,r) => s + parseFloat(r.total||0), 0))"></span>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" @click="budgetsPemasukan.push({keterangan:'',total:0})" class="btn-secondary mt-2 text-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Baris
                </button>
            </div>

            {{-- Pengeluaran --}}
            <div>
                <h3 class="font-semibold mb-3" style="color:var(--ink-900); font-size:15px;">2. Pengeluaran</h3>
                <div style="overflow-x:auto;">
                    <table class="repeater-table">
                        <thead>
                            <tr>
                                <th style="width:40px;">No.</th>
                                <th>Keterangan <span style="color:var(--telkom-red)">*</span></th>
                                <th style="width:80px;">Qty <span style="color:var(--telkom-red)">*</span></th>
                                <th style="width:80px;">Satuan <span style="color:var(--telkom-red)">*</span></th>
                                <th style="width:130px;">Harga Satuan <span style="color:var(--telkom-red)">*</span></th>
                                <th style="width:130px;">Total</th>
                                <th style="width:40px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(row, idx) in budgetsPengeluaran" :key="idx">
                                <tr>
                                    <td class="text-center text-sm" x-text="idx + 1"></td>
                                    <td><input type="text" :name="'budgets_pengeluaran[' + idx + '][keterangan]'"
                                               x-model="budgetsPengeluaran[idx].keterangan" required class="form-input"></td>
                                    <td><input type="number" :name="'budgets_pengeluaran[' + idx + '][kuantitas]'"
                                               x-model.number="budgetsPengeluaran[idx].kuantitas"
                                               @input="calcBudget(idx)" required
                                               class="form-input text-center" min="0"></td>
                                    <td><input type="text" :name="'budgets_pengeluaran[' + idx + '][satuan]'"
                                               x-model="budgetsPengeluaran[idx].satuan" required class="form-input"></td>
                                    <td><input type="number" :name="'budgets_pengeluaran[' + idx + '][harga_satuan]'"
                                               x-model.number="budgetsPengeluaran[idx].harga_satuan"
                                               @input="calcBudget(idx)" required
                                               class="form-input text-right" min="0"></td>
                                    <td>
                                        <input type="number" :name="'budgets_pengeluaran[' + idx + '][total]'"
                                               x-model="budgetsPengeluaran[idx].total"
                                               readonly class="form-input text-right"
                                               style="background:var(--surface-muted);">
                                    </td>
                                    <td>
                                        <button type="button" @click="budgetsPengeluaran.splice(idx,1)"
                                                x-show="budgetsPengeluaran.length > 1"
                                                class="p-1 hover:bg-red-50 rounded" style="color:var(--danger);">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <tr>
                                <td colspan="5" class="text-right font-semibold text-sm pr-4">TOTAL PENGELUARAN</td>
                                <td class="text-right font-semibold text-sm">
                                    Rp <span x-text="formatRp(budgetsPengeluaran.reduce((s,r) => s + parseFloat(r.total||0), 0))"></span>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="button" @click="budgetsPengeluaran.push({keterangan:'',kuantitas:0,satuan:'',harga_satuan:0,total:0})" class="btn-secondary mt-2 text-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Baris
                </button>
            </div>

            {{-- Sumber Dana --}}
            <div>
                <h3 class="font-semibold mb-3" style="color:var(--ink-900); font-size:15px;">3. Sumber Dana</h3>
                <table class="repeater-table">
                    <thead>
                        <tr><th style="width:40px;">No.</th><th>Keterangan <span style="color:var(--telkom-red)">*</span></th><th style="width:160px;">Total (Rp) <span style="color:var(--telkom-red)">*</span></th><th style="width:40px;"></th></tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, idx) in budgetsSumberDana" :key="idx">
                            <tr>
                                <td class="text-center text-sm" x-text="idx + 1"></td>
                                <td><input type="text" :name="'budgets_sumber_dana[' + idx + '][keterangan]'"
                                           x-model="budgetsSumberDana[idx].keterangan" required class="form-input"></td>
                                <td><input type="number" :name="'budgets_sumber_dana[' + idx + '][total]'"
                                           x-model="budgetsSumberDana[idx].total" required class="form-input text-right" min="0"></td>
                                <td>
                                    <button type="button" @click="budgetsSumberDana.splice(idx,1)"
                                            x-show="budgetsSumberDana.length > 1"
                                            class="p-1 hover:bg-red-50 rounded" style="color:var(--danger);">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <tr>
                            <td colspan="2" class="text-right font-semibold text-sm pr-4">Total Sumber Dana</td>
                            <td class="text-right font-semibold text-sm">
                                Rp <span x-text="formatRp(budgetsSumberDana.reduce((s,r) => s + parseFloat(r.total||0), 0))"></span>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" @click="budgetsSumberDana.push({keterangan:'',total:0})" class="btn-secondary mt-2 text-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Baris
                </button>
            </div>
        </div>

        {{-- ========== STEP 10: Penutup & Pengesahan ========== --}}
        <div x-show="currentStep === 10" class="card">
            <h2 class="section-heading">Penutup & Lembar Pengesahan</h2>

            <div class="mb-6">
                <label class="form-label">Penutup <span style="color:var(--telkom-red)">*</span></label>
                <textarea name="penutup" rows="6" class="form-input" required data-rich
                          x-model="data.penutup"></textarea>
            </div>

            <h3 class="font-semibold mb-4" style="font-size:15px; color:var(--ink-900);">Lembar Pengesahan: Data Penandatangan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-lg border" style="border-color:var(--ink-300);">
                    <p class="text-sm font-semibold mb-3" style="color:var(--ink-700);">President UKM / Organisasi</p>
                    <label class="form-label">Nama <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="president_ukm_nama" class="form-input mb-2" required
                           value="{{ old('president_ukm_nama', $model?->president_ukm_nama) }}">
                    <label class="form-label">NIM <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="president_ukm_nim" class="form-input" required
                           value="{{ old('president_ukm_nim', $model?->president_ukm_nim) }}"
                           inputmode="numeric"
                           data-fv-digits-min="8" data-fv-digits-max="20"
                           data-fv-message="NIM harus berupa angka dan minimal 8 digit.">
                </div>
                <div class="p-4 rounded-lg border" style="border-color:var(--ink-300);">
                    <p class="text-sm font-semibold mb-3" style="color:var(--ink-700);">Sekretaris</p>
                    <label class="form-label">Nama <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="sekretaris_nama" class="form-input mb-2" required
                           value="{{ old('sekretaris_nama', $model?->sekretaris_nama) }}">
                    <label class="form-label">NIM <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="sekretaris_nim" class="form-input" required
                           value="{{ old('sekretaris_nim', $model?->sekretaris_nim) }}"
                           inputmode="numeric"
                           data-fv-digits-min="8" data-fv-digits-max="20"
                           data-fv-message="NIM harus berupa angka dan minimal 8 digit.">
                </div>
                <div class="p-4 rounded-lg border" style="border-color:var(--ink-300);">
                    <p class="text-sm font-semibold mb-3" style="color:var(--ink-700);">Ketua Pelaksana</p>
                    <label class="form-label">Nama <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="ketua_pelaksana_nama" class="form-input mb-2" required
                           value="{{ old('ketua_pelaksana_nama', $model?->ketua_pelaksana_nama) }}">
                    <label class="form-label">NIM <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="ketua_pelaksana_nim" class="form-input" required
                           value="{{ old('ketua_pelaksana_nim', $model?->ketua_pelaksana_nim) }}"
                           inputmode="numeric"
                           data-fv-digits-min="8" data-fv-digits-max="20"
                           data-fv-message="NIM harus berupa angka dan minimal 8 digit.">
                </div>
                <div class="p-4 rounded-lg border" style="border-color:var(--ink-300);">
                    <p class="text-sm font-semibold mb-3" style="color:var(--ink-700);">Pembina I</p>
                    <label class="form-label">Nama <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="pembina_nama" class="form-input mb-2" required
                           value="{{ old('pembina_nama', $model?->pembina_nama) }}">
                    <label class="form-label">NIP <span style="color:var(--telkom-red)">*</span></label>
                    <input type="text" name="pembina_nip" class="form-input" required
                           value="{{ old('pembina_nip', $model?->pembina_nip) }}"
                           inputmode="numeric"
                           data-fv-digits-min="8" data-fv-digits-max="20"
                           data-fv-message="NIP harus berupa angka dan minimal 8 digit.">
                </div>
                <div class="p-4 rounded-lg border" style="border-color:var(--ink-300);">
                    <p class="text-sm font-semibold mb-3" style="color:var(--ink-700);">Pembina II <span style="font-weight:400; color:var(--ink-500);">(opsional)</span></p>
                    <label class="form-label">Nama</label>
                    <input type="text" name="pembina_2_nama" class="form-input mb-2"
                           value="{{ old('pembina_2_nama', $model?->pembina_2_nama) }}">
                    <label class="form-label">NIP</label>
                    <input type="text" name="pembina_2_nip" class="form-input"
                           value="{{ old('pembina_2_nip', $model?->pembina_2_nip) }}"
                           inputmode="numeric"
                           data-fv-digits-min="8" data-fv-digits-max="20"
                           data-fv-message="NIP harus berupa angka dan minimal 8 digit.">
                </div>
                <div class="md:col-span-2 p-4 rounded-lg border" style="border-color:var(--ink-300); background:var(--surface-alt);">
                    <p class="text-sm font-semibold mb-3" style="color:var(--ink-700);">Direktur Kemahasiswaan (dapat diubah jika perlu)</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nama Direktur <span style="color:var(--telkom-red)">*</span></label>
                            <input type="text" name="direktur_nama" class="form-input" required
                                   value="{{ old('direktur_nama', $model?->direktur_nama ?? 'Dr. Maulana Rezi Ramadhana, S.Psi., M.Psi., Psikolog') }}">
                        </div>
                        <div>
                            <label class="form-label">NIP Direktur <span style="color:var(--telkom-red)">*</span></label>
                            <input type="text" name="direktur_nip" class="form-input" required
                                   value="{{ old('direktur_nip', $model?->direktur_nip ?? '20820005') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== STEP 11: Lampiran ========== --}}
        <div x-show="currentStep === 11" class="card">
            <h2 class="section-heading">Lampiran</h2>
            <div class="mb-4 p-4 rounded-lg" style="background:var(--surface-alt); border:1px solid var(--ink-300);">
                <p class="text-sm flex items-start gap-2" style="color:var(--ink-700);">
                    <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5" style="color:var(--ink-500);"></i>
                    <span>
                        Gabungkan seluruh lampiran (foto dokumentasi, nota, bukti pembayaran, poster, dll)
                        menjadi <strong>satu berkas PDF</strong>, lalu upload di sini. Halaman PDF lampiran akan
                        digabung otomatis di akhir dokumen hasil generate. Format: <strong>PDF</strong>. Maks 50MB.
                    </span>
                </p>
            </div>

            @php $lampiranSaatIni = $isEdit ? $model->lampiranAttachments->first() : null; @endphp
            @if($lampiranSaatIni)
                <div class="mb-5" x-data="{ deleted: false }">
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

            <div class="mb-2" x-data="{ name: '' }">
                <label class="form-label">{{ $lampiranSaatIni ? 'Ganti Lampiran (PDF)' : 'Upload Lampiran (PDF)' }}</label>
                <input type="file" name="lampiran_pdf" accept="application/pdf"
                       class="form-input" style="padding:8px;"
                       @change="name = $event.target.files.length ? $event.target.files[0].name : ''">
                <p class="text-sm mt-2" x-show="name" x-cloak style="color:var(--ink-700);">
                    <i data-lucide="file-text" class="w-4 h-4 inline" style="color:var(--telkom-red);"></i>
                    <span x-text="name"></span>
                </p>
            </div>
        </div>

        {{-- ========== STEP 12: Review & Generate ========== --}}
        <div x-show="currentStep === 12" class="card">
            <h2 class="section-heading">Review & {{ $isEdit ? 'Simpan' : 'Generate PDF' }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="p-4 rounded-xl" style="background:var(--surface-alt); border:1px solid var(--ink-300);">
                    <h3 class="font-semibold mb-3 text-sm" style="color:var(--ink-500); text-transform:uppercase; letter-spacing:0.5px;">Informasi Kegiatan</h3>
                    <div class="space-y-2 text-sm">
                        <div>
                            <span style="color:var(--ink-500);">Nama Kegiatan</span>
                            <p class="font-semibold" style="color:var(--ink-900);" x-text="data.nama_kegiatan || '-'"></p>
                        </div>
                        <div>
                            <span style="color:var(--ink-500);">Penyelenggara</span>
                            <p class="font-semibold" style="color:var(--ink-900);" x-text="data.penyelenggara || '-'"></p>
                        </div>
                        <div>
                            <span style="color:var(--ink-500);">Tanggal</span>
                            <p class="font-semibold" style="color:var(--ink-900);" x-text="(data.tanggal_mulai || '-') + ' s.d. ' + (data.tanggal_selesai || '-')"></p>
                        </div>
                        <div>
                            <span style="color:var(--ink-500);">Tempat</span>
                            <p class="font-semibold" style="color:var(--ink-900);" x-text="data.tempat_kegiatan || '-'"></p>
                        </div>
                    </div>
                </div>
                <div class="p-4 rounded-xl" style="background:var(--surface-alt); border:1px solid var(--ink-300);">
                    <h3 class="font-semibold mb-3 text-sm" style="color:var(--ink-500); text-transform:uppercase; letter-spacing:0.5px;">Ringkasan Dokumen</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span style="color:var(--ink-500);">Anggota Kepanitiaan</span>
                            <span class="font-semibold" x-text="committees.filter(r => r.nama).length + ' orang'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color:var(--ink-500);">Baris Rundown</span>
                            <span class="font-semibold" x-text="rundowns.filter(r => r.aktivitas).length + ' item'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color:var(--ink-500);">Total Pengeluaran</span>
                            <span class="font-semibold">
                                Rp <span x-text="formatRp(budgetsPengeluaran.reduce((s,r) => s + parseFloat(r.total||0), 0))"></span>
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color:var(--ink-500);">Lampiran PDF</span>
                            <span class="font-semibold" x-text="document.querySelector('input[name=lampiran_pdf]')?.files?.length ? 'Terpilih' : 'Tidak ada / sesuai sebelumnya'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color:var(--ink-500);">Logo Organisasi</span>
                            <span class="font-semibold" x-text="logoPreview ? 'Tersedia' : 'Tidak ada'"></span>
                        </div>
                    </div>
                    <div x-show="logoPreview" class="mt-3">
                        <img :src="logoPreview" style="max-height:64px; object-fit:contain;">
                    </div>
                </div>
            </div>

            <div class="p-4 mb-6 rounded-lg" style="background:var(--surface-alt); border:1px solid var(--ink-300);">
                <p class="text-sm flex items-start gap-2" style="color:var(--ink-700);">
                    <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5" style="color:var(--ink-500);"></i>
                    <span>
                    @if($isEdit)
                        Setelah disimpan, perubahan akan tersimpan dan status proposal kembali menjadi Draf. Generate ulang PDF untuk mengunduh versi terbaru.
                    @else
                        Setelah submit, proposal akan disimpan. Pastikan semua data sudah benar sebelum melanjutkan.
                    @endif
                    </span>
                </p>
            </div>

            <x-btn-generate-pdf :label="$isEdit ? 'Simpan Perubahan' : 'Simpan Proposal'" />
        </div>

        {{-- Navigasi --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:justify-between mt-6">
            <button type="button" @click="prevStep()"
                    x-show="currentStep > 1"
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
function proposalForm(seed) {
    return {
        currentStep: 1,
        totalSteps: 12,
        stepLabels: [
            'Identitas Kegiatan', 'Cover & Logo', 'Narasi Kegiatan',
            'Materi & Narasumber', 'Susunan Acara', 'Analisis Risiko',
            'Monitoring & Evaluasi', 'Struktur Kepanitiaan', 'Rencana Anggaran',
            'Penutup & Pengesahan', 'Lampiran', 'Review & Generate'
        ],
        data: {
            nama_kegiatan: '', tema_kegiatan: '', penyelenggara: '', afiliasi: '',
            tanggal_mulai: '', tanggal_selesai: '', waktu_mulai: '', waktu_selesai: '',
            tempat_kegiatan: '', kota: 'BANDUNG', tahun: new Date().getFullYear(),
            latar_belakang: '', sasaran_kegiatan: '', bentuk_kegiatan: '',
            narasumber_kegiatan: '', monitoring_evaluasi: '', penutup: ''
        },
        logoPreview: null,
        logoIsExisting: false,
        tujuanList: [''],
        materiList: [{ judul: '', deskripsi: '' }],
        rundowns: [{ waktu_mulai: '', waktu_selesai: '', durasi_menit: 0, aktivitas: '', _tpMulaiOpen: false, _tpSelesaiOpen: false }],
        risks: [{ uraian_kegiatan: '', identifikasi_bahaya: '', peluang: '', akibat: '', tingkat_risiko: '', pengendalian_risiko: '', penanggung_jawab: '' }],
        committees: [{ jabatan: '', nama: '', jurusan: '', tahun_angkatan: '', fakultas: '', nim: '' }],
        budgetsPemasukan: [{ keterangan: '', total: 0 }],
        budgetsPengeluaran: [{ keterangan: '', kuantitas: 0, satuan: '', harga_satuan: 0, total: 0 }],
        budgetsSumberDana: [{ keterangan: '', total: 0 }],
        lampiranPreviews: [],
        showRiskGuide: false,
        tpHours: Array.from({ length: 24 }, (_, i) => String(i).padStart(2,'0')),
        tpMinutes: Array.from({ length: 12 }, (_, i) => String(i*5).padStart(2,'0')),

        init() {
            // Seed dari server untuk mode edit (prefill semua field & repeater).
            if (seed) {
                if (seed.data) Object.assign(this.data, seed.data);
                if (seed.tujuanList && seed.tujuanList.length) this.tujuanList = seed.tujuanList;
                if (seed.materiList && seed.materiList.length) this.materiList = seed.materiList;
                if (seed.rundowns && seed.rundowns.length) this.rundowns = seed.rundowns;
                if (seed.risks && seed.risks.length) this.risks = seed.risks;
                if (seed.committees && seed.committees.length) this.committees = seed.committees;
                if (seed.budgetsPemasukan && seed.budgetsPemasukan.length) this.budgetsPemasukan = seed.budgetsPemasukan;
                if (seed.budgetsPengeluaran && seed.budgetsPengeluaran.length) this.budgetsPengeluaran = seed.budgetsPengeluaran;
                if (seed.budgetsSumberDana && seed.budgetsSumberDana.length) this.budgetsSumberDana = seed.budgetsSumberDana;
                if (seed.logoUrl) { this.logoPreview = seed.logoUrl; this.logoIsExisting = true; }
            }

            // Re-init lucide + fade konten saat pindah langkah
            this.$watch('currentStep', () => {
                this.$nextTick(() => {
                    if (window.lucide) lucide.createIcons();
                    const form = document.getElementById('proposalForm');
                    if (form) { form.classList.remove('step-pane'); void form.offsetWidth; form.classList.add('step-pane'); }
                });
            });

            // Validasi seluruh form sebelum submit; cegah submit & lompat ke langkah
            // yang bermasalah bila ada field tidak valid (mis. NIM/NIP salah format).
            const formEl = document.getElementById('proposalForm');
            if (formEl) {
                formEl.addEventListener('submit', (e) => {
                    if (!window.FormValidator) return;
                    const invalid = window.FormValidator.validateAll(formEl)
                        || this.checkTanggal();
                    if (invalid) {
                        e.preventDefault();
                        const step = this.stepOfField(invalid);
                        if (step) this.currentStep = step;
                        window.scrollTo(0, 0);
                        this.$nextTick(() => invalid.focus({ preventScroll: true }));
                    }
                });
            }
        },

        prevStep() { if (this.currentStep > 1) this.currentStep--; window.scrollTo(0, 0); },
        nextStep() {
            if (!this.validateStep()) return;
            if (this.currentStep < this.totalSteps) this.currentStep++;
            window.scrollTo(0, 0);
        },

        validateStep() {
            const form = document.getElementById('proposalForm');
            // Validasi field wajib yang terlihat pada langkah aktif (inline, tanpa alert).
            let ok = window.FormValidator ? window.FormValidator.validateScope(form) : true;

            // Aturan tambahan: tanggal selesai tidak boleh sebelum tanggal mulai.
            if (this.currentStep === 1 && this.checkTanggal()) {
                ok = false;
            }
            return ok;
        },

        // Cari nomor langkah (currentStep === N) tempat field berada.
        stepOfField(field) {
            let el = field.closest ? field.closest('[x-show]') : null;
            while (el) {
                const m = (el.getAttribute('x-show') || '').match(/currentStep\s*===\s*(\d+)/);
                if (m) return parseInt(m[1], 10);
                el = el.parentElement ? el.parentElement.closest('[x-show]') : null;
            }
            return null;
        },

        // Aturan lintas-field: tanggal selesai tidak boleh sebelum tanggal mulai.
        // Mengembalikan elemen field bermasalah, atau null bila valid.
        checkTanggal() {
            const a = this.data.tanggal_mulai, b = this.data.tanggal_selesai;
            const trigger = document.querySelector('#proposalForm .dp-trigger');
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

        previewLogo(event) {
            const file = event.target.files[0];
            if (file) { this.logoPreview = URL.createObjectURL(file); this.logoIsExisting = false; }
        },

        addTujuan() { this.tujuanList.push(''); },
        removeTujuan(idx) { if (this.tujuanList.length > 1) this.tujuanList.splice(idx, 1); },

        addMateri() { this.materiList.push({ judul: '', deskripsi: '' }); },
        removeMateri(idx) { if (this.materiList.length > 1) this.materiList.splice(idx, 1); },

        addRundown() { this.rundowns.push({ waktu_mulai: '', waktu_selesai: '', durasi_menit: 0, aktivitas: '', _tpMulaiOpen: false, _tpSelesaiOpen: false }); },
        removeRundown(idx) { if (this.rundowns.length > 1) this.rundowns.splice(idx, 1); },
        calcDurasi(idx) {
            const r = this.rundowns[idx];
            if (r.waktu_mulai && r.waktu_selesai) {
                const [h1, m1] = r.waktu_mulai.split(':').map(Number);
                const [h2, m2] = r.waktu_selesai.split(':').map(Number);
                const diff = (h2 * 60 + m2) - (h1 * 60 + m1);
                r.durasi_menit = diff > 0 ? diff : 0;
            }
        },

        pickRdTime(idx, field, part, val) {
            const r = this.rundowns[idx];
            const key = field === 'mulai' ? 'waktu_mulai' : 'waktu_selesai';
            const cur = (r[key] || '00:00').split(':');
            r[key] = part === 'h' ? (val + ':' + (cur[1] || '00')) : ((cur[0] || '00') + ':' + val);
            if (r.waktu_mulai && r.waktu_selesai) this.calcDurasi(idx);
        },

        addRisk() { this.risks.push({ uraian_kegiatan:'', identifikasi_bahaya:'', peluang:'', akibat:'', tingkat_risiko:'', pengendalian_risiko:'', penanggung_jawab:'' }); },
        removeRisk(idx) { if (this.risks.length > 1) this.risks.splice(idx, 1); },
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

        addCommittee() { this.committees.push({ jabatan:'', nama:'', jurusan:'', tahun_angkatan:'', fakultas:'', nim:'' }); },
        removeCommittee(idx) { if (this.committees.length > 1) this.committees.splice(idx, 1); },

        calcBudget(idx) {
            const row = this.budgetsPengeluaran[idx];
            const qty = parseFloat(row.kuantitas) || 0;
            const harga = parseFloat(row.harga_satuan) || 0;
            row.total = qty * harga;
        },

        addLampirans(event) {
            // Satu <input type="file"> tidak bisa mengakumulasi file lintas pemilihan:
            // setiap pilih ulang, FileList-nya ditimpa. Maka kita kelola DataTransfer
            // sendiri lalu pasang kembali ke input agar file lama tidak hilang saat
            // user menambah file baru (mis. menambah PDF setelah memilih gambar).
            const input = event.target;
            if (!this._lampiranDt) this._lampiranDt = new DataTransfer();

            Array.from(input.files).forEach(file => this._lampiranDt.items.add(file));
            input.files = this._lampiranDt.files;

            this.syncLampiranPreviews();
        },

        removeLampiran(idx) {
            if (!this._lampiranDt) return;

            const dt = new DataTransfer();
            Array.from(this._lampiranDt.files).forEach((file, i) => {
                if (i !== idx) dt.items.add(file);
            });
            this._lampiranDt = dt;
            this.$refs.lampiranInput.files = dt.files;

            this.syncLampiranPreviews();
        },

        syncLampiranPreviews() {
            // Bebaskan object URL lama agar tidak bocor memori.
            this.lampiranPreviews.forEach(p => p.preview && URL.revokeObjectURL(p.preview));

            this.lampiranPreviews = Array.from(this._lampiranDt.files).map(file => ({
                name: file.name,
                type: file.type,
                preview: file.type.startsWith('image/') ? URL.createObjectURL(file) : null,
            }));
        },

        formatRp(val) {
            return Number(val || 0).toLocaleString('id-ID');
        },
    };
}
</script>
@endpush

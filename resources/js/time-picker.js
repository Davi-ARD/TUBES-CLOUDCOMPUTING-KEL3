/**
 * Time picker (Alpine) bergaya "jam" untuk semua field waktu.
 *
 * UX: tombol pemicu (ikon jam + "HH:MM") membuka popover berisi dua kolom
 * gulir — Jam (00-23) & Menit (00-59). Lebih profesional dibanding
 * <input type="time"> bawaan browser.
 *
 * Popover memakai position:fixed yang diposisikan via JS agar TIDAK terpotong
 * oleh kontainer overflow (mis. tabel rundown dengan overflow-x:auto).
 *
 * PENTING: menerima objek reaktif `store` (data form induk / baris repeater)
 * BY REFERENCE lalu menulis `store[key]` dalam format "HH:MM" via hidden input
 * bernama sama. Nilai submit & hasil generate PDF TIDAK berubah. `onChange`
 * opsional dipanggil setelah perubahan (mis. menghitung ulang durasi rundown).
 */
const pad = (n) => String(n).padStart(2, '0');

export default function timePicker(store, key, onChange = null) {
    return {
        store,
        key,
        onChange,
        open: false,
        coords: null,
        hours: Array.from({ length: 24 }, (_, i) => pad(i)),
        minutes: Array.from({ length: 12 }, (_, i) => pad(i * 5)),

        get value() { return (this.store && this.store[this.key]) || ''; },
        get hh() { const v = this.value.split(':'); return v.length === 2 ? v[0] : ''; },
        get mm() { const v = this.value.split(':'); return v.length === 2 ? v[1] : ''; },

        label() { return this.value || 'Pilih waktu'; },
        isHour(h) { return this.hh === h; },
        isMinute(m) { return this.mm === m; },

        change() { if (typeof this.onChange === 'function') this.onChange(); },
        pickHour(h) { this.store[this.key] = `${h}:${this.mm || '00'}`; this.change(); },
        pickMinute(m) { this.store[this.key] = `${this.hh || '00'}:${m}`; this.change(); },

        popStyle() {
            if (!this.open) return '';
            if (!this.coords) return 'position:fixed; visibility:hidden; z-index:80;';
            return `position:fixed; top:${this.coords.top}px; left:${this.coords.left}px; z-index:80;`;
        },

        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.coords = null;
                this.$nextTick(() => { this.reposition(); this.scrollToActive(); });
            }
        },

        // Hitung posisi fixed dari tombol pemicu; balik ke atas bila ruang bawah kurang.
        reposition() {
            const t = this.$el.querySelector('.tp-trigger');
            if (!t) return;
            const r = t.getBoundingClientRect();
            const w = 200, h = 214;
            let left = Math.min(r.left, window.innerWidth - w - 8);
            left = Math.max(8, left);
            let top = r.bottom + 6;
            if (top + h > window.innerHeight - 8 && r.top - h - 6 > 8) top = r.top - h - 6;
            this.coords = { top, left };
        },

        // Gulir kolom agar nilai terpilih terlihat di tengah saat dibuka.
        scrollToActive() {
            this.$el.querySelectorAll('.tp-col').forEach((col) => {
                const active = col.querySelector('.is-active');
                if (active) col.scrollTop = active.offsetTop - col.clientHeight / 2 + active.clientHeight / 2;
            });
        },
    };
}

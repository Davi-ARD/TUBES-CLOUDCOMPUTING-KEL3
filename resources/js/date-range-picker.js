/**
 * Date-range picker (Alpine) untuk field Tanggal Mulai & Tanggal Selesai.
 *
 * UX: tombol pemicu -> popover berisi dropdown Tahun & Bulan + grid kalender
 * untuk memilih rentang (from-to) + tombol Hapus/Terapkan. Setara dengan
 * komponen range date picker bergaya shadcn, namun memakai Alpine.
 *
 * PENTING: komponen menerima objek reaktif `store` (yaitu `data` dari form
 * induk) BY REFERENCE, lalu menulis langsung ke `store.tanggal_mulai` dan
 * `store.tanggal_selesai` dalam format "YYYY-MM-DD". Dengan begitu nilai yang
 * disubmit dan hasil generate PDF TIDAK berubah sama sekali (sama seperti
 * input <input type="date"> sebelumnya).
 */

const ID_MONTHS = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
];
const ID_DOW = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

const pad = (n) => String(n).padStart(2, '0');
const toStr = (y, m, d) => `${y}-${pad(m + 1)}-${pad(d)}`; // m: 0-based

function parseDate(str) {
    if (!str) return null;
    const p = String(str).split('-');
    if (p.length !== 3) return null;
    const y = +p[0], m = +p[1] - 1, d = +p[2];
    if ([y, m, d].some(Number.isNaN)) return null;
    return { y, m, d };
}

function formatId(str) {
    const p = parseDate(str);
    return p ? `${p.d} ${ID_MONTHS[p.m]} ${p.y}` : '';
}

export default function dateRangePicker(store) {
    const now = new Date();
    return {
        store,
        open: false,
        mOpen: false,
        yOpen: false,
        months: ID_MONTHS,
        dow: ID_DOW,
        viewYear: now.getFullYear(),
        viewMonth: now.getMonth(),

        init() {
            const f = parseDate(this.store?.tanggal_mulai);
            this.viewYear = f ? f.y : now.getFullYear();
            this.viewMonth = f ? f.m : now.getMonth();
        },

        get years() {
            const start = now.getFullYear() - 20;
            return Array.from({ length: 41 }, (_, i) => start + i);
        },

        get from() { return this.store?.tanggal_mulai || ''; },
        get to() { return this.store?.tanggal_selesai || ''; },

        label() {
            if (!this.from) return 'Pilih rentang tanggal';
            if (!this.to) return formatId(this.from);
            return `${formatId(this.from)} - ${formatId(this.to)}`;
        },

        toggle() {
            if (!this.open) this.init();
            this.open = !this.open;
        },

        prevMonth() {
            if (this.viewMonth === 0) { this.viewMonth = 11; this.viewYear--; }
            else { this.viewMonth--; }
        },
        nextMonth() {
            if (this.viewMonth === 11) { this.viewMonth = 0; this.viewYear++; }
            else { this.viewMonth++; }
        },

        // Sel kalender bulan aktif: leading null untuk hari kosong, lalu 1..n.
        get cells() {
            const startDow = new Date(this.viewYear, this.viewMonth, 1).getDay();
            const daysInMonth = new Date(this.viewYear, this.viewMonth + 1, 0).getDate();
            const arr = [];
            for (let i = 0; i < startDow; i++) arr.push(null);
            for (let d = 1; d <= daysInMonth; d++) arr.push(d);
            return arr;
        },

        dayStr(d) { return toStr(this.viewYear, this.viewMonth, d); },
        isFrom(d) { return this.from === this.dayStr(d); },
        isTo(d) { return this.to === this.dayStr(d); },
        isEnd(d) { return this.isFrom(d) || this.isTo(d); },
        isInRange(d) {
            if (!this.from || !this.to) return false;
            const s = this.dayStr(d);
            return s >= this.from && s <= this.to;
        },
        isToday(d) {
            return this.dayStr(d) === toStr(now.getFullYear(), now.getMonth(), now.getDate());
        },

        select(d) {
            const s = this.dayStr(d);
            const a = this.from;
            const b = this.to;
            if (!a || (a && b)) {
                // Mulai rentang baru.
                this.store.tanggal_mulai = s;
                this.store.tanggal_selesai = '';
            } else if (s >= a) {
                this.store.tanggal_selesai = s;
            } else {
                // Klik sebelum 'from' -> jadikan 'from' baru.
                this.store.tanggal_mulai = s;
                this.store.tanggal_selesai = '';
            }
            this.clearErr();
        },

        clear() {
            this.store.tanggal_mulai = '';
            this.store.tanggal_selesai = '';
            this.clearErr();
        },

        apply() {
            // Bila hanya 'from' yang dipilih, anggap kegiatan satu hari.
            if (this.from && !this.to) this.store.tanggal_selesai = this.from;
            this.open = false;
        },

        // Hapus pesan error inline pada tombol pemicu saat user memilih tanggal.
        clearErr() {
            if (!window.FormValidator) return;
            const t = this.$el.querySelector('.dp-trigger');
            if (t) window.FormValidator.clearError(t);
        },
    };
}

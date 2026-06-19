import './bootstrap';
import './form-validator';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import { createIcons, icons } from 'lucide';
import { initRichTextEditors } from './rich-text';
import dateRangePicker from './date-range-picker';
import timePicker from './time-picker';

Alpine.plugin(collapse);
Alpine.data('dateRangePicker', dateRangePicker);
Alpine.data('timePicker', timePicker);
window.Alpine = Alpine;

// Inisialisasi editor rich text pada field paragraf naratif.
// PENTING: listener didaftarkan SEBELUM Alpine.start(), karena Alpine v3
// memanggil `alpine:initialized` secara sinkron di dalam start() — listener
// yang didaftarkan setelahnya tidak akan pernah terpicu.
document.addEventListener('alpine:initialized', () => initRichTextEditors());

Alpine.start();

// Jaring pengaman bila event sudah terlewat di sebagian kondisi: panggil
// langsung (idempoten berkat penanda data-rich-init).
initRichTextEditors();

// Render ikon <i data-lucide> di dalam sebuah root (default: seluruh dokumen).
function renderIcons(root = document) {
    try {
        createIcons({ icons, nameAttr: 'data-lucide', root });
    } catch (e) { /* noop */ }
}

document.addEventListener('DOMContentLoaded', () => renderIcons());

// Ikon di dalam repeater Alpine (baris rundown, risiko, panitia, dll) disisipkan
// SETELAH createIcons awal berjalan, sehingga `<i data-lucide>` tidak pernah
// dikonversi menjadi SVG — tombol hapus jadi "hilang" saat menambah baris.
// MutationObserver berikut mengonversi HANYA subtree yang baru ditambahkan
// (bukan seluruh dokumen), agar tambah/hapus baris tetap ringan & cepat.
const iconObserver = new MutationObserver((mutations) => {
    for (const m of mutations) {
        for (const node of m.addedNodes) {
            if (node.nodeType !== 1 || node.tagName === 'svg') continue;
            if (node.hasAttribute('data-lucide')) {
                renderIcons(node.parentNode || node);
            } else if (node.querySelector('[data-lucide]')) {
                renderIcons(node);
            }
        }
    }
});

document.addEventListener('DOMContentLoaded', () => {
    iconObserver.observe(document.body, { childList: true, subtree: true });
});

/**
 * Editor rich text ringan untuk field paragraf naratif Proposal & LPJ.
 *
 * Implementasi memakai `contenteditable` + execCommand — tanpa dependensi
 * eksternal, sehingga ringan (beberapa KB) dan selalu tampil dengan andal.
 * Toolbar dibatasi pada format yang aman & sederhana untuk dompdf:
 *   Bold, Italic, Underline, Bullet list, Numbered list.
 *
 * Prinsip dompdf-safe & keamanan:
 *   - Output disanitasi di sisi klien menjadi whitelist tag sederhana
 *     (p, br, strong/b, em/i, u, ul, ol, li, a) — selaras dengan whitelist
 *     Purifier 'richtext' dan App\Support\RichText.
 *   - Konten tetap disanitasi ulang di server sebelum dicetak dengan {!! !!}.
 *
 * Sinkronisasi: setiap perubahan ditulis balik ke <textarea> asal lalu memicu
 * event `input`, sehingga Alpine x-model, validasi wajib-isi, dan autosave draft
 * LPJ tetap melihat nilai terbaru. Textarea asli disembunyikan namun tetap
 * berada di dalam form sehingga ikut ter-submit.
 */

const ALLOWED_TAGS = new Set(['P', 'BR', 'STRONG', 'B', 'EM', 'I', 'U', 'UL', 'OL', 'LI', 'A']);
const RENAME_TAGS = { DIV: 'P' };

function escapeHtml(text) {
    return text
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

/** Serialisasi rekursif sebuah node ke HTML yang hanya berisi tag whitelist. */
function serializeNode(node) {
    if (node.nodeType === Node.TEXT_NODE) {
        return escapeHtml(node.nodeValue);
    }
    if (node.nodeType !== Node.ELEMENT_NODE) {
        return '';
    }

    let tag = node.tagName;
    if (tag === 'BR') return '<br>';

    const inner = Array.from(node.childNodes).map(serializeNode).join('');
    if (RENAME_TAGS[tag]) tag = RENAME_TAGS[tag];

    // Tag tak dikenal di-"unwrap": buang elemennya, pertahankan isi.
    if (!ALLOWED_TAGS.has(tag)) return inner;

    const lower = tag.toLowerCase();
    if (lower === 'a') {
        const href = node.getAttribute('href') || '#';
        return `<a href="${escapeHtml(href)}">${inner}</a>`;
    }
    return `<${lower}>${inner}</${lower}>`;
}

/** Bersihkan isi editor menjadi HTML whitelist; kembalikan '' bila kosong. */
function cleanContent(contentEl) {
    if (!contentEl.textContent.trim() && !contentEl.querySelector('img')) {
        return '';
    }
    return Array.from(contentEl.childNodes).map(serializeNode).join('').trim();
}

const ICONS = {
    bold: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M6 4h8a4 4 0 0 1 0 8H6z"/><path d="M6 12h9a4 4 0 0 1 0 8H6z"/></svg>',
    italic: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg>',
    underline: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3v7a6 6 0 0 0 12 0V3"/><line x1="4" y1="21" x2="20" y2="21"/></svg>',
    bullet: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><circle cx="3.5" cy="6" r="1.2" fill="currentColor" stroke="none"/><circle cx="3.5" cy="12" r="1.2" fill="currentColor" stroke="none"/><circle cx="3.5" cy="18" r="1.2" fill="currentColor" stroke="none"/></svg>',
    numbered: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4" stroke-width="1.6"/><path d="M3.5 16.5a1 1 0 0 1 1.5.8c0 .7-1.5 1.2-1.5 2.2H5" stroke-width="1.6"/></svg>',
};

const TOOLBAR = [
    { cmd: 'bold', icon: ICONS.bold, title: 'Tebal (Ctrl+B)' },
    { cmd: 'italic', icon: ICONS.italic, title: 'Miring (Ctrl+I)' },
    { cmd: 'underline', icon: ICONS.underline, title: 'Garis bawah (Ctrl+U)' },
    { sep: true },
    { cmd: 'insertUnorderedList', icon: ICONS.bullet, title: 'Daftar poin' },
    { cmd: 'insertOrderedList', icon: ICONS.numbered, title: 'Daftar bernomor' },
];

function buildEditor(textarea) {
    const wrapper = document.createElement('div');
    wrapper.className = 'rt-editor';

    const toolbar = document.createElement('div');
    toolbar.className = 'rt-toolbar';

    const content = document.createElement('div');
    content.className = 'rt-content';
    content.setAttribute('contenteditable', 'true');
    content.setAttribute('role', 'textbox');
    content.setAttribute('aria-multiline', 'true');
    if (textarea.placeholder) content.dataset.placeholder = textarea.placeholder;

    const buttons = [];
    TOOLBAR.forEach((item) => {
        if (item.sep) {
            const sep = document.createElement('span');
            sep.className = 'rt-sep';
            toolbar.appendChild(sep);
            return;
        }
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'rt-btn';
        btn.innerHTML = item.icon;
        btn.title = item.title;
        btn.setAttribute('aria-label', item.title);
        btn.dataset.cmd = item.cmd;
        // mousedown agar seleksi di editor tidak hilang saat tombol diklik.
        btn.addEventListener('mousedown', (e) => {
            e.preventDefault();
            content.focus();
            document.execCommand(item.cmd, false, null);
            sync();
            refreshStates();
        });
        toolbar.appendChild(btn);
        buttons.push(btn);
    });

    wrapper.appendChild(toolbar);
    wrapper.appendChild(content);
    textarea.parentNode.insertBefore(wrapper, textarea);
    textarea.classList.add('rt-source');

    // Isi awal dari textarea (mode edit / restore draft sudah set value lebih dulu).
    content.innerHTML = textarea.value || '';

    function sync() {
        const html = cleanContent(content);
        if (textarea.value !== html) {
            textarea.value = html;
            textarea.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    function refreshStates() {
        buttons.forEach((btn) => {
            let active = false;
            try { active = document.queryCommandState(btn.dataset.cmd); } catch (e) { /* noop */ }
            btn.classList.toggle('is-active', active);
        });
    }

    content.addEventListener('input', sync);
    content.addEventListener('blur', sync);
    content.addEventListener('keyup', refreshStates);
    content.addEventListener('mouseup', refreshStates);
    document.addEventListener('selectionchange', () => {
        if (document.activeElement === content) refreshStates();
    });

    // Bila Alpine mengubah textarea secara reaktif (mis. restore draft setelah
    // editor dibuat), selaraskan tampilan editor sekali.
    textarea.addEventListener('rt:refresh', () => {
        content.innerHTML = textarea.value || '';
    });

    return content;
}

export function initRichTextEditors(root = document) {
    root.querySelectorAll('textarea[data-rich]').forEach((textarea) => {
        if (textarea.dataset.richInit) return;
        textarea.dataset.richInit = '1';

        try {
            document.execCommand('defaultParagraphSeparator', false, 'p');
        } catch (e) { /* sebagian browser tidak mendukung; abaikan */ }

        buildEditor(textarea);
    });
}

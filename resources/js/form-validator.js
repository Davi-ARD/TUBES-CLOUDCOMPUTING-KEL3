/**
 * Validasi form client-side kustom (pengganti tooltip bawaan browser).
 *
 * - Bekerja pada field dengan atribut `required` atau `data-required="true"`.
 * - Menyisipkan pesan error inline (ikon + teks merah) di bawah field.
 * - Pesan error otomatis hilang saat user mulai mengetik.
 * - Form single-page (login, register, profil) divalidasi otomatis saat submit
 *   selama memiliki atribut `novalidate`.
 * - Form multi-step (proposal, LPJ) memanggil window.FormValidator.validateScope()
 *   sendiri per-langkah.
 */
const ALERT_ICON =
    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" ' +
    'stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
    '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/>' +
    '<line x1="12" y1="16" x2="12.01" y2="16"/></svg>';

function labelFor(field) {
    if (field.dataset.label) return field.dataset.label;
    if (field.id) {
        const lbl = document.querySelector(`label[for="${CSS.escape(field.id)}"]`);
        if (lbl) return lbl.textContent.replace('*', '').trim();
    }
    if (field.placeholder) {
        return field.placeholder.replace(/^contoh\s*:?\s*/i, '').trim() || 'Kolom ini';
    }
    return 'Kolom ini';
}

// Field rich text disembunyikan (display:none) dan digantikan editor
// contenteditable (.rt-editor) di sebelumnya. Kembalikan wrapper editor agar
// validasi memakai visibilitas & posisi editor, bukan textarea tersembunyi.
function richWrapper(field) {
    if (field.dataset && field.dataset.rich !== undefined) {
        const prev = field.previousElementSibling;
        if (prev && prev.classList.contains('rt-editor')) return prev;
    }
    return null;
}

function isVisibleField(field) {
    const el = richWrapper(field) || field;
    return el.offsetParent !== null;
}

// Elemen yang dijadikan acuan penyisipan pesan error (agar tidak menimpa
// tombol toggle password yang absolute di dalam wrapper .relative).
function anchorFor(field) {
    const wrapper = richWrapper(field);
    if (wrapper) return wrapper;
    const rel = field.closest('.relative');
    if (rel && rel.contains(field)) return rel;
    return field;
}

function isEmpty(field) {
    if (field.type === 'checkbox') return !field.checked;
    if (field.type === 'radio') {
        const group = field.form ? field.form.querySelectorAll(`input[name="${CSS.escape(field.name)}"]`) : [];
        return !Array.from(group).some((r) => r.checked);
    }
    if (field.type === 'file') return field.files.length === 0;
    return !String(field.value || '').trim();
}

function isValidatable(field) {
    if (field.disabled) return false;
    if (field.type === 'hidden' || field.type === 'submit' || field.type === 'button') return false;
    return true;
}

function isRequired(field) {
    return field.required || field.dataset.required === 'true';
}

// Mengembalikan pesan error bila field tidak valid, atau null bila valid.
// Mendukung aturan format angka via atribut data-fv-digits-min / data-fv-digits-max.
function checkField(field) {
    if (!isValidatable(field)) return null;
    if (isEmpty(field)) {
        return isRequired(field) ? labelFor(field) + ' wajib diisi.' : null;
    }
    const min = field.dataset.fvDigitsMin;
    if (min) {
        const max = field.dataset.fvDigitsMax || '';
        const re = new RegExp('^\\d{' + min + ',' + max + '}$');
        if (!re.test(String(field.value).trim())) {
            return field.dataset.fvMessage || ('Harus berupa angka ' + min + (max ? '–' + max : '') + ' digit.');
        }
    }
    return null;
}

function clearError(field) {
    field.classList.remove('is-invalid');
    if (field._fvError) {
        field._fvError.remove();
        field._fvError = null;
    }
}

function setError(field, message) {
    field.classList.add('is-invalid');
    let el = field._fvError;
    if (!el) {
        el = document.createElement('div');
        el.className = 'field-error';
        el.setAttribute('role', 'alert');
        el.innerHTML = ALERT_ICON + '<span></span>';
        anchorFor(field).insertAdjacentElement('afterend', el);
        field._fvError = el;

        const clear = () => clearError(field);
        field.addEventListener('input', clear);
        field.addEventListener('change', clear);
    }
    el.querySelector('span').textContent = message;
}

const FIELD_SELECTOR = '[required], [data-required="true"], [data-fv-digits-min]';

/**
 * Validasi sekumpulan field eksplisit.
 * Mengembalikan field pertama yang tidak valid, atau null jika semua valid.
 * Opsi { focus: false } untuk melewati fokus/scroll (mis. saat field tersembunyi).
 */
function validateFields(fields, opts = {}) {
    const focus = opts.focus !== false;
    let firstInvalid = null;
    fields.forEach((field) => {
        const err = checkField(field);
        if (err) {
            setError(field, err);
            if (!firstInvalid) firstInvalid = field;
        } else {
            clearError(field);
        }
    });
    if (firstInvalid && focus) {
        // Untuk field rich text, fokuskan editornya (textarea aslinya tersembunyi).
        const wrapper = richWrapper(firstInvalid);
        const target = wrapper ? wrapper.querySelector('.rt-content') || wrapper : firstInvalid;
        target.focus({ preventScroll: true });
        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    return firstInvalid;
}

/** Validasi field wajib yang sedang terlihat di dalam sebuah scope (form / step). Mengembalikan boolean. */
function validateScope(scope) {
    if (!scope) return true;
    const fields = Array.from(scope.querySelectorAll(FIELD_SELECTOR))
        // hanya field yang terlihat (langkah aktif pada form multi-step);
        // field rich text dinilai dari wrapper editornya, bukan textarea tersembunyi.
        .filter((f) => isVisibleField(f));
    return validateFields(fields) === null;
}

/**
 * Validasi SEMUA field wajib di dalam scope tanpa memandang visibilitas
 * (dipakai saat submit form multi-step). Mengembalikan field pertama yang tidak valid, atau null.
 */
function validateAll(scope) {
    if (!scope) return null;
    const fields = Array.from(scope.querySelectorAll(FIELD_SELECTOR));
    return validateFields(fields, { focus: false });
}

/** Hapus semua pesan error inline di dalam scope. */
function clearScope(scope) {
    if (!scope) return;
    scope.querySelectorAll('.is-invalid').forEach((f) => clearError(f));
}

window.FormValidator = { validateScope, validateAll, validateFields, clearScope, setError, clearError };

// Auto-wire: form single-page dengan atribut `novalidate` divalidasi saat submit.
// Form multi-step (data-fv-manual) menangani validasinya sendiri per-langkah.
document.addEventListener(
    'submit',
    (e) => {
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;
        if (!form.hasAttribute('novalidate')) return;
        if (form.dataset.fvManual === 'true') return;
        if (!validateScope(form)) {
            e.preventDefault();
            e.stopPropagation();
        }
    },
    true
);

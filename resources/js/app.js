import './bootstrap';

/**
 * Modal handler (dipakai di layout dashboard & halaman detail reservasi pelanggan).
 */
document.addEventListener('click', (event) => {
    const opener = event.target.closest('[data-modal-target]');
    if (opener) {
        const modal = document.getElementById(opener.dataset.modalTarget);
        modal?.showModal();
    }

    const closer = event.target.closest('[data-modal-close]');
    if (closer) {
        closer.closest('dialog')?.close();
    }

    const dialog = event.target.closest('dialog');
    if (dialog && event.target === dialog) {
        dialog.close();
    }
});

/**
 * Sidebar toggle (mobile) — layout dashboard.
 */
const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
const sidebar = document.getElementById('dashboard-sidebar');
const sidebarOverlay = document.querySelector('[data-sidebar-overlay]');

sidebarToggle?.addEventListener('click', () => {
    sidebar?.classList.toggle('-translate-x-full');
    sidebarOverlay?.classList.toggle('hidden');
});

sidebarOverlay?.addEventListener('click', () => {
    sidebar?.classList.add('-translate-x-full');
    sidebarOverlay?.classList.add('hidden');
});

/**
 * Mobile nav toggle — layout public.
 */
const mobileNavToggle = document.querySelector('[data-mobile-nav-toggle]');
const mobileNav = document.getElementById('mobile-nav');

mobileNavToggle?.addEventListener('click', () => {
    const isHidden = mobileNav?.classList.toggle('hidden');
    mobileNavToggle.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
});

document.querySelectorAll('#mobile-nav a').forEach((link) => {
    link.addEventListener('click', () => {
        mobileNav?.classList.add('hidden');
        mobileNavToggle?.setAttribute('aria-expanded', 'false');
    });
});

/**
 * Back to top button — layout public.
 */
const backToTopButton = document.querySelector('[data-back-to-top]');

if (backToTopButton) {
    const toggleBackToTop = () => {
        if (window.scrollY > 400) {
            backToTopButton.classList.remove('hidden');
            backToTopButton.classList.add('flex');
        } else {
            backToTopButton.classList.add('hidden');
            backToTopButton.classList.remove('flex');
        }
    };

    window.addEventListener('scroll', toggleBackToTop, { passive: true });
    toggleBackToTop();

    backToTopButton.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

/**
 * Character counter generik untuk textarea mana pun.
 */
document.querySelectorAll('[data-char-count-target]').forEach((textarea) => {
    const key = textarea.dataset.charCountTarget;
    const counter = document.querySelector(`[data-char-counter="${key}"]`);

    if (!counter) {
        return;
    }

    const max = textarea.getAttribute('maxlength') ?? '';

    const updateCounter = () => {
        counter.textContent = max ? `${textarea.value.length} / ${max}` : `${textarea.value.length}`;
    };

    textarea.addEventListener('input', updateCounter);
    updateCounter();
});

/**
 * File upload preview (halaman Reservasi pelanggan).
 */
document.querySelectorAll('[data-file-upload]').forEach((wrapper) => {
    const input = wrapper.querySelector('[data-file-upload-input]');
    const list = wrapper.querySelector('[data-file-upload-list]');

    if (!input || !list) {
        return;
    }

    input.addEventListener('change', () => {
        list.innerHTML = '';

        Array.from(input.files ?? []).forEach((file) => {
            const item = document.createElement('li');
            item.className = 'flex items-center gap-2 rounded-lg bg-pln-slate-50 px-3 py-2 text-sm text-pln-slate-700';

            const sizeKb = Math.round(file.size / 1024);
            item.innerHTML = `
                <svg viewBox="0 0 24 24" class="h-4 w-4 shrink-0 text-pln-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 4h8l4 4v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z" />
                </svg>
                <span class="flex-1 truncate">${file.name}</span>
                <span class="shrink-0 text-xs text-pln-slate-400">${sizeKb} KB</span>
            `;

            list.appendChild(item);
        });
    });
});

/**
 * Fetch jadwal tersedia berdasarkan jenis layanan + tanggal.
 * Dipakai oleh Form Reservasi (create) dan Form Ubah Jadwal.
 * Mendukung `kecualiJadwalId` opsional (dipakai halaman Ubah Jadwal agar
 * slot yang sedang dipakai reservasi ini tidak muncul kembali di daftar).
 */
const layananOptions = document.querySelectorAll('[data-layanan-option]');
const tanggalInput = document.querySelector('[data-tanggal-input]');
const jadwalSelect = document.querySelector('[data-jadwal-select]');

if (layananOptions.length && tanggalInput && jadwalSelect) {
    const config = window.reservasiConfig ?? {};

    const getSelectedLayananId = () => {
        const checked = document.querySelector('input[name="layanan_id"]:checked');
        return checked ? checked.value : null;
    };

    const muatJadwalTersedia = async () => {
        const layananId = getSelectedLayananId();
        const tanggal = tanggalInput.value;

        jadwalSelect.innerHTML = '<option value="">Memuat jadwal...</option>';
        jadwalSelect.disabled = true;

        if (!layananId || !tanggal) {
            jadwalSelect.innerHTML = '<option value="">Pilih jenis layanan &amp; tanggal dahulu</option>';
            return;
        }

        try {
            const url = new URL(config.jadwalTersediaUrl, window.location.origin);
            url.searchParams.set('layanan_id', layananId);
            url.searchParams.set('tanggal', tanggal);

            if (config.kecualiJadwalId) {
                url.searchParams.set('kecuali_jadwal_id', config.kecualiJadwalId);
            }

            const response = await fetch(url, {
                headers: { Accept: 'application/json' },
            });

            const result = await response.json();

            jadwalSelect.innerHTML = '';
            jadwalSelect.disabled = false;

            if (!result.data || result.data.length === 0) {
                jadwalSelect.innerHTML = '<option value="">Tidak ada jadwal tersedia pada tanggal ini</option>';
                return;
            }

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Pilih jam kedatangan';
            jadwalSelect.appendChild(defaultOption);

            result.data.forEach((jadwal) => {
                const option = document.createElement('option');
                option.value = jadwal.id;
                option.textContent = `${jadwal.label} (sisa ${jadwal.sisa_kuota} slot)`;

                if (config.oldJadwalId && String(config.oldJadwalId) === String(jadwal.id)) {
                    option.selected = true;
                }

                jadwalSelect.appendChild(option);
            });
        } catch (error) {
            jadwalSelect.innerHTML = '<option value="">Gagal memuat jadwal, silakan coba lagi</option>';
            jadwalSelect.disabled = false;
        }
    };

    layananOptions.forEach((option) => {
        option.addEventListener('change', muatJadwalTersedia);
    });

    tanggalInput.addEventListener('change', muatJadwalTersedia);

    if (getSelectedLayananId() && tanggalInput.value) {
        muatJadwalTersedia();
    }
}

/**
 * Collapse/expand konten pada mobile (halaman Detail Reservasi).
 */
document.querySelectorAll('[data-toggle-target]').forEach((button) => {
    const target = document.getElementById(button.dataset.toggleTarget);
    const icon = button.querySelector('[data-toggle-icon]');

    if (!target) {
        return;
    }

    button.addEventListener('click', () => {
        const isHidden = target.classList.toggle('hidden');
        button.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
        icon?.classList.toggle('rotate-180', isHidden);
    });
});

/**
 * "Lihat Semua" dokumen (halaman Detail Reservasi).
 */
document.querySelectorAll('[data-dokumen-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const card = button.closest('div');
        const hiddenItems = card?.parentElement?.querySelectorAll('.hidden') ?? [];

        hiddenItems.forEach((item) => item.classList.remove('hidden'));
        button.remove();
    });
});
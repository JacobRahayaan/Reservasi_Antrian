import './bootstrap';

/**
 * Modal handler.
 * Elemen dengan [data-modal-target="id-dialog"] membuka <dialog id="id-dialog">.
 * Elemen dengan [data-modal-close] menutup <dialog> terdekat.
 * Klik pada backdrop dialog juga menutup modal.
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
 * Sidebar toggle (mobile).
 * Tombol [data-sidebar-toggle] membuka/menutup sidebar dashboard di layar kecil.
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
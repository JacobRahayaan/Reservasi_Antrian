import './bootstrap';

/**
 * Modal handler (dipakai di layout dashboard).
 * Elemen dengan [data-modal-target="id-dialog"] membuka <dialog id="id-dialog">.
 * Elemen dengan [data-modal-close] menutup <dialog> terdekat.
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
 * Mobile nav toggle — layout public (landing page).
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
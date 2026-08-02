<footer class="border-t border-pln-slate-200 bg-white">
    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 md:grid-cols-4">

            <div class="md:col-span-2">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-pln-navy-900">
                        <svg viewBox="0 0 24 24" class="h-4 w-4 text-pln-amber-500" fill="currentColor" aria-hidden="true">
                            <path d="M13 2 3 14h7l-1 8 11-14h-8l1-6Z" />
                        </svg>
                    </span>
                    <span class="font-display text-sm font-semibold tracking-tight text-pln-navy-900">
                        SIRA-PLN
                    </span>
                </div>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-pln-slate-600">
                    Sistem Reservasi & Manajemen Antrean Pelanggan PLN. Jadwalkan kedatangan Anda, sampaikan keluhan lebih awal, dan datang ke kantor hanya jika benar-benar diperlukan.
                </p>
            </div>

            <div>
                <h3 class="font-display text-sm font-semibold text-pln-navy-900">Layanan</h3>
                <ul class="mt-4 space-y-3 text-sm text-pln-slate-600">
                    <li><a href="#" class="hover:text-pln-navy-900">Pasang Baru / Tambah Daya</a></li>
                    <li><a href="#" class="hover:text-pln-navy-900">Tagihan Bulanan</a></li>
                    <li><a href="#" class="hover:text-pln-navy-900">Pengaduan Gangguan</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-display text-sm font-semibold text-pln-navy-900">Bantuan</h3>
                <ul class="mt-4 space-y-3 text-sm text-pln-slate-600">
                    <li><a href="#" class="hover:text-pln-navy-900">Cek Status Reservasi</a></li>
                    <li><a href="#" class="hover:text-pln-navy-900">Hubungi Customer Service</a></li>
                    <li><a href="#" class="hover:text-pln-navy-900">Syarat &amp; Ketentuan</a></li>
                </ul>
            </div>

        </div>

        <div class="mt-10 flex flex-col gap-4 border-t border-pln-slate-200 pt-6 text-xs text-pln-slate-400 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ now()->year }} PLN. Seluruh hak cipta dilindungi.</p>
            <p>Dibangun dengan Laravel &amp; Tailwind CSS.</p>
        </div>
    </div>
</footer>
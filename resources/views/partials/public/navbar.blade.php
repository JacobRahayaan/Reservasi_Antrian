<header class="sticky top-0 z-40 border-b border-pln-slate-200 bg-pln-slate-50/90 backdrop-blur">
    <nav class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6 lg:px-8" aria-label="Navigasi utama">

        <a href="{{ route('landing') }}" class="flex items-center gap-2.5">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-pln-navy-900">
                <svg viewBox="0 0 24 24" class="h-5 w-5 text-pln-amber-500" fill="currentColor" aria-hidden="true">
                    <path d="M13 2 3 14h7l-1 8 11-14h-8l1-6Z" />
                </svg>
            </span>
            <span class="font-display text-base font-semibold tracking-tight text-pln-navy-900">
                SIRA<span class="text-pln-amber-600">-PLN</span>
            </span>
        </a>

        <div class="hidden items-center gap-8 md:flex">
            <a href="{{ route('landing') }}" class="text-sm font-medium text-pln-slate-600 transition hover:text-pln-navy-900">Beranda</a>
            <a href="#layanan" class="text-sm font-medium text-pln-slate-600 transition hover:text-pln-navy-900">Layanan</a>
            <a href="#cara-kerja" class="text-sm font-medium text-pln-slate-600 transition hover:text-pln-navy-900">Cara Kerja</a>
            <a href="#bantuan" class="text-sm font-medium text-pln-slate-600 transition hover:text-pln-navy-900">Bantuan</a>
        </div>

        <div class="flex items-center gap-3">
            <x-button href="#" variant="ghost" size="sm" class="hidden sm:inline-flex">
                Cek Status
            </x-button>
            <x-button href="#" variant="primary" size="sm">
                Buat Reservasi
            </x-button>
        </div>

    </nav>
</header>
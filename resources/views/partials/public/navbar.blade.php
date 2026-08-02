<header class="sticky top-0 z-40 border-b border-pln-slate-200 bg-white print:hidden">
    <nav class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6 lg:px-8" aria-label="Navigasi utama">

        <a href="{{ route('landing') }}" class="flex items-center gap-2.5">
            <img
				src="{{ asset('images/logo-pln.png') }}"
				alt="Logo PLN"
				class="h-12 w-auto"
			/>
            <span class="leading-tight">
                <span class="block font-display text-base font-bold tracking-tight text-pln-navy-900">Unit Layanan Pelanggan Manado Selatan</span>
            </span>			
        </a>

        <div class="hidden items-center gap-8 md:flex">
            <a href="{{ route('landing') }}" class="text-sm font-semibold text-pln-navy-700">Beranda</a>
            <a href="#cara-reservasi" class="text-sm font-medium text-pln-slate-600 transition hover:text-pln-navy-900">Cara Reservasi</a>
            <a href="#layanan" class="text-sm font-medium text-pln-slate-600 transition hover:text-pln-navy-900">Layanan</a>
            <a href="#informasi" class="text-sm font-medium text-pln-slate-600 transition hover:text-pln-navy-900">Informasi</a>
            <a href="#kontak" class="text-sm font-medium text-pln-slate-600 transition hover:text-pln-navy-900">Kontak</a>
        </div>

        <button
            type="button"
            data-mobile-nav-toggle
            class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-pln-slate-600 transition hover:bg-pln-slate-100 md:hidden"
            aria-label="Buka menu navigasi"
            aria-controls="mobile-nav"
            aria-expanded="false"
        >
            <x-icon name="bars-3" class="h-6 w-6" />
        </button>

    </nav>

    <div id="mobile-nav" class="hidden border-t border-pln-slate-200 md:hidden">
        <div class="space-y-1 px-4 py-3">
            <a href="{{ route('landing') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-pln-navy-700">Beranda</a>
            <a href="#cara-reservasi" class="block rounded-lg px-3 py-2 text-sm font-medium text-pln-slate-600 hover:bg-pln-slate-100">Cara Reservasi</a>
            <a href="#layanan" class="block rounded-lg px-3 py-2 text-sm font-medium text-pln-slate-600 hover:bg-pln-slate-100">Layanan</a>
            <a href="#informasi" class="block rounded-lg px-3 py-2 text-sm font-medium text-pln-slate-600 hover:bg-pln-slate-100">Informasi</a>
            <a href="#kontak" class="block rounded-lg px-3 py-2 text-sm font-medium text-pln-slate-600 hover:bg-pln-slate-100">Kontak</a>
        </div>
    </div>
</header>
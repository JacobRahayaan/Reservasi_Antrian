<header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-pln-slate-200 bg-white px-4 sm:px-6 lg:px-8">

    <div class="flex items-center gap-4">
        <button
            type="button"
            data-sidebar-toggle
            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-pln-slate-600 transition hover:bg-pln-slate-100 lg:hidden"
            aria-label="Buka menu navigasi"
            aria-controls="dashboard-sidebar"
        >
            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <h1 class="font-display text-lg font-semibold text-pln-navy-900">
            @yield('page-title', 'Dashboard')
        </h1>
    </div>

    <div class="flex items-center gap-3">
        <span class="hidden text-sm text-pln-slate-500 sm:inline">
            {{ now()->translatedFormat('l, d F Y') }}
        </span>
        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-pln-navy-900 text-sm font-semibold text-white">
            CS
        </span>
    </div>

</header>
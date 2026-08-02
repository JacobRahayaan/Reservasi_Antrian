@php
    $isAdmin = request()->routeIs('admin.*');
    $isCs = request()->routeIs('cs.*');

    $linkClass = fn (bool $active) => $active
        ? 'flex items-center gap-3 rounded-r-lg border-l-2 border-pln-amber-500 bg-pln-navy-800 px-3 py-2 text-sm font-medium text-white'
        : 'flex items-center gap-3 rounded-r-lg border-l-2 border-transparent px-3 py-2 text-sm font-medium text-pln-slate-300 transition hover:bg-pln-navy-800 hover:text-white';
@endphp

<aside
    id="dashboard-sidebar"
    class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-pln-navy-900 transition-transform duration-200 lg:translate-x-0"
>
    <div class="flex h-16 items-center gap-2.5 px-5">
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-pln-navy-950">
            <svg viewBox="0 0 24 24" class="h-5 w-5 text-pln-amber-500" fill="currentColor" aria-hidden="true">
                <path d="M13 2 3 14h7l-1 8 11-14h-8l1-6Z" />
            </svg>
        </span>
        <span class="font-display text-base font-semibold tracking-tight text-white">
            SIRA<span class="text-pln-amber-500">-PLN</span>
        </span>
    </div>

    <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-6" aria-label="Navigasi dashboard">

        @if ($isAdmin)
            <div>
                <p class="px-3 text-xs font-semibold uppercase tracking-wider text-pln-slate-400">Admin</p>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="{{ $linkClass(request()->routeIs('admin.dashboard')) }}">
                        <span>Ringkasan</span>
                    </a>
                </div>
            </div>
        @elseif ($isCs)
            <div>
                <p class="px-3 text-xs font-semibold uppercase tracking-wider text-pln-slate-400">Customer Service</p>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('cs.dashboard') }}" class="{{ $linkClass(request()->routeIs('cs.dashboard')) }}">
                        <span>Ringkasan</span>
                    </a>
                </div>
            </div>
        @else
            <div>
                <p class="px-3 text-xs font-semibold uppercase tracking-wider text-pln-slate-400">Sistem</p>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('system.error-demo') }}" class="{{ $linkClass(request()->routeIs('system.error-demo')) }}">
                        <span>Contoh Halaman Error</span>
                    </a>
                </div>
            </div>
        @endif

    </nav>

    <div class="border-t border-pln-navy-800 px-4 py-4">
        <p class="text-xs text-pln-slate-400">SIRA-PLN &middot; MVP 1.0</p>
    </div>
</aside>
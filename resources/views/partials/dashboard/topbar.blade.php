@php
    $tanggalTampil = isset($tanggal) ? $tanggal : now();
    $jumlahNotifikasiTampil = $jumlahNotifikasi ?? 0;

    // $authUser disuntikkan oleh AuthUserComposer. Jika null (mis. halaman
    // system.error-demo yang tidak diproteksi middleware auth), topbar
    // jatuh kembali ke @yield('user-*') seperti sebelum modul Login ada
    // tidak ada view lain yang perlu diedit untuk perubahan ini.
    $namaTampil = $authUser?->nama_tampilan ?? trim($__env->yieldContent('user-name', 'Admin'));
    $peranTampil = $authUser?->labelPeran() ?? trim($__env->yieldContent('user-role', 'Administrator'));
    $inisialTampil = $authUser?->inisial() ?? trim($__env->yieldContent('user-initial', 'A'));
@endphp

<header class="sticky top-0 z-20 flex h-16 items-center justify-between gap-3 border-b border-pln-slate-200 bg-white px-4 sm:px-6 lg:px-8">

    <div class="flex min-w-0 items-center gap-4">
        <button
            type="button"
            data-sidebar-toggle
            class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-pln-slate-600 transition hover:bg-pln-slate-100 lg:hidden"
            aria-label="Buka menu navigasi"
            aria-controls="dashboard-sidebar"
        >
            <x-icon name="bars-3" class="h-5 w-5" />
        </button>

        <div class="min-w-0">
            <h1 class="truncate font-display text-lg font-semibold text-pln-navy-900">
                @yield('page-title', 'Dashboard')
            </h1>
            <p class="hidden truncate text-xs text-pln-slate-500 sm:block">
                @yield('page-subtitle', '')
            </p>
        </div>
    </div>

    <div class="flex shrink-0 items-center gap-3">

        <form method="GET" class="hidden sm:block">
            <label for="filter-tanggal" class="sr-only">Pilih tanggal</label>
            <div class="flex items-center gap-2 rounded-lg border border-pln-slate-200 px-3 py-2">
                <x-icon name="calendar" class="h-4 w-4 text-pln-slate-400" />
                <input
                    type="date"
                    name="tanggal"
                    id="filter-tanggal"
                    value="{{ $tanggalTampil->toDateString() }}"
                    onchange="this.form.submit()"
                    class="border-0 bg-transparent p-0 text-sm text-pln-slate-700 focus:outline-none focus:ring-0"
                >
            </div>
        </form>

		@auth('petugas')
			<a
				href="{{ route('cs.reservasi.index', ['tab' => 'aktif', 'status' => 'menunggu_review']) }}"
				class="relative flex h-9 w-9 items-center justify-center rounded-lg text-pln-slate-600 transition hover:bg-pln-slate-100"
				aria-label="Notifikasi reservasi menunggu review"
			>
				<x-icon name="bell" class="h-5 w-5" />
				<span
					id="notifikasi-bell-badge"
					@class([
						'absolute -right-1 -top-1 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-status-cancel px-1 text-[10px] font-bold text-white',
						'hidden' => $jumlahNotifikasiTampil === 0,
					])
				>
					{{ $jumlahNotifikasiTampil > 99 ? '99+' : $jumlahNotifikasiTampil }}
				</span>
				<span
					id="notifikasi-suara-indikator"
					class="absolute -bottom-1 -right-1 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-pln-slate-300 ring-2 ring-white"
					title="Klik di mana saja untuk mengaktifkan suara notifikasi"
				></span>
			</a>

			<script>
				window.notifikasiReservasiConfig = {
					cekUrl: @json(route('cs.notifikasi.cek-reservasi-baru')),
					intervalMs: 8000,
				};
			</script>
		@else
			<button
				type="button"
				class="relative flex h-9 w-9 items-center justify-center rounded-lg text-pln-slate-600 transition hover:bg-pln-slate-100"
				aria-label="Notifikasi reservasi menunggu review"
			>
				<x-icon name="bell" class="h-5 w-5" />
				@if ($jumlahNotifikasiTampil > 0)
					<span class="absolute -right-1 -top-1 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-status-cancel px-1 text-[10px] font-bold text-white">
						{{ $jumlahNotifikasiTampil > 99 ? '99+' : $jumlahNotifikasiTampil }}
					</span>
				@endif
			</button>
		@endauth

        <div class="hidden items-center gap-2.5 border-l border-pln-slate-200 pl-3 sm:flex">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-pln-navy-900 text-sm font-semibold text-white">
                {{ $inisialTampil }}
            </span>
            <div class="leading-tight">
                <p class="text-sm font-semibold text-pln-navy-900">{{ $namaTampil }}</p>
                <p class="text-xs text-pln-slate-400">{{ $peranTampil }}</p>
            </div>

            @if ($authUser)
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="ml-1 flex h-9 w-9 items-center justify-center rounded-lg text-pln-slate-400 transition hover:bg-status-cancel/10 hover:text-status-cancel"
                        aria-label="Keluar"
                        title="Keluar"
                    >
                        <x-icon name="logout" class="h-4 w-4" />
                    </button>
                </form>
            @endif
        </div>
    </div>

</header>
@php
    $isAdmin = request()->routeIs('admin.*');
    $isCs = request()->routeIs('cs.*');
@endphp

<aside
    id="dashboard-sidebar"
    class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col overflow-y-auto border-r border-pln-slate-200 bg-white transition-transform duration-200 lg:translate-x-0"
>
	<div class="flex h-16 items-center gap-3 px-5">

		<img
			src="{{ asset('images/logo-pln.png') }}"
			alt="Logo PLN"
			class="h-10 w-10 object-contain"
		>

		<div>
			<p class="text-xs text-pln-slate-500">
				Sistem Reservasi Antrian ULP Manado Selatan
			</p>
		</div>

	</div>
    <nav class="flex-1 space-y-6 px-3 py-4" aria-label="Navigasi dashboard">

        @if ($isAdmin)
            <x-dashboard.nav-group title="Menu Utama">
                <x-dashboard.nav-item
                    :href="route('admin.dashboard')"
                    icon="check-circle"
                    :active="request()->routeIs('admin.dashboard')"
                >
                    Dashboard
                </x-dashboard.nav-item>

                <x-dashboard.nav-item
                    :href="Route::has('admin.reservasi.index') ? route('admin.reservasi.index') : null"
                    icon="clipboard-list"
                    :disabled="! Route::has('admin.reservasi.index')"
                >
                    Reservasi
                </x-dashboard.nav-item>

                <x-dashboard.nav-item
                    :href="Route::has('admin.kalender.index') ? route('admin.kalender.index') : null"
                    icon="calendar"
                    :disabled="! Route::has('admin.kalender.index')"
                >
                    Kalender Jadwal
                </x-dashboard.nav-item>

                <x-dashboard.nav-item
                    :href="Route::has('admin.laporan.index') ? route('admin.laporan.index') : null"
                    icon="chart-bar"
                    :disabled="! Route::has('admin.laporan.index')"
                >
                    Laporan
                </x-dashboard.nav-item>

                <x-dashboard.nav-item
                    :href="Route::has('admin.pengguna.index') ? route('admin.pengguna.index') : null"
                    icon="users"
                    :disabled="! Route::has('admin.pengguna.index')"
                >
                    Pengguna
                </x-dashboard.nav-item>
            </x-dashboard.nav-group>

            <x-dashboard.nav-group title="Pengelolaan">
                <x-dashboard.nav-item
                    :href="route('admin.layanan.index')"
                    icon="bolt"
                    :active="request()->routeIs('admin.layanan.*')"
                >
                    Layanan
                </x-dashboard.nav-item>

                <x-dashboard.nav-item
                    :href="route('admin.jadwal.index')"
                    icon="ticket"
                    :active="request()->routeIs('admin.jadwal.*')"
                >
                    Jadwal &amp; Kuota
                </x-dashboard.nav-item>

                <x-dashboard.nav-item
                    :href="Route::has('admin.pengumuman.index') ? route('admin.pengumuman.index') : null"
                    icon="megaphone"
                    :disabled="! Route::has('admin.pengumuman.index')"
                >
                    Pengumuman
                </x-dashboard.nav-item>
            </x-dashboard.nav-group>

            <x-dashboard.nav-group title="Pengaturan">
                <x-dashboard.nav-item
                    :href="Route::has('admin.pengaturan.index') ? route('admin.pengaturan.index') : null"
                    icon="cog"
                    :disabled="! Route::has('admin.pengaturan.index')"
                >
                    Pengaturan Sistem
                </x-dashboard.nav-item>

                <x-dashboard.nav-item
                    :href="Route::has('admin.profil.index') ? route('admin.profil.index') : null"
                    icon="user"
                    :disabled="! Route::has('admin.profil.index')"
                >
                    Profil Saya
                </x-dashboard.nav-item>
            </x-dashboard.nav-group>
        @elseif ($isCs)
            <x-dashboard.nav-group title="Menu Utama">
                <x-dashboard.nav-item
                    :href="route('cs.dashboard')"
                    icon="check-circle"
                    :active="request()->routeIs('cs.dashboard')"
                >
                    Dashboard
                </x-dashboard.nav-item>

                <x-dashboard.nav-item
                    :href="Route::has('cs.reservasi.index') ? route('cs.reservasi.index') : null"
                    icon="clipboard-list"
                    :disabled="! Route::has('cs.reservasi.index')"
                >
                    Daftar Reservasi
                </x-dashboard.nav-item>
            </x-dashboard.nav-group>
        @else
            <x-dashboard.nav-group title="Sistem">
                <x-dashboard.nav-item
                    :href="route('system.error-demo')"
                    icon="x-mark"
                    :active="request()->routeIs('system.error-demo')"
                >
                    Contoh Halaman Error
                </x-dashboard.nav-item>
            </x-dashboard.nav-group>
        @endif

    </nav>

    <div class="border-t border-pln-slate-200 p-4">
        <div class="rounded-xl bg-pln-navy-900/5 p-4">
            <p class="text-sm font-semibold text-pln-navy-900">Butuh Bantuan?</p>
            <p class="mt-1 text-xs leading-relaxed text-pln-slate-500">
                Hubungi Contact Center PLN 123.
            </p>
            
                href="tel:123"
                class="mt-3 flex items-center justify-center gap-2 rounded-lg bg-pln-navy-900 px-3 py-2 text-xs font-semibold text-white transition hover:bg-pln-navy-800"
            >
                <x-icon name="phone" class="h-3.5 w-3.5" />
                PLN 123
            </a>
        </div>
    </div>
</aside>
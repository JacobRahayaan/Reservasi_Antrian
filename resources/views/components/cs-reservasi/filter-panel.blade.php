@props(['layanans', 'opsiStatus', 'filters'])

<div id="panel-filter-reservasi" class="hidden sm:!block">
    <form method="GET" class="space-y-4">
        <input type="hidden" name="tab" value="{{ request()->query('tab', 'aktif') }}">

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-input
                label="Tanggal Mulai"
                name="tanggal_mulai"
                type="date"
                :value="$filters['tanggal_mulai']"
            />
            <x-input
                label="Tanggal Akhir"
                name="tanggal_akhir"
                type="date"
                :value="$filters['tanggal_akhir']"
            />

            <div class="w-full">
                <label for="layanan_id" class="mb-1.5 block text-sm font-medium text-pln-slate-900">Layanan</label>
                <select
                    name="layanan_id"
                    id="layanan_id"
                    class="block w-full rounded-lg border border-pln-slate-200 px-3.5 py-2.5 text-sm text-pln-slate-700 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20"
                >
                    <option value="">Semua Layanan</option>
                    @foreach ($layanans as $layanan)
                        <option value="{{ $layanan->id }}" @selected((string) $filters['layanan_id'] === (string) $layanan->id)>
                            {{ $layanan->nama_layanan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full">
                <label for="status" class="mb-1.5 block text-sm font-medium text-pln-slate-900">Status</label>
                <select
                    name="status"
                    id="status"
                    class="block w-full rounded-lg border border-pln-slate-200 px-3.5 py-2.5 text-sm text-pln-slate-700 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20"
                >
                    <option value="">Semua Status</option>
                    @foreach ($opsiStatus as $value => $label)
                        <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-pln-slate-400" />
                <input
                    type="text"
                    name="cari"
                    value="{{ $filters['cari'] }}"
                    placeholder="Cari nama, nomor antrean, atau kode reservasi..."
                    class="w-full rounded-lg border border-pln-slate-200 py-2.5 pl-10 pr-3.5 text-sm text-pln-slate-900 placeholder:text-pln-slate-400 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20"
                >
            </div>

            <div class="w-full sm:w-48">
                <select
                    name="urutan"
                    class="w-full rounded-lg border border-pln-slate-200 px-3.5 py-2.5 text-sm text-pln-slate-700 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20"
                >
                    <option value="terbaru" @selected($filters['urutan'] === 'terbaru')>Terbaru</option>
                    <option value="terlama" @selected($filters['urutan'] === 'terlama')>Terlama</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex items-center gap-2 rounded-lg bg-pln-navy-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-pln-navy-800">
                    <x-icon name="filter" class="h-4 w-4" />
                    Filter
                </button>
                <a href="{{ route('cs.reservasi.index', ['tab' => request()->query('tab', 'aktif')]) }}" class="flex items-center gap-2 rounded-lg border border-pln-slate-300 px-4 py-2.5 text-sm font-semibold text-pln-slate-700 transition hover:bg-pln-slate-50">
                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5M4 9a8 8 0 0 1 14.6-4.6M20 15a8 8 0 0 1-14.6 4.6" />
                    </svg>
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>
@extends('layouts.dashboard')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Kelola Pengguna')
@section('page-subtitle', 'Dashboard > Pengguna')
@section('user-initial', 'A')
@section('user-name', 'Admin')
@section('user-role', 'Administrator')

@section('content')

    <div class="space-y-6">

        @if (session('success'))
            <x-alert variant="success" title="Berhasil" dismissible>{{ session('success') }}</x-alert>
        @endif

        @if (session('error'))
            <x-alert variant="danger" title="Tidak dapat dihapus" dismissible>{{ session('error') }}</x-alert>
        @endif

        <x-card padding="p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="font-display text-xl font-bold text-pln-navy-950">Kelola Pengguna</h1>
                    <p class="mt-1 text-sm text-pln-slate-600">Kelola data petugas Customer Service.</p>
                </div>

                <x-button href="{{ route('admin.pengguna.create') }}" variant="primary" size="md">
                    <x-icon name="plus" class="h-4 w-4" />
                    Tambah Petugas
                </x-button>
            </div>
        </x-card>

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <x-card padding="p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-pln-navy-600/10 text-pln-navy-700">
                        <x-icon name="users" class="h-5 w-5" />
                    </span>
                    <div>
                        <p class="font-display text-2xl font-bold text-pln-navy-950">{{ $statistik['total'] }}</p>
                        <p class="text-xs text-pln-slate-500">Petugas</p>
                    </div>
                </div>
                <p class="mt-3 text-sm font-medium text-pln-slate-700">Total</p>
            </x-card>

            <x-card padding="p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-status-done/10 text-status-done">
                        <x-icon name="check-circle" class="h-5 w-5" />
                    </span>
                    <div>
                        <p class="font-display text-2xl font-bold text-pln-navy-950">{{ $statistik['aktif'] }}</p>
                        <p class="text-xs text-pln-slate-500">Petugas aktif</p>
                    </div>
                </div>
                <p class="mt-3 text-sm font-medium text-pln-slate-700">Aktif</p>
            </x-card>

            <x-card padding="p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-pln-slate-200 text-pln-slate-500">
                        <x-icon name="pause-circle" class="h-5 w-5" />
                    </span>
                    <div>
                        <p class="font-display text-2xl font-bold text-pln-navy-950">{{ $statistik['nonaktif'] }}</p>
                        <p class="text-xs text-pln-slate-500">Petugas nonaktif</p>
                    </div>
                </div>
                <p class="mt-3 text-sm font-medium text-pln-slate-700">Nonaktif</p>
            </x-card>

            <x-card padding="p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-500/10 text-violet-600">
                        <x-icon name="document-text" class="h-5 w-5" />
                    </span>
                    <div>
                        <p class="font-display text-2xl font-bold text-pln-navy-950">{{ $statistik['total_aktivitas'] }}</p>
                        <p class="text-xs text-pln-slate-500">Aktivitas tercatat</p>
                    </div>
                </div>
                <p class="mt-3 text-sm font-medium text-pln-slate-700">Total Aktivitas</p>
            </x-card>
        </div>

        <x-card padding="p-6">
            <x-slot:header>
                <div>
                    <h2 class="font-display text-base font-semibold text-pln-navy-900">Daftar Petugas</h2>
                </div>
            </x-slot:header>

            <form method="GET" class="mb-5 flex flex-col gap-3 sm:flex-row">
                <div class="relative flex-1">
                    <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-pln-slate-400" />
                    <input
                        type="text"
                        name="cari"
                        value="{{ $pencarian }}"
                        placeholder="Cari nama atau email petugas..."
                        class="w-full rounded-lg border border-pln-slate-200 py-2.5 pl-10 pr-3.5 text-sm text-pln-slate-900 placeholder:text-pln-slate-400 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20"
                    >
                </div>

                <select
                    name="status"
                    onchange="this.form.submit()"
                    class="rounded-lg border border-pln-slate-200 px-3.5 py-2.5 text-sm text-pln-slate-700 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20 sm:w-48"
                >
                    <option value="semua" @selected($statusFilter === 'semua')>Semua Status</option>
                    <option value="aktif" @selected($statusFilter === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected($statusFilter === 'nonaktif')>Nonaktif</option>
                </select>

                <button type="submit" class="rounded-lg bg-pln-navy-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-pln-navy-800 sm:hidden">
                    Cari
                </button>
            </form>

            @if ($petugas->isEmpty())
                <x-empty-state
                    title="{{ $pencarian !== '' ? 'Petugas tidak ditemukan' : 'Belum ada petugas' }}"
                    description="{{ $pencarian !== '' ? 'Tidak ada petugas yang cocok dengan pencarian &quot;' . $pencarian . '&quot;.' : 'Tambahkan petugas Customer Service pertama.' }}"
                >
                    @if ($pencarian === '')
                        <x-slot:action>
                            <x-button href="{{ route('admin.pengguna.create') }}" variant="primary" size="sm">
                                <x-icon name="plus" class="h-4 w-4" />
                                Tambah Petugas
                            </x-button>
                        </x-slot:action>
                    @endif
                </x-empty-state>
            @else
                <div class="hidden overflow-x-auto sm:block">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-pln-slate-200 text-xs font-semibold uppercase tracking-wider text-pln-slate-400">
                                <th class="px-4 py-2.5">No</th>
                                <th class="px-4 py-2.5">
                                    <x-layanan.sort-link column="nama_petugas" label="Nama" :sort-by="$sortBy" :sort-direction="$sortDirection" />
                                </th>
                                <th class="px-4 py-2.5">
                                    <x-layanan.sort-link column="email" label="Email" :sort-by="$sortBy" :sort-direction="$sortDirection" />
                                </th>
                                <th class="px-4 py-2.5">Nomor HP</th>
                                <th class="px-4 py-2.5">
                                    <x-layanan.sort-link column="is_active" label="Status" :sort-by="$sortBy" :sort-direction="$sortDirection" />
                                </th>
                                <th class="px-4 py-2.5">
                                    <x-layanan.sort-link column="created_at" label="Dibuat Pada" :sort-by="$sortBy" :sort-direction="$sortDirection" />
                                </th>
                                <th class="px-4 py-2.5">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($petugas as $item)
                                <x-petugas.table-row :petugas="$item" :nomor="$petugas->firstItem() + $loop->index" />
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="space-y-3 sm:hidden">
                    @foreach ($petugas as $item)
                        <x-petugas.card :petugas="$item" />
                    @endforeach
                </div>

                <div class="mt-5 border-t border-pln-slate-100 pt-5">
                    <x-pagination :paginator="$petugas" />
                </div>
            @endif
        </x-card>

    </div>

@endsection
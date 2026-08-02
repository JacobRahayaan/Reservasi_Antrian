@extends('layouts.public')

@section('title', 'Beranda')

@section('content')

    <section class="mx-auto max-w-6xl px-4 py-20 sm:px-6 sm:py-28 lg:px-8">
        <div class="grid items-center gap-12 lg:grid-cols-2">

            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-pln-navy-900/5 px-3 py-1 text-xs font-medium text-pln-navy-800">
                    <span class="h-1.5 w-1.5 rounded-full bg-pln-amber-500"></span>
                    Sprint 0 &middot; Fondasi Proyek
                </span>

                <h1 class="mt-5 font-display text-4xl font-semibold tracking-tight text-pln-navy-900 sm:text-5xl">
                    Reservasi layanan PLN, tanpa antre di tempat.
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-pln-slate-600 sm:text-lg">
                    Jadwalkan kedatangan Anda, sampaikan keluhan lebih awal, dan dapatkan nomor antrean secara instan. Petugas kami mereview kebutuhan Anda sebelum Anda datang.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <x-button href="#" variant="primary" size="lg">
                        Buat Reservasi
                    </x-button>
                    <x-button href="#" variant="ghost" size="lg">
                        Cek Status Reservasi
                    </x-button>
                </div>
            </div>

            <x-card class="p-8">
                <x-slot:header>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Nomor Antrean</p>
                        <p class="font-mono text-3xl font-semibold text-pln-navy-900">A-012</p>
                    </div>
                    <x-badge variant="review">Menunggu Review</x-badge>
                </x-slot:header>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-pln-slate-500">Jenis Layanan</dt>
                        <dd class="font-medium text-pln-slate-900">Tambah Daya</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-pln-slate-500">Jadwal</dt>
                        <dd class="font-medium text-pln-slate-900">12 Agustus 2026, 09.00</dd>
                    </div>
                </dl>

                <x-slot:footer>
                    <p class="text-xs text-pln-slate-400">
                        Contoh tampilan kartu — akan menjadi Halaman Detail Reservasi pada sprint berikutnya.
                    </p>
                </x-slot:footer>
            </x-card>

        </div>
    </section>

@endsection
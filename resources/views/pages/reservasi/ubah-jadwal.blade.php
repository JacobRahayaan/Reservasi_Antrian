@extends('layouts.public')

@section('title', 'Ubah Jadwal Reservasi')
@section('meta_description', 'Ubah tanggal dan jam kedatangan reservasi Anda.')

@section('content')

    <div class="border-b border-pln-slate-200 bg-pln-slate-100/60">
        <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
            <x-reservasi.breadcrumb :items="[
                ['label' => 'Beranda', 'href' => route('landing')],
                ['label' => 'Detail Reservasi', 'href' => route('reservasi.show', $reservasi)],
                ['label' => 'Ubah Jadwal'],
            ]" />

            <div class="mt-4">
                <h1 class="font-display text-2xl font-bold tracking-tight text-pln-navy-950 sm:text-3xl">
                    Ubah Jadwal Reservasi
                </h1>
                <p class="mt-1.5 text-sm text-pln-slate-600">
                    Reservasi {{ $reservasi->kode_reservasi }} &middot; Nomor Antrean {{ $reservasi->nomor_antrean }}
                </p>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">

        @if ($errors->any())
            <x-alert variant="danger" title="Periksa kembali data Anda" class="mb-6">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <form
            action="{{ route('reservasi.ubah-jadwal.update', $reservasi) }}"
            method="POST"
            class="grid gap-6 lg:grid-cols-3"
            novalidate
        >
            @csrf
            @method('PUT')

            <div class="space-y-6 lg:col-span-2">

                <x-card>
                    <x-slot:header>
                        <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                            <x-icon name="calendar" class="h-5 w-5 text-pln-navy-700" />
                            Jadwal Saat Ini
                        </h2>
                    </x-slot:header>

                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Jenis Layanan</dt>
                            <dd class="mt-1 text-sm font-medium text-pln-slate-900">{{ $reservasi->layanan->nama_layanan }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Tanggal &amp; Jam</dt>
                            <dd class="mt-1 text-sm font-medium text-pln-slate-900">
                                {{ $reservasi->jadwal->tanggal->translatedFormat('d F Y') }},
                                {{ substr($reservasi->jadwal->jam_mulai, 0, 5) }} - {{ substr($reservasi->jadwal->jam_selesai, 0, 5) }}
                            </dd>
                        </div>
                    </dl>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                            <x-icon name="pencil-square" class="h-5 w-5 text-pln-navy-700" />
                            Pilih Jadwal Baru
                        </h2>
                    </x-slot:header>

                    <div class="space-y-5">
                        <input
                            type="radio"
                            name="layanan_id"
                            value="{{ $reservasi->layanan_id }}"
                            checked
                            class="sr-only"
                            data-layanan-option
                        >

                        <x-input
                            label="Konfirmasi Nomor HP"
                            name="nomor_hp_konfirmasi"
                            placeholder="Masukkan nomor HP yang digunakan saat reservasi"
                            hint="Untuk memverifikasi bahwa Anda pemilik reservasi ini."
                            required
                            :error="$errors->first('nomor_hp_konfirmasi')"
                        />

                        <div class="grid gap-5 sm:grid-cols-2">
                            <x-input
                                label="Pilih Tanggal Baru"
                                name="tanggal"
                                type="date"
                                id="tanggal"
                                data-tanggal-input
                                :value="old('tanggal', $reservasi->jadwal->tanggal->toDateString())"
                                min="{{ now()->toDateString() }}"
                                required
                                :error="$errors->first('tanggal')"
                            />

                            <div class="w-full">
                                <label for="jadwal_id" class="mb-1.5 block text-sm font-medium text-pln-slate-900">
                                    Pilih Jam Baru <span class="text-status-cancel">*</span>
                                </label>
                                <select
                                    name="jadwal_id"
                                    id="jadwal_id"
                                    data-jadwal-select
                                    required
                                    class="block w-full rounded-lg border px-3.5 py-2.5 text-sm text-pln-slate-900 focus:outline-none focus:ring-2 {{ $errors->has('jadwal_id') ? 'border-status-cancel focus:ring-status-cancel/40' : 'border-pln-slate-200 focus:border-pln-navy-700 focus:ring-pln-navy-700/20' }}"
                                >
                                    <option value="">Memuat jadwal...</option>
                                </select>
                                @error('jadwal_id')
                                    <p class="mt-1.5 text-sm text-status-cancel">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <x-slot:footer>
                        <div class="flex items-center justify-between">
                            <x-button href="{{ route('reservasi.show', $reservasi) }}" variant="ghost" size="md">
                                <x-icon name="x-mark" class="h-4 w-4" />
                                Batal
                            </x-button>
                            <x-button type="submit" variant="primary" size="md">
                                Simpan Perubahan Jadwal
                                <x-icon name="check" class="h-4 w-4" />
                            </x-button>
                        </div>
                    </x-slot:footer>
                </x-card>

            </div>

            <div class="space-y-6">
                <x-card>
                    <x-slot:header>
                        <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                            <x-icon name="clock" class="h-5 w-5 text-pln-navy-700" />
                            Informasi Penting
                        </h2>
                    </x-slot:header>

                    <div class="space-y-5">
                        <x-reservasi.info-item icon="ticket" title="Nomor Antrean Dapat Berubah">
                            Jika Anda memindahkan reservasi ke tanggal lain, nomor antrean akan diterbitkan ulang sesuai urutan pada tanggal baru.
                        </x-reservasi.info-item>

                        <x-reservasi.info-item icon="clock" title="Ketersediaan Slot">
                            Hanya jadwal yang masih aktif dan memiliki sisa kuota yang dapat dipilih.
                        </x-reservasi.info-item>
                    </div>
                </x-card>

                <x-reservasi.bantuan-card />
            </div>

        </form>

    </div>

@endsection

@push('scripts')
    <script>
        window.reservasiConfig = {
            jadwalTersediaUrl: @json(route('reservasi.jadwal-tersedia')),
            oldJadwalId: @json(old('jadwal_id')),
            kecualiJadwalId: @json($reservasi->jadwal_id),
        };
    </script>
@endpush
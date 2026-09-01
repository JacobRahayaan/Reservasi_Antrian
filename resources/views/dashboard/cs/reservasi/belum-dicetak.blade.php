@extends('layouts.dashboard')

@section('title', 'Belum Dicetak Fisik')
@section('page-title', 'Belum Dicetak Fisik')
@section('page-subtitle', 'Dashboard > Belum Dicetak Fisik')
@section('user-initial', 'C')
@section('user-name', 'CS. Amanda')
@section('user-role', 'Customer Service')

@section('content')

    <div class="space-y-6">

        <x-card padding="p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="font-display text-xl font-bold text-pln-navy-950">Belum Dicetak Fisik</h1>
                    <p class="mt-1 text-sm text-pln-slate-600">
                        Reservasi berstatus "Perlu Datang" yang nomor antreannya belum dicetak / tersinkron ke mesin antrean fisik.
                    </p>
                </div>

                <div class="flex items-center gap-2 rounded-lg bg-status-visit/10 px-4 py-2.5 text-sm font-semibold text-status-visit">
                    <x-icon name="exclamation-triangle" class="h-4 w-4" />
                    {{ $total }} reservasi menunggu dicetak
                </div>
            </div>
        </x-card>

        @if ($reservasis->isEmpty())
            <x-card padding="p-6">
                <x-empty-state
                    title="Semua sudah dicetak"
                    description="Tidak ada reservasi Perlu Datang yang masih menunggu sinkronisasi ke mesin antrean fisik."
                />
            </x-card>
        @else
            <x-card padding="p-6">
                <div class="hidden overflow-x-auto sm:block">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-pln-slate-200 text-xs font-semibold uppercase tracking-wider text-pln-slate-400">
                                <th class="px-4 py-2.5">No. Antrean</th>
                                <th class="px-4 py-2.5">Nama Pelanggan</th>
                                <th class="px-4 py-2.5">Layanan</th>
                                <th class="px-4 py-2.5">Tanggal &amp; Jam</th>
                                <th class="px-4 py-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservasis as $reservasi)
                                <tr class="border-b border-pln-slate-100 text-sm">
                                    <td class="px-4 py-3 font-semibold text-pln-navy-900">{{ $reservasi->nomor_antrean }}</td>
                                    <td class="px-4 py-3 text-pln-slate-700">{{ $reservasi->nama }}</td>
                                    <td class="px-4 py-3 text-pln-slate-700">{{ $reservasi->layanan->nama_layanan }}</td>
                                    <td class="px-4 py-3 text-pln-slate-700">
                                        {{ $reservasi->jadwal->tanggal->translatedFormat('d M Y') }},
                                        {{ substr($reservasi->jadwal->jam_mulai, 0, 5) }}–{{ substr($reservasi->jadwal->jam_selesai, 0, 5) }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <a
                                                href="{{ route('cs.reservasi.show', $reservasi) }}"
                                                class="rounded-lg border border-pln-slate-300 px-3 py-1.5 text-xs font-semibold text-pln-slate-700 transition hover:bg-pln-slate-50"
                                            >
                                                Detail
                                            </a>
                                            <form action="{{ route('cs.reservasi.tandai-sinkron-fisik', $reservasi) }}" method="POST">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-pln-navy-900 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-pln-navy-800"
                                                >
                                                    Tandai Sudah Dicetak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="space-y-3 sm:hidden">
                    @foreach ($reservasis as $reservasi)
                        <div class="rounded-xl border border-pln-slate-200 p-4">
                            <div class="flex items-center justify-between">
                                <span class="font-display text-base font-bold text-pln-navy-900">{{ $reservasi->nomor_antrean }}</span>
                                <span class="text-xs text-pln-slate-500">
                                    {{ $reservasi->jadwal->tanggal->translatedFormat('d M') }}, {{ substr($reservasi->jadwal->jam_mulai, 0, 5) }}
                                </span>
                            </div>
                            <p class="mt-1 text-sm font-medium text-pln-slate-800">{{ $reservasi->nama }}</p>
                            <p class="text-xs text-pln-slate-500">{{ $reservasi->layanan->nama_layanan }}</p>

                            <div class="mt-3 flex items-center gap-2">
                                <a
                                    href="{{ route('cs.reservasi.show', $reservasi) }}"
                                    class="flex-1 rounded-lg border border-pln-slate-300 px-3 py-2 text-center text-xs font-semibold text-pln-slate-700"
                                >
                                    Detail
                                </a>
                                <form action="{{ route('cs.reservasi.tandai-sinkron-fisik', $reservasi) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="w-full rounded-lg bg-pln-navy-900 px-3 py-2 text-xs font-semibold text-white"
                                    >
                                        Sudah Dicetak
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 border-t border-pln-slate-100 pt-5">
                    <x-pagination :paginator="$reservasis" />
                </div>
            </x-card>
        @endif

    </div>

@endsection
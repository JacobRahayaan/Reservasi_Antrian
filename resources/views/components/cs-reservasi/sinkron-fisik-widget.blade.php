@props(['daftarBelumSinkron', 'jumlahBelumSinkron'])

@if ($jumlahBelumSinkron > 0)
    <x-card padding="p-6" class="border-2 border-pln-amber-500/40 bg-pln-amber-500/5">
        <x-slot:header>
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-pln-amber-500 text-white">
                    <x-icon name="exclamation-triangle" class="h-4 w-4" />
                </span>
                <div>
                    <h2 class="font-display text-base font-semibold text-pln-navy-900">
                        {{ $jumlahBelumSinkron }} Nomor Antrean Belum Dicetak di Mesin
                    </h2>
                    <p class="text-xs text-pln-slate-500">
                        Koordinasikan dengan security agar tiket fisik tersedia sebelum pelanggan datang.
                    </p>
                </div>
            </div>
        </x-slot:header>

        <div class="space-y-2.5">
            @foreach ($daftarBelumSinkron as $reservasi)
                <div class="flex flex-col gap-3 rounded-lg border border-pln-slate-200 bg-white p-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <span class="font-mono text-sm font-bold text-pln-navy-900">{{ $reservasi->nomor_antrean }}</span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-pln-slate-900">{{ $reservasi->nama }}</p>
                            <p class="text-xs text-pln-slate-400">
                                {{ $reservasi->layanan->nama_layanan }} &middot;
                                {{ $reservasi->jadwal->tanggal->translatedFormat('d M Y') }},
                                {{ substr($reservasi->jadwal->jam_mulai, 0, 5) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <a href="{{ route('cs.reservasi.show', $reservasi) }}" class="text-xs font-semibold text-pln-navy-700 hover:text-pln-navy-900">
                            Detail
                        </a>
                        <form action="{{ route('cs.reservasi.tandai-sinkron-fisik', $reservasi) }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="rounded-lg bg-status-done px-3 py-1.5 text-xs font-semibold text-white transition hover:opacity-90"
                            >
                                Tandai Sudah Dicetak
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($jumlahBelumSinkron > count($daftarBelumSinkron))
            <p class="mt-3 text-center text-xs text-pln-slate-500">
                dan {{ $jumlahBelumSinkron - count($daftarBelumSinkron) }} nomor lainnya menunggu sinkronisasi.
            </p>
        @endif
    </x-card>
@endif
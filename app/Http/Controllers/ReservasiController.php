<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservasiRequest;
use App\Models\Jadwal;
use App\Models\Layanan;
use App\Models\Reservasi;
use App\Services\ReservasiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservasiController extends Controller
{
    public function __construct(private readonly ReservasiService $reservasiService)
    {
    }

    /**
     * Tampilkan form pembuatan reservasi.
     */
    public function create(): View
    {
        $layanans = Layanan::query()
            ->where('is_active', true)
            ->orderBy('nama_layanan')
            ->get();

        return view('pages.reservasi.create', compact('layanans'));
    }

    /**
     * Ambil daftar jam tersedia untuk kombinasi layanan + tanggal (AJAX).
     */
    public function jadwalTersedia(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'layanan_id' => ['required', 'integer', 'exists:layanans,id'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $jadwals = Jadwal::query()
            ->where('layanan_id', $validated['layanan_id'])
            ->whereDate('tanggal', $validated['tanggal'])
            ->whereColumn('kuota_terpakai', '<', 'kuota_maksimal')
            ->orderBy('jam_mulai')
            ->get(['id', 'jam_mulai', 'jam_selesai', 'kuota_maksimal', 'kuota_terpakai']);

        return response()->json([
            'success' => true,
            'message' => 'Daftar jadwal tersedia berhasil diambil.',
            'data' => $jadwals->map(fn (Jadwal $jadwal) => [
                'id' => $jadwal->id,
                'label' => substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5),
                'sisa_kuota' => $jadwal->sisaKuota(),
            ]),
        ]);
    }

    /**
     * Simpan reservasi baru.
     */
    public function store(StoreReservasiRequest $request): RedirectResponse
    {
        $reservasi = $this->reservasiService->buat(
            $request->validated(),
            $request->file('dokumen') ?? []
        );

        return redirect()
            ->route('reservasi.show', $reservasi)
            ->with('success', 'Reservasi berhasil dibuat. Nomor antrean Anda: ' . $reservasi->nomor_antrean);
    }

    /**
     * Tampilkan halaman detail reservasi.
     */
    public function show(Reservasi $reservasi): View
    {
        $reservasi->load(['layanan', 'jadwal', 'dokumen', 'statusHistories', 'notes']);

        return view('pages.reservasi.show', compact('reservasi'));
    }
}
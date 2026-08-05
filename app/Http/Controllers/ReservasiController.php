<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservasiRequest;
use App\Models\DokumenReservasi;
use App\Models\Jadwal;
use App\Models\Layanan;
use App\Models\Reservasi;
use App\Services\ReservasiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            ->tersediaUntukPelanggan()
            ->where('layanan_id', $validated['layanan_id'])
            ->whereDate('tanggal', $validated['tanggal'])
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
     * Tampilkan halaman detail reservasi (read-only).
     */
    public function show(Reservasi $reservasi): View
    {
        $reservasi->load([
            'layanan:id,nama_layanan,kode_layanan',
            'jadwal:id,tanggal,jam_mulai,jam_selesai',
            'dokumen:id,reservasi_id,nama_file_asli,ukuran_file,created_at',
            'statusHistories' => fn ($query) => $query->oldest('changed_at'),
            'notes' => fn ($query) => $query->latest()->limit(1),
        ]);

        return view('pages.reservasi.show', compact('reservasi'));
    }

    /**
     * Unduh dokumen pendukung milik sebuah reservasi.
     */
    public function downloadDokumen(Reservasi $reservasi, DokumenReservasi $dokumen): StreamedResponse
    {
        abort_unless($dokumen->reservasi_id === $reservasi->id, 404);
        abort_unless(Storage::disk('local')->exists($dokumen->path_file), 404);

        return Storage::disk('local')->download(
            $dokumen->path_file,
            $dokumen->nama_file_asli
        );
    }

    /**
     * Tampilkan (preview) dokumen pendukung di tab baru tanpa mengunduhnya
     * secara paksa. Dipakai bersama oleh halaman pelanggan (Sprint 3) dan
     * halaman Detail Reservasi Customer Service (Sprint 6) — satu logika
     * file-serving, tanpa duplikasi controller.
     */
    public function previewDokumen(Reservasi $reservasi, DokumenReservasi $dokumen): StreamedResponse
    {
        abort_unless($dokumen->reservasi_id === $reservasi->id, 404);
        abort_unless(Storage::disk('local')->exists($dokumen->path_file), 404);

        return Storage::disk('local')->response(
            $dokumen->path_file,
            $dokumen->nama_file_asli,
            ['Content-Disposition' => 'inline; filename="' . $dokumen->nama_file_asli . '"']
        );
    }
}
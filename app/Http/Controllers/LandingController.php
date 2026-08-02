<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LandingController extends Controller
{
    /**
     * Tampilkan halaman landing publik SIRA-PLN.
     */
    public function index(): View
    {
        $steps = [
            [
                'number' => 1,
                'icon' => 'pencil-square',
                'title' => 'Isi Formulir',
                'description' => 'Lengkapi data diri, pilih jenis layanan, tanggal, jam, dan tuliskan keluhan Anda.',
            ],
            [
                'number' => 2,
                'icon' => 'ticket',
                'title' => 'Dapatkan Nomor Antrean',
                'description' => 'Setelah reservasi berhasil, Anda akan mendapatkan nomor antrean sesuai dengan jadwal yang dipilih.',
            ],
            [
                'number' => 3,
                'icon' => 'building-office-2',
                'title' => 'Datang Sesuai Jadwal',
                'description' => 'Datang ke kantor PLN sesuai jadwal dan nomor antrean yang telah diberikan.',
            ],
        ];

        $services = [
            [
                'variant' => 'amber',
                'icon' => 'bolt',
                'title' => 'Pasang Baru / Tambah Daya',
                'description' => 'Layanan pengajuan pasang baru listrik atau permohonan penambahan daya.',
                'href' => '#layanan',
            ],
            [
                'variant' => 'blue',
                'icon' => 'document-text',
                'title' => 'Tagihan Bulanan',
                'description' => 'Informasi dan layanan terkait tagihan listrik bulanan Anda.',
                'href' => '#layanan',
            ],
            [
                'variant' => 'green',
                'icon' => 'wrench-screwdriver',
                'title' => 'Gangguan',
                'description' => 'Laporkan gangguan kelistrikan di area Anda.',
                'href' => '#layanan',
            ],
        ];

        return view('pages.landing', compact('steps', 'services'));
    }
}
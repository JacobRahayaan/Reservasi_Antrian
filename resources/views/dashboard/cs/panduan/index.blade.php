@extends('layouts.dashboard')

@section('title', 'Panduan')
@section('page-title', 'Panduan')
@section('page-subtitle', 'Dashboard > Panduan')
@section('user-initial', 'C')
@section('user-name', 'CS. Amanda')
@section('user-role', 'Customer Service')

@section('content')

    <div class="mx-auto max-w-4xl space-y-6">

        <x-card padding="p-6">
            <div class="flex items-start gap-4">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-pln-navy-900 text-white">
                    <x-icon name="headphones" class="h-6 w-6" />
                </span>
                <div>
                    <h1 class="font-display text-xl font-bold text-pln-navy-950">Panduan Customer Service</h1>
                    <p class="mt-1 text-sm text-pln-slate-600">
                        Ringkasan alur kerja dan cara menggunakan sistem SIRA-PLN untuk petugas Customer Service.
                    </p>
                </div>
            </div>
        </x-card>

        <x-panduan.section icon="check-circle" title="Ringkasan Dashboard" number="1">
            <p>
                Dashboard menampilkan ringkasan aktivitas reservasi hari ini: total reservasi, jumlah per status,
                distribusi per jenis layanan, dan grafik reservasi per jam kedatangan.
            </p>
            <p>
                Gunakan filter tanggal di bagian atas halaman untuk melihat ringkasan pada tanggal lain, dan klik
                salah satu kartu status untuk langsung membuka daftar reservasi dengan filter status tersebut.
            </p>
        </x-panduan.section>

        <x-panduan.section icon="clipboard-list" title="Alur Kerja Reservasi" number="2">
            <ol class="list-inside list-decimal space-y-2">
                <li>Buka menu <strong>Daftar Reservasi</strong>, pilih tab <strong>Reservasi Aktif</strong> untuk melihat reservasi yang perlu ditindaklanjuti.</li>
                <li>Klik <strong>Lihat Detail</strong> pada reservasi yang ingin direview.</li>
                <li>Baca keluhan pelanggan dan periksa dokumen pendukung yang diunggah (jika ada).</li>
                <li>Tentukan apakah keluhan dapat diselesaikan tanpa pelanggan datang (<strong>Selesai Online</strong>) atau pelanggan perlu datang ke kantor (<strong>Perlu Datang</strong>).</li>
                <li>Tulis catatan pada kolom <strong>Catatan Customer Service</strong> agar pelanggan mengetahui perkembangan reservasinya.</li>
                <li>Pilih status baru pada kartu <strong>Ubah Status Reservasi</strong>, lalu klik <strong>Perbarui Status</strong>.</li>
            </ol>
        </x-panduan.section>

        <x-panduan.section icon="ticket" title="Arti Setiap Status" number="3">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-pln-slate-200 text-xs font-semibold uppercase tracking-wider text-pln-slate-400">
                            <th class="py-2 pr-4">Status</th>
                            <th class="py-2">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarStatus as $status)
                            <tr class="border-b border-pln-slate-100 last:border-0">
                                <td class="py-2.5 pr-4">
                                    <x-badge :variant="$status->badgeVariant()">{{ $status->label() }}</x-badge>
                                </td>
                                <td class="py-2.5 text-pln-slate-600">{{ $status->hintCs() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-panduan.section>

        <x-panduan.section icon="document-text" title="Aturan Perubahan Status" number="4">
            <p>Status reservasi hanya dapat berubah mengikuti alur berikut, tidak boleh melompat tahap:</p>
            <ul class="list-inside list-disc space-y-1.5">
                <li><strong>Menunggu Review</strong> → Perlu Datang, Selesai Online, atau Dibatalkan</li>
                <li><strong>Perlu Datang</strong> → Selesai atau Dibatalkan</li>
                <li><strong>Selesai Online</strong> → Selesai</li>
                <li><strong>Selesai</strong> dan <strong>Dibatalkan</strong> bersifat final, tidak dapat diubah lagi</li>
            </ul>
            <p>
                Sistem akan otomatis menonaktifkan pilihan status yang tidak valid dari status saat ini, sehingga
                Anda tidak perlu menghafal urutan ini  cukup pilih dari opsi yang tersedia.
            </p>
        </x-panduan.section>

        <x-panduan.section icon="calendar" title="Menggunakan Kalender Jadwal" number="5">
            <p>
                Menu <strong>Kalender Jadwal</strong> menampilkan ringkasan kuota per tanggal dalam satu bulan.
                Warna pada setiap tanggal menunjukkan tingkat keterisian kuota: hijau (masih longgar), kuning
                (mulai menipis), dan merah (hampir penuh).
            </p>
            <p>Klik tanggal mana pun yang memiliki jadwal untuk langsung melihat daftar reservasi pada tanggal tersebut.</p>
        </x-panduan.section>

        <x-card padding="p-6">
            <x-slot:header>
                <h2 class="font-display text-base font-semibold text-pln-navy-900">Pertanyaan yang Sering Diajukan</h2>
            </x-slot:header>

            <div class="space-y-2.5">
                <x-panduan.faq-item pertanyaan="Bagaimana jika pelanggan tidak bisa dihubungi untuk konfirmasi Selesai Online?">
                    Tulis catatan yang menjelaskan upaya yang sudah dilakukan (mis. sudah dicoba dihubungi 2 kali),
                    lalu ubah status sesuai kondisi terakhir. Jika keluhan belum benar-benar terselesaikan, pertimbangkan
                    mengubah ke status <strong>Perlu Datang</strong> agar pelanggan diminta datang langsung.
                </x-panduan.faq-item>

                <x-panduan.faq-item pertanyaan="Apakah saya bisa membatalkan reservasi pelanggan?">
                    Pada versi sistem saat ini, pembatalan reservasi hanya dapat dilakukan oleh pelanggan sendiri
                    melalui halaman Detail Reservasi mereka. Customer Service dapat mengubah status menjadi
                    <strong>Dibatalkan</strong> dari kartu Ubah Status jika pelanggan meminta pembatalan melalui telepon.
                </x-panduan.faq-item>

                <x-panduan.faq-item pertanyaan="Kenapa tombol status tertentu tidak bisa saya klik?">
                    Tombol yang berwarna abu-abu berarti status tersebut bukan transisi yang valid dari status
                    reservasi saat ini. Lihat bagian "Aturan Perubahan Status" di atas untuk urutan yang berlaku.
                </x-panduan.faq-item>

                <x-panduan.faq-item pertanyaan="Bagaimana cara melihat dokumen yang diunggah pelanggan?">
                    Pada halaman Detail Reservasi, buka kartu <strong>Dokumen yang Diunggah</strong>. Klik ikon mata
                    untuk melihat pratinjau file di tab baru, atau ikon unduh untuk menyimpan file ke perangkat Anda.
                </x-panduan.faq-item>
            </div>
        </x-card>

        <div class="rounded-2xl bg-pln-navy-900 p-6 text-center sm:p-8">
            <p class="font-display text-base font-semibold text-white">Butuh bantuan lebih lanjut?</p>
            <p class="mx-auto mt-1.5 max-w-sm text-sm text-pln-slate-300">
                Hubungi tim IT Support internal atau supervisor Anda jika mengalami kendala teknis di luar panduan ini.
            </p>
            <a
                href="tel:123"
                class="mt-4 inline-flex items-center gap-2 rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-pln-navy-900 transition hover:bg-pln-slate-100"
            >
                <x-icon name="phone" class="h-4 w-4" />
                Hubungi PLN 123
            </a>
        </div>

    </div>

@endsection
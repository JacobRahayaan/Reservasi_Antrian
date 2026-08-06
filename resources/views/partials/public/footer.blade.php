<footer id="informasi" class="border-t border-pln-slate-200 bg-white print:hidden">
    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">

            <div class="sm:col-span-2 lg:col-span-1">
                <div class="flex items-center gap-2.5">
                    <img
						src="{{ asset('images/logo-pln.png') }}"
						alt="Logo PLN"
						class="h-12 w-auto"
					/>
                    <span class="leading-tight">
                        <span class="block text-[11px] font-medium text-pln-slate-500">Unit Layanan Pelanggan Manado Selatan</span>
                    </span>
                </div>
                <p class="mt-4 max-w-xs text-sm leading-relaxed text-pln-slate-600">
                    PLN berkomitmen memberikan layanan terbaik untuk kenyamanan Anda.
                </p>

                <div class="mt-5 flex items-center gap-3">
                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full bg-pln-slate-100 text-pln-slate-500 transition hover:bg-pln-navy-900 hover:text-white" aria-label="Facebook PLN">
                        <x-icon name="facebook" class="h-4 w-4" />
                    </a>
                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full bg-pln-slate-100 text-pln-slate-500 transition hover:bg-pln-navy-900 hover:text-white" aria-label="Instagram PLN">
                        <x-icon name="instagram" class="h-4 w-4" />
                    </a>
                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full bg-pln-slate-100 text-pln-slate-500 transition hover:bg-pln-navy-900 hover:text-white" aria-label="Twitter PLN">
                        <x-icon name="twitter" class="h-4 w-4" />
                    </a>
                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full bg-pln-slate-100 text-pln-slate-500 transition hover:bg-pln-navy-900 hover:text-white" aria-label="YouTube PLN">
                        <x-icon name="youtube" class="h-4 w-4" />
                    </a>
                </div>
            </div>

            <div>
                <h3 class="font-display text-sm font-semibold text-pln-navy-900">Informasi</h3>
                <ul class="mt-4 space-y-3 text-sm text-pln-slate-600">
                    <li><a href="#" class="hover:text-pln-navy-900">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-pln-navy-900">Syarat &amp; Ketentuan</a></li>
                    <li><a href="#" class="hover:text-pln-navy-900">Kebijakan Privasi</a></li>
                    <li><a href="#" class="hover:text-pln-navy-900">Panduan Pengguna</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-display text-sm font-semibold text-pln-navy-900">Layanan</h3>
                <ul class="mt-4 space-y-3 text-sm text-pln-slate-600">
                    <li><a href="#layanan" class="hover:text-pln-navy-900">Pasang Baru / Tambah Daya</a></li>
                    <li><a href="#layanan" class="hover:text-pln-navy-900">Tagihan Bulanan</a></li>
                    <li><a href="#layanan" class="hover:text-pln-navy-900">Gangguan</a></li>
                    <li><a href="{{ route('reservasi.cek-status.form') }}" class="hover:text-pln-navy-900">Cek Status Reservasi</a></li>
                </ul>
            </div>

            <div id="kontak">
                <h3 class="font-display text-sm font-semibold text-pln-navy-900">Kontak</h3>
                <ul class="mt-4 space-y-3 text-sm text-pln-slate-600">
                    <li class="flex items-start gap-2">
                        <x-icon name="map-pin" class="mt-0.5 h-4 w-4 shrink-0 text-pln-slate-400" />
                        <span>
							Jl. Ahmad Yani No. 17<br>
							Sario Utara, Kec. Sario<br>
							Kota Manado, Sulawesi Utara
						</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <x-icon name="phone" class="h-4 w-4 shrink-0 text-pln-slate-400" />
                        <a href="tel:02112345678" class="hover:text-pln-navy-900">(021) 1234 5678</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <x-icon name="envelope" class="h-4 w-4 shrink-0 text-pln-slate-400" />
                        <a href="mailto:info@pln.co.id" class="hover:text-pln-navy-900">info@pln.co.id</a>
                    </li>
                </ul>
            </div>

        </div>

        <div class="mt-10 border-t border-pln-slate-200 pt-6 text-center text-xs text-pln-slate-400">
            <p>&copy; {{ now()->year }} PT PLN (Persero). All rights reserved.</p>
        </div>
    </div>
</footer>
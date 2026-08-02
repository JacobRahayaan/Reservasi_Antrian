<section class="relative overflow-hidden bg-pln-slate-50">
    <div class="mx-auto grid max-w-6xl items-center gap-12 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-[45%_55%] lg:py-28 lg:px-8">

        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-pln-navy-700">
                Sistem Reservasi Online
            </p>

            <h1 class="mt-3 font-display text-3xl font-bold leading-tight tracking-tight text-pln-navy-950 sm:text-4xl lg:text-[2.75rem]">
                Reservasi Layanan Pelanggan PLN
            </h1>

            <p class="mt-5 max-w-md text-base leading-relaxed text-pln-slate-600">
                Kini reservasi layanan di PLN lebih mudah dan cepat. Pilih layanan, tanggal, dan jam kedatangan sesuai kebutuhan Anda.
            </p>

            <div class="mt-7 flex flex-wrap items-center gap-4">
                <x-button href="#cara-reservasi" variant="primary" size="lg">
                    <x-icon name="document-text" class="h-5 w-5" />
                    Buat Reservasi Sekarang
                </x-button>
            </div>

            <p class="mt-5 flex items-center gap-2 text-sm text-pln-slate-500">
                <x-icon name="clock" class="h-4 w-4" />
                Senin – Jumat | 08.00 – 16.00 WITA
            </p>
        </div>

        <div class="relative flex justify-center">
			<img
				src="{{ asset('images/hero.png') }}"
				alt="Reservasi Layanan PLN"
				class="w-full h-auto"
			>
		</div>

    </div>
</section>
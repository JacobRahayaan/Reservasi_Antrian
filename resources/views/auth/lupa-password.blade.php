<x-layouts.guest>

    @section('title', 'Lupa Password')

    <div class="w-full max-w-md text-center">

        <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-pln-navy-900/5 text-pln-navy-700">
            <x-icon name="lock" class="h-7 w-7" />
        </span>

        <h1 class="mt-5 font-display text-2xl font-bold tracking-tight text-pln-navy-950">
            Lupa Password?
        </h1>

        <x-card padding="p-6" class="mt-6 text-left">
            <p class="text-sm leading-relaxed text-pln-slate-600">
                Untuk saat ini, reset password belum dapat dilakukan secara mandiri melalui email.
                Silakan hubungi Administrator untuk melakukan reset password akun Anda.
            </p>

            <div class="mt-5 flex items-start gap-3 rounded-lg bg-pln-navy-900/5 p-4">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-pln-navy-900 text-white">
                    <x-icon name="headphones" class="h-4 w-4" />
                </span>
                <div>
                    <p class="text-sm font-semibold text-pln-navy-900">Hubungi Admin PLN</p>
                    <p class="mt-0.5 text-xs leading-relaxed text-pln-slate-500">
                        Sampaikan email akun Anda kepada Administrator sistem untuk diproses.
                    </p>
                </div>
            </div>

            <x-button href="{{ route('login') }}" variant="ghost" size="md" class="mt-6 w-full">
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Halaman Login
            </x-button>
        </x-card>

    </div>

</x-layouts.guest>
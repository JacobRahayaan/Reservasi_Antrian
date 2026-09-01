<x-layouts.guest>

    @section('title', 'Masuk ke Sistem')

    <div class="w-full max-w-md">

        <div class="mb-6 text-center">
            <span class="inline-flex items-center gap-2 rounded-full bg-pln-navy-900/5 px-3 py-1 text-xs font-medium text-pln-navy-800">
                <span class="h-1.5 w-1.5 rounded-full bg-pln-amber-500"></span>
                Sistem Reservasi Online
            </span>
            <h1 class="mt-4 font-display text-3xl font-bold tracking-tight text-pln-navy-950">
                Masuk ke <span class="text-pln-navy-700">Sistem Reservasi Online-PLN</span>
            </h1>
            <p class="mt-2 text-sm text-pln-slate-600">
                Kelola reservasi pelanggan lebih cepat, lebih mudah, dan lebih efisien.
            </p>
        </div>

        <x-card padding="p-6">
            <x-slot:header>
                <div>
                    <h2 class="font-display text-base font-semibold text-pln-navy-900">Selamat Datang</h2>
                    <p class="mt-0.5 text-sm text-pln-slate-500">Silakan login untuk melanjutkan.</p>
                </div>
            </x-slot:header>

            @if ($errors->any())
                <x-alert variant="danger" title="Login gagal" class="mb-5">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            <form action="{{ route('login.attempt') }}" method="POST" class="space-y-5" novalidate>
                @csrf

                <div>
                    <label for="peran" class="mb-1.5 block text-sm font-medium text-pln-slate-900">
                        Login Sebagai <span class="text-status-cancel">*</span>
                    </label>
                    <div class="relative">
                        <x-icon name="user" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-pln-slate-400" />
                        <select
                            name="peran"
                            id="peran"
                            required
                            class="block w-full appearance-none rounded-lg border py-2.5 pl-10 pr-10 text-sm text-pln-slate-900 focus:outline-none focus:ring-2 {{ $errors->has('peran') ? 'border-status-cancel focus:ring-status-cancel/40' : 'border-pln-slate-200 focus:border-pln-navy-700 focus:ring-pln-navy-700/20' }}"
                        >
                            <option value="" disabled @selected(old('peran', '') === '')>Pilih Peran</option>
                            <option value="admin" @selected(old('peran') === 'admin')>Administrator</option>
                            <option value="petugas" @selected(old('peran') === 'petugas')>Customer Service</option>
                        </select>
                        <x-icon name="chevron-down" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-pln-slate-400" />
                    </div>
                    @error('peran')
                        <p class="mt-1.5 text-sm text-status-cancel">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-start gap-3 rounded-lg bg-pln-navy-900/5 p-4">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-pln-navy-900 text-white">
                        <x-icon name="user" class="h-4 w-4" />
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-pln-navy-900">Pilih peran Anda</p>
                        <p class="mt-0.5 text-xs leading-relaxed text-pln-slate-500">
                            Pilih peran untuk melihat informasi yang sesuai dengan akses Anda.
                        </p>
                    </div>
                </div>

                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-pln-slate-900">
                        Email <span class="text-status-cancel">*</span>
                    </label>
                    <div class="relative">
                        <x-icon name="envelope" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-pln-slate-400" />
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            placeholder="Masukkan email Anda"
                            required
                            autofocus
                            class="block w-full rounded-lg border py-2.5 pl-10 pr-3.5 text-sm text-pln-slate-900 placeholder:text-pln-slate-400 focus:outline-none focus:ring-2 {{ $errors->has('email') ? 'border-status-cancel focus:ring-status-cancel/40' : 'border-pln-slate-200 focus:border-pln-navy-700 focus:ring-pln-navy-700/20' }}"
                        >
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-sm text-status-cancel">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-sm font-medium text-pln-slate-900">
                        Password <span class="text-status-cancel">*</span>
                    </label>
                    <div class="relative">
                        <x-icon name="lock" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-pln-slate-400" />
                        <input
                            type="password"
                            name="password"
                            id="password"
                            data-password-input
                            placeholder="Masukkan password Anda"
                            required
                            class="block w-full rounded-lg border py-2.5 pl-10 pr-10 text-sm text-pln-slate-900 placeholder:text-pln-slate-400 focus:outline-none focus:ring-2 {{ $errors->has('password') ? 'border-status-cancel focus:ring-status-cancel/40' : 'border-pln-slate-200 focus:border-pln-navy-700 focus:ring-pln-navy-700/20' }}"
                        >
                        <button
                            type="button"
                            data-password-toggle
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-pln-slate-400 transition hover:text-pln-slate-600"
                            aria-label="Tampilkan atau sembunyikan password"
                        >
                            <x-icon data-password-icon-show name="eye" class="h-4 w-4" />
                            <x-icon data-password-icon-hide name="eye-slash" class="hidden h-4 w-4" />
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-sm text-status-cancel">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-pln-slate-600">
                        <input
                            type="checkbox"
                            name="ingat_saya"
                            value="1"
                            class="h-4 w-4 rounded border-pln-slate-300 text-pln-navy-700 focus:ring-pln-navy-700/30"
                        >
                        Ingat Saya
                    </label>
                    <a href="{{ route('password.lupa') }}" class="text-sm font-semibold text-pln-navy-700 hover:text-pln-navy-900">
                        Lupa Password?
                    </a>
                </div>

                <x-button type="submit" variant="primary" size="lg" class="w-full">
                    <x-icon name="lock" class="h-4 w-4" />
                    Masuk ke Sistem
                </x-button>
            </form>
        </x-card>

    </div>

</x-layouts.guest>
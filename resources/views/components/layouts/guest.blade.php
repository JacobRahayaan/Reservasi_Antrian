<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Masuk')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full bg-pln-slate-50 font-sans text-pln-slate-900 antialiased">

    <div class="flex min-h-full flex-col">

        <header class="border-b border-pln-slate-200 bg-white">
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6 lg:px-8">
				<a href="{{ route('landing') }}" class="flex items-center gap-2.5">
					<img
						src="{{ asset('images/logo-pln.png') }}"
						alt="Logo PLN"
						class="h-10 w-auto"
					/>
					<span class="leading-tight">
						<span class="block font-display text-base font-bold tracking-tight text-pln-navy-900">PLN</span>
						<span class="block text-[11px] font-medium text-pln-slate-500">Unit Layanan Pelanggan</span>
					</span>
				</a>

                <a href="{{ route('landing') }}" class="text-sm font-medium text-pln-slate-500 transition hover:text-pln-navy-900">
                    Kembali ke Beranda
                </a>
            </div>
        </header>

        <main class="flex flex-1 items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>

        <footer class="border-t border-pln-slate-200 bg-white py-6">
            <p class="text-center text-xs text-pln-slate-400">
                &copy; {{ now()->year }} PT PLN (Persero). Halaman ini khusus untuk staf internal.
            </p>
        </footer>

    </div>

    @stack('scripts')
</body>
</html>
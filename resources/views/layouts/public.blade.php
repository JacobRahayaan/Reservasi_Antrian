<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Beranda') — SIRA-PLN</title>
    <meta name="description" content="Jadwalkan kedatangan Anda ke kantor PLN, sampaikan keluhan lebih awal, dan dapatkan nomor antrean tanpa perlu antre di tempat.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="flex min-h-full flex-col bg-pln-slate-50 font-sans text-pln-slate-900 antialiased">

    @include('partials.public.navbar')

    <main class="flex-1">
        @yield('content')
    </main>

    @include('partials.public.footer')

    @stack('scripts')
</body>
</html>
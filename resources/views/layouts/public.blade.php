<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Beranda') — SIRA-PLN</title>
    <meta name="description" content="@yield('meta_description', 'Jadwalkan kedatangan Anda ke kantor PLN, sampaikan keluhan lebih awal, dan dapatkan nomor antrean tanpa perlu antre di tempat.')">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="SIRA-PLN">
    <meta property="og:title" content="@yield('title', 'Beranda') — SIRA-PLN">
    <meta property="og:description" content="@yield('meta_description', 'Jadwalkan kedatangan Anda ke kantor PLN, sampaikan keluhan lebih awal, dan dapatkan nomor antrean tanpa perlu antre di tempat.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="flex min-h-full flex-col bg-pln-slate-50 font-sans text-pln-slate-900 antialiased">

    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-pln-navy-900 focus:px-4 focus:py-2 focus:text-sm focus:font-medium focus:text-white">
        Lewati ke konten utama
    </a>

    @include('partials.public.navbar')

    <main id="main-content" class="flex-1">
        @yield('content')
    </main>

    @include('partials.public.footer')

    <x-landing.back-to-top />

    @stack('scripts')
</body>
</html>
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full bg-pln-slate-100 font-sans text-pln-slate-900 antialiased">

    <div class="flex h-full">

        @include('partials.dashboard.sidebar')

        <div
            data-sidebar-overlay
            class="fixed inset-0 z-30 hidden bg-pln-navy-950/40 lg:hidden"
        ></div>

        <div class="flex min-h-full flex-1 flex-col lg:pl-64">

            @include('partials.dashboard.topbar')

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                @yield('content')
            </main>

        </div>
    </div>

    @stack('scripts')
</body>
</html>
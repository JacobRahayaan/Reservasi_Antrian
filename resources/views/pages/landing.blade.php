@extends('layouts.public')

@section('title', 'Beranda')
@section('meta_description', 'Reservasi layanan pelanggan PLN secara online. Pilih layanan, tanggal, dan jam kedatangan, lalu dapatkan nomor antrean tanpa perlu mengantre di kantor.')

@section('content')

    <x-landing.hero />

    <section id="cara-reservasi" class="bg-pln-slate-50 py-16 sm:py-20">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-xl text-center">
                <h2 class="font-display text-2xl font-bold tracking-tight text-pln-navy-950 sm:text-3xl">
                    Cara Reservasi
                </h2>
                <p class="mt-3 text-base text-pln-slate-600">
                    Ikuti langkah mudah berikut untuk mendapatkan nomor antrean Anda.
                </p>
            </div>

            <ol class="mt-12 sm:grid sm:grid-cols-3 sm:gap-8">
                @foreach ($steps as $step)
                    <x-landing.step-card
                        :number="$step['number']"
                        :icon="$step['icon']"
                        :title="$step['title']"
                        :description="$step['description']"
                        :last="$loop->last"
                    />
                @endforeach
            </ol>
        </div>
    </section>

    <section id="layanan" class="bg-white py-16 sm:py-20">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-xl text-center">
                <h2 class="font-display text-2xl font-bold tracking-tight text-pln-navy-950 sm:text-3xl">
                    Jenis Layanan
                </h2>
                <p class="mt-3 text-base text-pln-slate-600">
                    Pilih layanan sesuai kebutuhan Anda.
                </p>
            </div>

            <div class="mt-12 hidden sm:grid sm:grid-cols-3 sm:gap-6">
                @foreach ($services as $service)
                    <x-landing.service-card
                        :variant="$service['variant']"
                        :icon="$service['icon']"
                        :title="$service['title']"
                        :description="$service['description']"
                        :href="$service['href']"
                    />
                @endforeach
            </div>

            <div class="mt-12 space-y-3 sm:hidden">
                @foreach ($services as $service)
                    <x-landing.service-row
                        :variant="$service['variant']"
                        :icon="$service['icon']"
                        :title="$service['title']"
                        :href="$service['href']"
                    />
                @endforeach
            </div>

            <div class="mt-10">
                <x-landing.help-banner />
            </div>
        </div>
    </section>

@endsection
@props([
    'nama' => 'Admin',
    'role' => 'admin',
])
<div class="flex flex-col items-center gap-8 overflow-hidden rounded-2xl bg-[#0b2345] p-8 lg:flex-row">
    <!-- Text -->
    <div class="flex-1">
        <h2 class="font-display text-3xl font-bold text-white">
            Selamat datang{{ $role === 'cs' ? ' kembali' : '' }}, {{ $nama }} 
        </h2>
        <p class="mt-3 text-base leading-7 text-pln-slate-300">
            Berikut ringkasan aktivitas {{ $role === 'cs' ? 'reservasi' : 'sistem' }} hari ini.
        </p>
    </div>

    <!-- Image -->
    <div class="flex justify-center lg:justify-end">
        @if ($role === 'cs')
            <img
                src="{{ asset('images/welcome-cs.png') }}"
                alt="Dashboard Admin"
                class="max-h-56 w-auto object-contain"
            >
        @else
            <img
                src="{{ asset('images/welcome-admin.png') }}"
                alt="Dashboard Admin"
                class="max-h-56 w-auto object-contain"
            >
        @endif
    </div>
</div>
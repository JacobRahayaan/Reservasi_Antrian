@props(['layanan' => null])

<div class="grid gap-5 sm:grid-cols-2">

    <x-input
        label="Nama Layanan"
        name="nama_layanan"
        :value="old('nama_layanan', $layanan?->nama_layanan)"
        placeholder="Contoh: Pasang Baru / Tambah Daya"
        required
        :error="$errors->first('nama_layanan')"
    />

    <div class="w-full">

        <label
            for="kode_layanan"
            class="mb-1.5 block text-sm font-medium text-pln-slate-900"
        >
            Prefix Nomor Antrean
            <span class="text-status-cancel">*</span>
        </label>

        <input
            type="text"
            name="kode_layanan"
            id="kode_layanan"
            value="{{ old('kode_layanan', $layanan?->kode_layanan) }}"
            placeholder="Contoh: A"
            maxlength="10"
            autocomplete="off"
            required
            style="text-transform: uppercase"
            oninput="this.value=this.value.toUpperCase()"
            class="block w-full rounded-lg border px-3.5 py-2.5 text-sm font-mono text-pln-slate-900 placeholder:font-sans placeholder:text-pln-slate-400 focus:outline-none focus:ring-2 {{ $errors->has('kode_layanan') ? 'border-status-cancel focus:ring-status-cancel/40' : 'border-pln-slate-200 focus:border-pln-navy-700 focus:ring-pln-navy-700/20' }}"
        >

        <p class="mt-1.5 text-xs text-pln-slate-400">
            Dipakai sebagai awalan nomor antrean.
            Contoh:
            <strong>A</strong>
            menghasilkan
            <strong>A001</strong>,
            <strong>A002</strong>,
            dan seterusnya.
        </p>

        @error('kode_layanan')
            <p class="mt-1.5 text-sm text-status-cancel">
                {{ $message }}
            </p>
        @enderror

    </div>

</div>


<div class="mt-5">

    <label
        for="deskripsi"
        class="mb-1.5 block text-sm font-medium text-pln-slate-900"
    >
        Deskripsi
    </label>

    <textarea
        name="deskripsi"
        id="deskripsi"
        rows="3"
        maxlength="255"
        placeholder="Jelaskan singkat layanan ini untuk pelanggan..."
        class="block w-full rounded-lg border px-3.5 py-2.5 text-sm text-pln-slate-900 placeholder:text-pln-slate-400 focus:outline-none focus:ring-2 {{ $errors->has('deskripsi') ? 'border-status-cancel focus:ring-status-cancel/40' : 'border-pln-slate-200 focus:border-pln-navy-700 focus:ring-pln-navy-700/20' }}"
    >{{ old('deskripsi', $layanan?->deskripsi) }}</textarea>

    @error('deskripsi')
        <p class="mt-1.5 text-sm text-status-cancel">
            {{ $message }}
        </p>
    @enderror

</div>


<div class="mt-5 grid gap-5 sm:grid-cols-2">

    <x-input
        label="Estimasi Waktu Minimal (menit)"
        name="estimasi_menit_min"
        type="number"
        min="1"
        step="1"
        :value="old('estimasi_menit_min', $layanan?->estimasi_menit_min)"
        placeholder="Contoh: 60"
        :error="$errors->first('estimasi_menit_min')"
    />

    <x-input
        label="Estimasi Waktu Maksimal (menit)"
        name="estimasi_menit_max"
        type="number"
        min="1"
        step="1"
        :value="old('estimasi_menit_max', $layanan?->estimasi_menit_max)"
        placeholder="Contoh: 120"
        :error="$errors->first('estimasi_menit_max')"
    />

</div>


<div class="mt-5">

    <label class="mb-1.5 block text-sm font-medium text-pln-slate-900">
        Status
        <span class="text-status-cancel">*</span>
    </label>

    <div class="flex gap-3">

        <label class="flex flex-1 cursor-pointer items-center gap-2.5 rounded-lg border-2 border-pln-slate-200 px-4 py-3 transition has-[:checked]:border-status-done has-[:checked]:bg-status-done/5">

            <input
                type="radio"
                name="is_active"
                value="1"
                @checked(old('is_active', $layanan?->is_active ?? 1) == 1)
                class="h-4 w-4 text-status-done focus:ring-status-done"
            >

            <span class="text-sm font-medium text-pln-slate-900">
                Aktif
            </span>

        </label>

        <label class="flex flex-1 cursor-pointer items-center gap-2.5 rounded-lg border-2 border-pln-slate-200 px-4 py-3 transition has-[:checked]:border-pln-slate-400 has-[:checked]:bg-pln-slate-100">

            <input
                type="radio"
                name="is_active"
                value="0"
                @checked(old('is_active', $layanan?->is_active ?? 1) == 0)
                class="h-4 w-4 text-pln-slate-500 focus:ring-pln-slate-400"
            >

            <span class="text-sm font-medium text-pln-slate-900">
                Nonaktif
            </span>

        </label>

    </div>

    @error('is_active')
        <p class="mt-1.5 text-sm text-status-cancel">
            {{ $message }}
        </p>
    @enderror

</div>
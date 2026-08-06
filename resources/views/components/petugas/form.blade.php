@props(['petugas' => null])

<div class="grid gap-5 sm:grid-cols-2">
    <x-input
        label="Nama Petugas"
        name="nama_petugas"
        :value="old('nama_petugas', $petugas?->nama_petugas)"
        placeholder="Contoh: CS. Amanda"
        required
        :error="$errors->first('nama_petugas')"
    />

    <x-input
        label="Email"
        name="email"
        type="email"
        :value="old('email', $petugas?->email)"
        placeholder="Contoh: amanda@pln.co.id"
        required
        :error="$errors->first('email')"
    />
</div>

<div class="mt-5">
    <x-input
        label="Nomor HP (Opsional)"
        name="no_hp"
        :value="old('no_hp', $petugas?->no_hp)"
        placeholder="Contoh: 081234567890"
        :error="$errors->first('no_hp')"
    />
</div>

<div class="mt-5">
    <label class="mb-1.5 block text-sm font-medium text-pln-slate-900">
        Status <span class="text-status-cancel">*</span>
    </label>
    <div class="flex gap-3">
        <label class="flex flex-1 cursor-pointer items-center gap-2.5 rounded-lg border-2 border-pln-slate-200 px-4 py-3 has-[:checked]:border-status-done has-[:checked]:bg-status-done/5">
            <input
                type="radio"
                name="is_active"
                value="1"
                @checked(old('is_active', $petugas?->is_active ?? true))
                class="h-4 w-4 text-status-done focus:ring-status-done"
            >
            <span class="text-sm font-medium text-pln-slate-900">Aktif</span>
        </label>
        <label class="flex flex-1 cursor-pointer items-center gap-2.5 rounded-lg border-2 border-pln-slate-200 px-4 py-3 has-[:checked]:border-pln-slate-400 has-[:checked]:bg-pln-slate-100">
            <input
                type="radio"
                name="is_active"
                value="0"
                @checked(! old('is_active', $petugas?->is_active ?? true))
                class="h-4 w-4 text-pln-slate-500 focus:ring-pln-slate-400"
            >
            <span class="text-sm font-medium text-pln-slate-900">Nonaktif</span>
        </label>
    </div>
    @error('is_active')
        <p class="mt-1.5 text-sm text-status-cancel">{{ $message }}</p>
    @enderror
</div>
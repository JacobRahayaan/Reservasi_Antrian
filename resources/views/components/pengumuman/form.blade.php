@props(['pengumuman' => null])

<x-input
    label="Judul Pengumuman"
    name="judul"
    :value="old('judul', $pengumuman?->judul)"
    placeholder="Contoh: Perubahan Jam Layanan Saat Libur Nasional"
    required
    :error="$errors->first('judul')"
/>

<div class="mt-5">
    <div class="mb-1.5 flex items-center justify-between">
        <label for="isi" class="block text-sm font-medium text-pln-slate-900">
            Isi Pengumuman <span class="text-status-cancel">*</span>
        </label>
        <span data-char-counter="isi" class="text-xs text-pln-slate-400">0 / 2000</span>
    </div>
    <textarea
        name="isi"
        id="isi"
        rows="5"
        maxlength="2000"
        data-char-count-target="isi"
        placeholder="Tuliskan isi pengumuman secara lengkap..."
        required
        class="block w-full rounded-lg border px-3.5 py-2.5 text-sm text-pln-slate-900 placeholder:text-pln-slate-400 focus:outline-none focus:ring-2 {{ $errors->has('isi') ? 'border-status-cancel focus:ring-status-cancel/40' : 'border-pln-slate-200 focus:border-pln-navy-700 focus:ring-pln-navy-700/20' }}"
    >{{ old('isi', $pengumuman?->isi) }}</textarea>
    @error('isi')
        <p class="mt-1.5 text-sm text-status-cancel">{{ $message }}</p>
    @enderror
</div>

<div class="mt-5 grid gap-5 sm:grid-cols-2">
    <x-input
        label="Tanggal Mulai Tayang"
        name="tanggal_mulai"
        type="date"
        :value="old('tanggal_mulai', $pengumuman?->tanggal_mulai?->toDateString() ?? now()->toDateString())"
        required
        :error="$errors->first('tanggal_mulai')"
    />
    <x-input
        label="Tanggal Selesai Tayang (Opsional)"
        name="tanggal_selesai"
        type="date"
        :value="old('tanggal_selesai', $pengumuman?->tanggal_selesai?->toDateString())"
        hint="Kosongkan jika pengumuman tayang tanpa batas waktu."
        :error="$errors->first('tanggal_selesai')"
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
                @checked(old('is_active', $pengumuman?->is_active ?? true))
                class="h-4 w-4 text-status-done focus:ring-status-done"
            >
            <span class="text-sm font-medium text-pln-slate-900">Aktif</span>
        </label>
        <label class="flex flex-1 cursor-pointer items-center gap-2.5 rounded-lg border-2 border-pln-slate-200 px-4 py-3 has-[:checked]:border-pln-slate-400 has-[:checked]:bg-pln-slate-100">
            <input
                type="radio"
                name="is_active"
                value="0"
                @checked(! old('is_active', $pengumuman?->is_active ?? true))
                class="h-4 w-4 text-pln-slate-500 focus:ring-pln-slate-400"
            >
            <span class="text-sm font-medium text-pln-slate-900">Nonaktif</span>
        </label>
    </div>
    @error('is_active')
        <p class="mt-1.5 text-sm text-status-cancel">{{ $message }}</p>
    @enderror
</div>
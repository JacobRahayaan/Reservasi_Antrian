@props(['jadwal' => null, 'layanans'])

@php
    $modeAwal = old('mode_pembuatan', $errors->hasAny(['tanggal_mulai', 'tanggal_selesai', 'hari', 'jam_awal', 'jam_akhir', 'interval_menit', 'kuota_maksimal_berulang']) ? 'berulang' : 'tunggal');
@endphp

<div class="w-full">
    <label for="layanan_id" class="mb-1.5 block text-sm font-medium text-pln-slate-900">
        Jenis Layanan <span class="text-status-cancel">*</span>
    </label>
    <select
        name="layanan_id"
        id="layanan_id"
        required
        class="block w-full rounded-lg border px-3.5 py-2.5 text-sm text-pln-slate-900 focus:outline-none focus:ring-2 {{ $errors->has('layanan_id') ? 'border-status-cancel focus:ring-status-cancel/40' : 'border-pln-slate-200 focus:border-pln-navy-700 focus:ring-pln-navy-700/20' }}"
    >
        <option value="">Pilih jenis layanan</option>
        @foreach ($layanans as $layanan)
            <option value="{{ $layanan->id }}" @selected((int) old('layanan_id', $jadwal?->layanan_id) === $layanan->id)>
                {{ $layanan->nama_layanan }}
            </option>
        @endforeach
    </select>
    @error('layanan_id')
        <p class="mt-1.5 text-sm text-status-cancel">{{ $message }}</p>
    @enderror
</div>

@if (! $jadwal)
    <div class="mt-5">
        <label class="mb-1.5 block text-sm font-medium text-pln-slate-900">Mode Pembuatan</label>
        <div class="flex gap-3">
            <label class="flex flex-1 cursor-pointer items-center gap-2.5 rounded-lg border-2 border-pln-slate-200 px-4 py-3 has-[:checked]:border-pln-navy-700 has-[:checked]:bg-pln-navy-900/5">
                <input
                    type="radio"
                    name="mode_pembuatan"
                    value="tunggal"
                    id="jadwal-mode-tunggal-radio"
                    @checked($modeAwal === 'tunggal')
                    onchange="window.toggleJadwalModeDisplay()"
                    class="h-4 w-4 text-pln-navy-700 focus:ring-pln-navy-700"
                >
                <span class="text-sm font-medium text-pln-slate-900">Satu Jadwal</span>
            </label>
            <label class="flex flex-1 cursor-pointer items-center gap-2.5 rounded-lg border-2 border-pln-slate-200 px-4 py-3 has-[:checked]:border-pln-navy-700 has-[:checked]:bg-pln-navy-900/5">
                <input
                    type="radio"
                    name="mode_pembuatan"
                    value="berulang"
                    id="jadwal-mode-berulang-radio"
                    @checked($modeAwal === 'berulang')
                    onchange="window.toggleJadwalModeDisplay()"
                    class="h-4 w-4 text-pln-navy-700 focus:ring-pln-navy-700"
                >
                <span class="text-sm font-medium text-pln-slate-900">Buat Banyak Sekaligus (per Interval Jam)</span>
            </label>
        </div>
    </div>
@endif

<div id="jadwal-blok-tunggal" class="mt-5">
    <x-input
        label="Tanggal"
        name="tanggal"
        type="date"
        min="{{ now()->toDateString() }}"
        :value="old('tanggal', $jadwal?->tanggal?->toDateString())"
        required
        :error="$errors->first('tanggal')"
    />

    <div class="mt-5 grid gap-5 sm:grid-cols-2">
        <x-input
            label="Jam Mulai"
            name="jam_mulai"
            type="time"
            :value="old('jam_mulai', $jadwal ? substr($jadwal->jam_mulai, 0, 5) : null)"
            required
            :error="$errors->first('jam_mulai')"
        />
        <x-input
            label="Jam Selesai"
            name="jam_selesai"
            type="time"
            :value="old('jam_selesai', $jadwal ? substr($jadwal->jam_selesai, 0, 5) : null)"
            required
            :error="$errors->first('jam_selesai')"
        />
    </div>

    @php
        $kuotaHint = $jadwal && $jadwal->kuota_terpakai > 0
            ? "Sudah terisi {$jadwal->kuota_terpakai} reservasi — kuota tidak boleh diturunkan di bawah angka ini."
            : null;
    @endphp
    <div class="mt-5">
        <x-input
            label="Kuota Maksimum"
            name="kuota_maksimal"
            type="number"
            min="1"
            :value="old('kuota_maksimal', $jadwal?->kuota_maksimal)"
            placeholder="Contoh: 60"
            required
            :error="$errors->first('kuota_maksimal')"
            :hint="$kuotaHint"
        />
    </div>
</div>

@if (! $jadwal)
    @php
        $hariOpsi = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 0 => 'Minggu'];
        $hariTerpilih = array_map('strval', old('hari', []));
        $intervalHint = 'Setiap slot otomatis dibuat sepanjang interval ini, dari jam mulai sampai jam selesai operasional.';
    @endphp
    <div id="jadwal-blok-berulang" class="mt-5 hidden space-y-5">
        <div class="grid gap-5 sm:grid-cols-2">
            <x-input
                label="Tanggal Mulai"
                name="tanggal_mulai"
                type="date"
                min="{{ now()->toDateString() }}"
                :value="old('tanggal_mulai')"
                :error="$errors->first('tanggal_mulai')"
            />
            <x-input
                label="Tanggal Selesai"
                name="tanggal_selesai"
                type="date"
                min="{{ now()->toDateString() }}"
                :value="old('tanggal_selesai')"
                :error="$errors->first('tanggal_selesai')"
            />
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-pln-slate-900">Ulangi Pada Hari</label>
            <div class="flex flex-wrap gap-2">
                @foreach ($hariOpsi as $nilai => $label)
                    <label class="flex cursor-pointer items-center gap-2 rounded-lg border-2 border-pln-slate-200 px-3 py-2 has-[:checked]:border-pln-navy-700 has-[:checked]:bg-pln-navy-900/5">
                        <input
                            type="checkbox"
                            name="hari[]"
                            value="{{ $nilai }}"
                            @checked(in_array((string) $nilai, $hariTerpilih, true))
                            class="h-4 w-4 rounded text-pln-navy-700 focus:ring-pln-navy-700"
                        >
                        <span class="text-sm text-pln-slate-900">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            @error('hari')
                <p class="mt-1.5 text-sm text-status-cancel">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <x-input
                label="Jam Mulai Operasional"
                name="jam_awal"
                type="time"
                :value="old('jam_awal')"
                :error="$errors->first('jam_awal')"
            />
            <x-input
                label="Jam Selesai Operasional"
                name="jam_akhir"
                type="time"
                :value="old('jam_akhir')"
                :error="$errors->first('jam_akhir')"
            />
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <x-input
                label="Interval per Slot (menit)"
                name="interval_menit"
                type="number"
                min="15"
                step="5"
                :value="old('interval_menit', 60)"
                placeholder="Contoh: 60"
                :error="$errors->first('interval_menit')"
                :hint="$intervalHint"
            />
            <x-input
                label="Kuota per Slot"
                name="kuota_maksimal_berulang"
                type="number"
                min="1"
                :value="old('kuota_maksimal_berulang')"
                placeholder="Contoh: 3"
                :error="$errors->first('kuota_maksimal_berulang')"
            />
        </div>
    </div>
@endif

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
                @checked(old('is_active', $jadwal?->is_active ?? true))
                class="h-4 w-4 text-status-done focus:ring-status-done"
            >
            <span class="text-sm font-medium text-pln-slate-900">Aktif</span>
        </label>
        <label class="flex flex-1 cursor-pointer items-center gap-2.5 rounded-lg border-2 border-pln-slate-200 px-4 py-3 has-[:checked]:border-pln-slate-400 has-[:checked]:bg-pln-slate-100">
            <input
                type="radio"
                name="is_active"
                value="0"
                @checked(! old('is_active', $jadwal?->is_active ?? true))
                class="h-4 w-4 text-pln-slate-500 focus:ring-pln-slate-400"
            >
            <span class="text-sm font-medium text-pln-slate-900">Nonaktif</span>
        </label>
    </div>
    @error('is_active')
        <p class="mt-1.5 text-sm text-status-cancel">{{ $message }}</p>
    @enderror
</div>

@if (! $jadwal)
    <script>
        function toggleJadwalModeDisplay() {
            const radioBerulang = document.getElementById('jadwal-mode-berulang-radio');
            const radioTunggal = document.getElementById('jadwal-mode-tunggal-radio');
            const blokTunggal = document.getElementById('jadwal-blok-tunggal');
            const blokBerulang = document.getElementById('jadwal-blok-berulang');

            if (! radioBerulang || ! radioTunggal || ! blokTunggal || ! blokBerulang) {
                return;
            }

            const berulang = radioBerulang.checked;
            const form = radioTunggal.closest('form');

            blokTunggal.classList.toggle('hidden', berulang);
            blokBerulang.classList.toggle('hidden', ! berulang);

            if (form) {
                form.action = berulang
                    ? '{{ route('admin.jadwal.store-berulang') }}'
                    : '{{ route('admin.jadwal.store') }}';
            }
        }

        window.toggleJadwalModeDisplay = toggleJadwalModeDisplay;
        document.addEventListener('DOMContentLoaded', toggleJadwalModeDisplay);
    </script>
@endif
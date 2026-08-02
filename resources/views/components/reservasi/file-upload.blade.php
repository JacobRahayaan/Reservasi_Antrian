@props([
    'name' => 'dokumen',
    'maksimalFile' => 3,
    'error' => null,
])

@php
    $inputId = $attributes->get('id', $name . '-input');
@endphp

<div data-file-upload>
    <label
        for="{{ $inputId }}"
        data-file-upload-dropzone
        class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed px-6 py-8 text-center transition {{ $error ? 'border-status-cancel' : 'border-pln-slate-300 hover:border-pln-navy-400' }}"
    >
        <x-icon name="document-text" class="h-6 w-6 text-pln-navy-600" />
        <p class="text-sm text-pln-slate-600">
            Klik atau seret file ke sini untuk mengunggah
            <span class="font-semibold text-pln-navy-700">Pilih File</span>
        </p>
        <input
            type="file"
            name="{{ $name }}[]"
            id="{{ $inputId }}"
            data-file-upload-input
            multiple
            accept=".jpg,.jpeg,.png,.pdf"
            class="sr-only"
        >
    </label>

    <p class="mt-1.5 text-xs text-pln-slate-400">Maksimal {{ $maksimalFile }} file</p>

    <ul data-file-upload-list class="mt-3 space-y-2"></ul>

    @if ($error)
        <p class="mt-1.5 text-sm text-status-cancel">{{ $error }}</p>
    @endif
</div>
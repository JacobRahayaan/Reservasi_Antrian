@props(['status', 'checked' => false, 'disabled' => false])

@php
    $variantStyle = [
        'menunggu_review' => ['border' => 'has-[:checked]:border-status-review', 'bg' => 'has-[:checked]:bg-status-review/5', 'text' => 'text-status-review'],
        'perlu_datang' => ['border' => 'has-[:checked]:border-status-visit', 'bg' => 'has-[:checked]:bg-status-visit/5', 'text' => 'text-status-visit'],
        'selesai_online' => ['border' => 'has-[:checked]:border-status-online', 'bg' => 'has-[:checked]:bg-status-online/5', 'text' => 'text-status-online'],
        'selesai' => ['border' => 'has-[:checked]:border-violet-500', 'bg' => 'has-[:checked]:bg-violet-500/5', 'text' => 'text-violet-600'],
        'dibatalkan' => ['border' => 'has-[:checked]:border-status-cancel', 'bg' => 'has-[:checked]:bg-status-cancel/5', 'text' => 'text-status-cancel'],
    ];

    $iconMap = [
        'menunggu_review' => 'clock',
        'perlu_datang' => 'walking',
        'selesai_online' => 'check',
        'selesai' => 'check-circle',
        'dibatalkan' => 'x-mark',
    ];

    $style = $variantStyle[$status->value] ?? $variantStyle['menunggu_review'];
    $icon = $iconMap[$status->value] ?? 'clock';
@endphp

<label
    @class([
        'flex items-center gap-2 rounded-lg border-2 border-pln-slate-200 px-4 py-2.5 text-sm font-medium transition',
        $style['border'],
        $style['bg'],
        'cursor-pointer hover:bg-pln-slate-50' => ! $disabled,
        'cursor-not-allowed opacity-40' => $disabled,
    ])
>
    <input
        type="radio"
        name="status"
        value="{{ $status->value }}"
        @checked($checked)
        @disabled($disabled)
        class="peer sr-only"
    >
    <x-icon :name="$icon" class="h-4 w-4 {{ $style['text'] }}" />
    <span class="text-pln-slate-700 peer-checked:{{ $style['text'] }}">{{ $status->label() }}</span>
</label>
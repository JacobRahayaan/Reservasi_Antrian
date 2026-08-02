@props(['name'])

@php
    $icons = [
        'bolt' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 2 3 14h7l-1 8 11-14h-8l1-6Z" />',
        'clock' => '<circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3.5 2" />',
        'document-text' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 4h8l4 4v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 8h1M9 12h6m-6 4h6" />',
        'wrench-screwdriver' => '<path stroke-linecap="round" stroke-linejoin="round" d="M14.25 6.5a3.5 3.5 0 1 1 3.75 3.74L21 13.15l-2.5 2.5-2.9-2.9-4.2 4.2a2 2 0 0 1-2.83 0l-.42-.42a2 2 0 0 1 0-2.83l4.2-4.2-1.9-1.9a3.5 3.5 0 0 1 3.6-1.6Z" /><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 3-3" />',
        'pencil-square' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 5.5 18.5 9" />',
        'ticket' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v1.5a1.5 1.5 0 0 0 0 3V15a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-1.5a1.5 1.5 0 0 0 0-3Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 8v8" />',
        'building-office-2' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 21V7l7-4 7 4v14M4 21h16M9 21v-4h4v4" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 10h1M9 14h1M13 10h1M13 14h1" />',
        'headphones' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 15v-3a8 8 0 0 1 16 0v3" /><path stroke-linecap="round" stroke-linejoin="round" d="M4 15a2 2 0 0 1 2-2h1a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H6a2 2 0 0 1-2-2Zm16 0a2 2 0 0 1-2 2h-1a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1h1a2 2 0 0 1 2 2Z" />',
        'arrow-right' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0-6-6m6 6-6 6" />',
        'arrow-up' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5V4.5m0 0-6 6m6-6 6 6" />',
        'chevron-right' => '<path stroke-linecap="round" stroke-linejoin="round" d="m9 6 6 6-6 6" />',
        'bars-3' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />',
        'x-mark' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />',
        'map-pin' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 6-7.5 11-7.5 11s-7.5-5-7.5-11a7.5 7.5 0 1 1 15 0Z" />',
        'phone' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h1.5a2.25 2.25 0 0 0 2.25-2.25v-1.372a1.5 1.5 0 0 0-1.183-1.465l-3.223-.716a1.5 1.5 0 0 0-1.582.645l-.522.783a11.25 11.25 0 0 1-5.706-5.706l.783-.522a1.5 1.5 0 0 0 .645-1.582l-.716-3.223A1.5 1.5 0 0 0 6.622 2.25H5.25A2.25 2.25 0 0 0 3 4.5v2.25Z" />',
        'envelope' => '<path stroke-linecap="round" stroke-linejoin="round" d="m3 7 8.5 6a1 1 0 0 0 1 0L21 7M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2Z" />',
        'facebook' => '<path stroke-linecap="round" stroke-linejoin="round" d="M14 20v-6h2.2l.3-2.6H14V9.7c0-.75.2-1.26 1.28-1.26H16.6V6.1A16 16 0 0 0 14.9 6c-1.9 0-3.2 1.16-3.2 3.3v1.9H9.5v2.6h2.2V20" /><rect x="3.5" y="3.5" width="17" height="17" rx="4" />',
        'instagram' => '<rect x="3.5" y="3.5" width="17" height="17" rx="5" /><circle cx="12" cy="12" r="4" /><circle cx="16.6" cy="7.4" r="0.6" />',
        'twitter' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 6.4c-.66.3-1.36.5-2.1.6a3.6 3.6 0 0 0 1.6-2 7.2 7.2 0 0 1-2.3.9 3.6 3.6 0 0 0-6.2 3.3A10.2 10.2 0 0 1 4.6 5.4a3.6 3.6 0 0 0 1.1 4.8c-.58-.02-1.13-.18-1.6-.44v.05a3.6 3.6 0 0 0 2.9 3.5c-.5.14-1.06.16-1.6.06a3.6 3.6 0 0 0 3.4 2.5A7.2 7.2 0 0 1 3 17.4a10.2 10.2 0 0 0 5.5 1.6c6.6 0 10.2-5.5 10.2-10.2v-.47A7.3 7.3 0 0 0 21 6.4Z" />',
        'youtube' => '<rect x="2.5" y="6" width="19" height="12" rx="4" /><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 9.5v5l4.5-2.5-4.5-2.5Z" />',
    ];

    $path = $icons[$name] ?? '';
@endphp

<svg
    {{ $attributes->merge(['class' => 'h-5 w-5']) }}
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="1.8"
    aria-hidden="true"
>
    {!! $path !!}
</svg>
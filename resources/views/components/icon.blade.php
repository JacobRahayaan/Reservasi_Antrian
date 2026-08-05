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
        'user' => '<circle cx="12" cy="8" r="4" /><path stroke-linecap="round" stroke-linejoin="round" d="M4 20c0-4 4-6 8-6s8 2 8 6" />',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2" /><path stroke-linecap="round" d="M8 3v4M16 3v4M3 10h18" />',
        'walking' => '<circle cx="14" cy="4.5" r="2" /><path stroke-linecap="round" stroke-linejoin="round" d="M11 8.5 14 10l2 5-1 6M14 10l-4 3-3 6M9.5 11.5l-4.5 2" />',
        'check' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />',
        'check-circle' => '<circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />',
        'bell' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 8a6 6 0 1 1 12 0c0 4 1.5 5.5 1.5 5.5H4.5S6 12 6 8Z" /><path stroke-linecap="round" d="M10 19a2 2 0 0 0 4 0" />',
        'chevron-down' => '<path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />',
        'megaphone' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 11v2a2 2 0 0 0 2 2h1l3 5h2l-1-5h2l8 4V6l-8 4H6a2 2 0 0 0-2 2Z" /><path stroke-linecap="round" d="M9 15v4" />',
        'users' => '<circle cx="9" cy="8" r="3" /><path stroke-linecap="round" stroke-linejoin="round" d="M3 20c0-3.3 2.7-5 6-5s6 1.7 6 5" /><circle cx="17" cy="9" r="2.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M15.5 14c2.5 .3 4 1.7 4.5 4" />',
        'cog' => '<circle cx="12" cy="12" r="3" /><path stroke-linecap="round" d="M12 3v2M12 19v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M3 12h2M19 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4" />',
        'clipboard-list' => '<rect x="6" y="4" width="12" height="16" rx="2" /><path stroke-linecap="round" d="M9 4V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1M9 10h6M9 13h6M9 16h4" />',
        'chart-bar' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 20V10M10 20V4M16 20v-7M4 20h16" />',
        'arrow-trend-up' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 16 10 10l4 4 6-8M15 8h5v5" />',
        'arrow-trend-down' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 8l6 6 4-4 6 8M15 16h5v-5" />',
        'search' => '<circle cx="10" cy="10" r="6" /><path stroke-linecap="round" d="m20 20-4.3-4.3" />',
        'plus' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m-8-8h16" />',
        'trash' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m-9 0 1 13a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-13" /><path stroke-linecap="round" d="M10 11v6M14 11v6" />',
        'eye' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12S6 5 12 5s9.5 7 9.5 7-3.5 7-9.5 7S2.5 12 2.5 12Z" /><circle cx="12" cy="12" r="3" />',
        'pause-circle' => '<circle cx="12" cy="12" r="9" /><path stroke-linecap="round" d="M10 9v6M14 9v6" />',
        'chart-pie' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11 3.5A8.5 8.5 0 1 0 20.5 13H11V3.5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M14.5 3.6A8.5 8.5 0 0 1 20.4 9.5H14.5V3.6Z" />',
        'arrows-up-down' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7 4v16m0 0-3-3m3 3 3-3M17 20V4m0 0 3 3m-3-3-3 3" />',
        'exclamation-triangle' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.3 4 2.6 18a2 2 0 0 0 1.7 3h15.4a2 2 0 0 0 1.7-3L13.7 4a2 2 0 0 0-3.4 0Z" /><path stroke-linecap="round" d="M12 9v4M12 17h.01" />',
        'download' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" />',
        'dots-vertical' => '<circle cx="12" cy="6" r="1.2" /><circle cx="12" cy="12" r="1.2" /><circle cx="12" cy="18" r="1.2" />',
        'filter' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16M7 12h10M11 19h2" />',
        'paper-airplane' => '<path stroke-linecap="round" stroke-linejoin="round" d="m3 20 18-8L3 4l0 6.5 12 1.5-12 1.5Z" />',
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
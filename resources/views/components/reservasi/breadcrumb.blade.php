@props(['items'])

<nav class="text-sm text-pln-slate-500" aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-1.5">
        @foreach ($items as $index => $item)
            <li class="flex items-center gap-1.5">
                @if ($index > 0)
                    <span aria-hidden="true">/</span>
                @endif

                @if (! empty($item['href']) && $index !== count($items) - 1)
                    <a href="{{ $item['href'] }}" class="hover:text-pln-navy-900">{{ $item['label'] }}</a>
                @else
                    <span class="font-medium text-pln-slate-900" aria-current="page">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
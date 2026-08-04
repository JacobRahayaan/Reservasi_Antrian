@props(['paginator'])

@if ($paginator->hasPages())
<nav
    class="flex flex-col items-center justify-between gap-3 sm:flex-row"
    aria-label="Navigasi halaman"
>
    <p class="text-sm text-pln-slate-500">
        Menampilkan
        {{ $paginator->firstItem() }}
        -
        {{ $paginator->lastItem() }}
        dari
        {{ $paginator->total() }}
        data
    </p>

    <div class="flex items-center gap-2">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())

            <span
                class="flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-lg border border-pln-slate-200 text-pln-slate-300"
            >
                <x-icon
                    name="chevron-right"
                    class="h-4 w-4 rotate-180"
                />
            </span>

        @else

            <a
                href="{{ $paginator->previousPageUrl() }}"
                class="flex h-9 w-9 items-center justify-center rounded-lg border border-pln-slate-200 text-pln-slate-600 transition hover:bg-pln-slate-100"
                aria-label="Halaman sebelumnya"
            >
                <x-icon
                    name="chevron-right"
                    class="h-4 w-4 rotate-180"
                />
            </a>

        @endif

        {{-- Page Number --}}
        @foreach (
            $paginator->getUrlRange(
                max(1,$paginator->currentPage()-2),
                min($paginator->lastPage(),$paginator->currentPage()+2)
            ) as $page => $url
        )

            @if ($page == $paginator->currentPage())

                <span
                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-pln-navy-800 text-sm font-semibold text-white"
                >
                    {{ $page }}
                </span>

            @else

                <a
                    href="{{ $url }}"
                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-pln-slate-200 text-sm font-medium text-pln-slate-600 transition hover:bg-pln-slate-100"
                >
                    {{ $page }}
                </a>

            @endif

        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())

            <a
                href="{{ $paginator->nextPageUrl() }}"
                class="flex h-9 w-9 items-center justify-center rounded-lg border border-pln-slate-200 text-pln-slate-600 transition hover:bg-pln-slate-100"
                aria-label="Halaman berikutnya"
            >
                <x-icon
                    name="chevron-right"
                    class="h-4 w-4"
                />
            </a>

        @else

            <span
                class="flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-lg border border-pln-slate-200 text-pln-slate-300"
            >
                <x-icon
                    name="chevron-right"
                    class="h-4 w-4"
                />
            </span>

        @endif

    </div>

</nav>
@endif
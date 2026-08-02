@props(['number', 'icon', 'title', 'description', 'last' => false])

<li class="relative flex gap-5 sm:block sm:gap-0">

    <div class="flex flex-col items-center sm:hidden">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-pln-navy-800 text-sm font-semibold text-white">
            {{ $number }}
        </span>
        @unless ($last)
            <span class="mt-1 w-px flex-1 border-l-2 border-dashed border-pln-slate-300" aria-hidden="true"></span>
        @endunless
    </div>

    <div class="flex-1 pb-8 sm:hidden">
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-pln-slate-100 text-pln-navy-800">
            <x-icon :name="$icon" class="h-5 w-5" />
        </div>
        <h3 class="mt-3 font-display text-base font-semibold text-pln-navy-900">{{ $title }}</h3>
        <p class="mt-1.5 text-sm leading-relaxed text-pln-slate-600">{{ $description }}</p>
    </div>

    <div class="relative hidden sm:block">
        <div class="relative rounded-2xl bg-white p-6 pt-10 text-center shadow-sm ring-1 ring-pln-slate-200">
            <span class="absolute -top-4 left-1/2 flex h-8 w-8 -translate-x-1/2 items-center justify-center rounded-full bg-pln-navy-800 text-sm font-semibold text-white ring-4 ring-pln-slate-50">
                {{ $number }}
            </span>
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-xl bg-pln-slate-100 text-pln-navy-800">
                <x-icon :name="$icon" class="h-7 w-7" />
            </div>
            <h3 class="mt-4 font-display text-base font-semibold text-pln-navy-900">{{ $title }}</h3>
            <p class="mt-2 text-sm leading-relaxed text-pln-slate-600">{{ $description }}</p>
        </div>

        @unless ($last)
            <span class="absolute -right-10 top-1/2 hidden -translate-y-1/2 text-pln-slate-300 sm:flex" aria-hidden="true">
                <x-icon name="arrow-right" class="h-5 w-5" />
            </span>
        @endunless
    </div>

</li>
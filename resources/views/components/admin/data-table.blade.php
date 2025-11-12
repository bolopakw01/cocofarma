@props([
    'responsive' => true,
])

@php
    // Global fallback for table sort arrow icons. If iconamoon arrows are missing,
    // fall back to typcn icons bundled in the project.
    $arrowUpPath = 'bolopa/back/images/icon/iconamoon--arrow-up-2-duotone.svg';
    $arrowDownPath = 'bolopa/back/images/icon/iconamoon--arrow-down-2-duotone.svg';
    $arrowUpIcon = file_exists(public_path($arrowUpPath)) ? asset($arrowUpPath) : asset('bolopa/back/images/icon/typcn--arrow-sorted-up.svg');
    $arrowDownIcon = file_exists(public_path($arrowDownPath)) ? asset($arrowDownPath) : asset('bolopa/back/images/icon/typcn--arrow-sorted-down.svg');
@endphp

<div {{ $attributes->class('bolopa-tabel-wrapper') }}>
    <div class="bolopa-tabel-container">
        @isset($header)
            <header class="bolopa-tabel-header">
                {{ $header }}
            </header>
        @endisset

        @isset($controls)
            <div class="bolopa-tabel-controls">
                {{ $controls }}
            </div>
        @endisset

        @isset($beforeTable)
            {{ $beforeTable }}
        @endisset

        @if(isset($table))
            @if($responsive)
                <div class="bolopa-tabel-table-responsive">
                    {{ $table }}
                </div>
            @else
                {{ $table }}
            @endif
        @else
            @if($responsive)
                <div class="bolopa-tabel-table-responsive">
                    {{ $slot }}
                </div>
            @else
                {{ $slot }}
            @endif
        @endif

        @isset($footer)
            {{ $footer }}
        @endisset
    </div>
</div>

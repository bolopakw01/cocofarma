@props([
    'responsive' => true,
])

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

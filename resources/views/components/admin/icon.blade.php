@props([
    'name',
    'alt' => '',
    'size' => 20,
])

@php
    $iconMap = [
        'user' => 'bi--person-circle.svg',
        'plus' => 'line-md--plus-square-filled.svg',
        'search' => 'line-md--file-search-filled.svg',
        'export' => 'line-md--file-export-filled.svg',
        'print' => 'line-md--cloud-alt-print-twotone-loop.svg',
        'view' => 'line-md--watch.svg',
        'edit' => 'line-md--edit-twotone.svg',
        'delete' => 'line-md--trash.svg',
        'prev' => 'line-md--chevron-small-left.svg',
        'next' => 'line-md--chevron-small-right.svg',
        'product' => 'fluent-mdl2--product.svg',
        'production' => 'f7--cube-box-fill.svg',
        'transaction' => 'ant-design--wallet-outlined.svg',
        'report' => 'uil--chart-line.svg',
        'inventory' => 'fluent-mdl2--product.svg',
        'dashboard' => 'ic--outline-dashboard.svg',
        'lock' => 'mingcute--safe-lock-fill.svg',
        'adjustment' => 'tdesign--adjustment.svg',
        'cart' => 'mdi--cart-heart.svg',
        'switch' => 'ep--switch.svg',
        'product-fluent' => 'fluent-mdl2--product.svg',
    ];

    $fileName = $iconMap[$name] ?? $name;
    $src = asset('bolopa/back/images/icon/' . $fileName);
    $dimension = is_numeric($size) ? intval($size) : 20;
@endphp

<img src="{{ $src }}" alt="{{ $alt }}" width="{{ $dimension }}" height="{{ $dimension }}" {{ $attributes->merge(['class' => 'bolopa-icon']) }}>

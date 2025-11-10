<?php
require 'vendor/autoload.php';
\ = require_once 'bootstrap/app.php';
\->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
\ = App\Models\Produk::all();
foreach(\ as \) {
    echo \->id . ': ' . \->nama_produk . ' - foto: ' . (\->foto ?? 'null') . ' - status: ' . \->status . PHP_EOL;
}


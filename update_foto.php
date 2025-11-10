<?php
require 'vendor/autoload.php';
\ = require_once 'bootstrap/app.php';
\->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
\ = App\Models\Produk::find(134);
\->foto = 'produk_GsHgReJ6FizE.jpg';
\->save();
echo 'Updated foto to: ' . \->foto . PHP_EOL;


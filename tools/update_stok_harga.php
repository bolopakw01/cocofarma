<?php
// One-off script to update existing StokProduk.harga_satuan to match Produk.harga_jual
// Run: php tools/update_stok_harga.php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\StokProduk;
use Illuminate\Support\Facades\DB;

echo "Starting update of stok_produks.harga_satuan => produk.harga_jual\n";

$updated = 0;
$skipped = 0;
$missingProduk = 0;

StokProduk::with('produk')
    ->chunk(200, function($rows) use (&$updated, &$skipped, &$missingProduk) {
        foreach ($rows as $r) {
            $produk = $r->produk;
            if (!$produk) {
                $missingProduk++;
                continue;
            }
            $masterPrice = $produk->harga_jual ?? 0;

            // Compare numerically
            if ((float) $r->harga_satuan !== (float) $masterPrice) {
                $r->harga_satuan = $masterPrice;
                $r->save();
                $updated++;
            } else {
                $skipped++;
            }
        }
    });

echo "Done. Updated: {$updated}, Skipped (already equal): {$skipped}, Missing Produk: {$missingProduk}\n";

return 0;

<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\StokProduk;
use Illuminate\Support\Facades\DB;

$bad = 0;
$examples = [];

StokProduk::with('produk')
    ->chunk(200, function($rows) use (&$bad, &$examples) {
        foreach ($rows as $r) {
            $p = $r->produk;
            if (!$p) continue;
            $master = (float) $p->harga_jual;
            $s = (float) $r->harga_satuan;
            if ($master !== $s) {
                $bad++;
                if (count($examples) < 20) {
                    $examples[] = [
                        'stok_id' => $r->id,
                        'produk_id' => $r->produk_id,
                        'produk_nama' => $p->nama_produk,
                        'master_harga' => $p->harga_jual,
                        'stok_harga' => $r->harga_satuan,
                    ];
                }
            }
        }
    });

echo "Mismatched records: {$bad}\n";
if (!empty($examples)) {
    echo "Examples:\n";
    foreach ($examples as $e) {
        echo "stok_id: {$e['stok_id']}, produk_id: {$e['produk_id']}, nama: {$e['produk_nama']}, master: {$e['master_harga']}, stok: {$e['stok_harga']}\n";
    }
}
return 0;

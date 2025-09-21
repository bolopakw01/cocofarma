<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Verifying dummy data...\n";

    $produksiCount = DB::table('produksis')->count();
    echo "Produksi records: $produksiCount\n";

    if ($produksiCount > 0) {
        $produksi = DB::table('produksis')->first();
        echo "Sample produksi: {$produksi->nomor_produksi} - {$produksi->status}\n";
    }

    $produkCount = DB::table('produks')->count();
    echo "Produk records: $produkCount\n";

    $batchCount = DB::table('batch_produksis')->count();
    echo "Batch produksi records: $batchCount\n";

    $tungkuCount = DB::table('tungkus')->count();
    echo "Tungku records: $tungkuCount\n";

    echo "\nDummy data verification completed!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
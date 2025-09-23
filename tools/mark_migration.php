<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

$migrationName = '2025_09_23_000001_create_produk_bahans_table';
$exists = DB::table('migrations')->where('migration', $migrationName)->exists();
if ($exists) {
    echo "Migration already recorded\n";
    exit(0);
}
$maxBatch = DB::table('migrations')->max('batch');
$batch = $maxBatch ? $maxBatch + 1 : 1;
DB::table('migrations')->insert([
    'migration' => $migrationName,
    'batch' => $batch,
]);
echo "Inserted migration record: {$migrationName} (batch {$batch})\n";

// Quick Eloquent test: load produk with produkBahans
use App\Models\Produk;
try {
    $produks = Produk::aktif()->with('produkBahans.masterBahan')->limit(5)->get();
    echo "Produk query OK. Count: " . $produks->count() . "\n";
} catch (\Exception $e) {
    echo "Error running produk query: " . $e->getMessage() . "\n";
}

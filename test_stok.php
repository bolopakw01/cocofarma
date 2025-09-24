<?php

require_once 'vendor/autoload.php';

use App\Models\Produk;
use App\Models\BatchProduksi;
use App\Models\StokProduk;

echo "=== CHECKING PRODUCTS ===\n";
$produk = Produk::first();
if ($produk) {
    echo "First product ID: " . $produk->id . " - " . $produk->nama_produk . "\n";

    // Create batch produksi
    echo "\n=== CREATING BATCH PRODUKSI ===\n";
    $batch = BatchProduksi::create([
        'nomor_batch' => 'BATCH-TEST-001',
        'produk_id' => $produk->id,
        'tanggal_produksi' => '2025-09-24',
        'status' => 'selesai'
    ]);
    echo "Batch created with ID: " . $batch->id . "\n";

    // Create stok produk
    echo "\n=== CREATING STOK PRODUK ===\n";
    $stok = StokProduk::create([
        'produk_id' => $produk->id,
        'batch_produksi_id' => $batch->id,
        'jumlah_masuk' => 100,
        'sisa_stok' => 100,
        'harga_satuan' => 10000,
        'tanggal' => '2025-09-24'
    ]);
    echo "Stok created with ID: " . $stok->id . "\n";

} else {
    echo "No products found in database\n";
}

echo "\n=== CHECKING STOK PRODUK ===\n";
$stoks = StokProduk::with('produk')->get();
foreach ($stoks as $stok) {
    echo "Stok ID: {$stok->id}, Produk: {$stok->produk->nama_produk}, Sisa: {$stok->sisa_stok}\n";
}
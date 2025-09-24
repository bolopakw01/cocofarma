<?php

use App\Models\Produk;
use App\Models\BahanBaku;
use App\Models\MasterBahanBaku;
use App\Models\CodeCounter;
use Illuminate\Foundation\Testing\RefreshDatabase;

it('generates produk kode in expected format and increments sequence', function () {
    // Ensure counter table exists and is empty
    expect(CodeCounter::count())->toBe(0);

    $p = Produk::create([
        'nama_produk' => 'Kopi Campur',
        'kategori' => 'Minuman',
        'satuan' => 'pcs',
        'harga_jual' => 10000,
        'minimum_stok' => 0,
        'status' => 'aktif'
    ]);

    // Should have kode starting with P-YYYYMMDD and 3-letter abbr KOP
    $today = now()->format('Ymd');
    expect($p->kode_produk)->toStartWith('P-' . $today);
    expect(strlen($p->kode_produk))->toBeGreaterThanOrEqual(12);

    // Create another product with same prefix
    $p2 = Produk::create([
        'nama_produk' => 'Kopi Campur',
        'kategori' => 'Minuman',
        'satuan' => 'pcs',
        'harga_jual' => 12000,
        'minimum_stok' => 0,
        'status' => 'aktif'
    ]);

    // Ensure sequences are different
    expect($p->kode_produk)->not()->toBe($p2->kode_produk);
});

it('generates master bahan kode and increments sequence', function () {
    $m1 = MasterBahanBaku::create([
        'kode_bahan' => null,
        'nama_bahan' => 'Gula Pasir',
        'satuan' => 'kg',
        'harga_per_satuan' => 8000,
        'status' => 'aktif'
    ]);

    $m2 = MasterBahanBaku::create([
        'kode_bahan' => null,
        'nama_bahan' => 'Gula Pasir',
        'satuan' => 'kg',
        'harga_per_satuan' => 8200,
        'status' => 'aktif'
    ]);

    expect($m1->kode_bahan)->not()->toBeNull();
    expect($m1->kode_bahan)->not()->toBe($m2->kode_bahan);
});

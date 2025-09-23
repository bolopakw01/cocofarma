<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\Produksi;
use App\Models\ProduksiBahan;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Starting biaya_produksi recalculation...\n";

try {
    // Get all production records
    $produksis = Produksi::with('produksiBahans')->get();

    $updatedCount = 0;
    $totalRecords = $produksis->count();

    echo "Found {$totalRecords} production records to process.\n";

    foreach ($produksis as $produksi) {
        echo "Processing production {$produksi->nomor_produksi}\n";
        echo "  Current biaya_produksi: {$produksi->biaya_produksi}\n";
        echo "  Number of produksi_bahans: {$produksi->produksiBahans->count()}\n";

        $totalCost = 0;

        foreach ($produksi->produksiBahans as $bahan) {
            $namaBahan = $bahan->bahanBaku ? $bahan->bahanBaku->nama_bahan : 'Unknown';
            echo "    Bahan: {$namaBahan}, jumlah: {$bahan->jumlah_digunakan}, harga_satuan: {$bahan->harga_satuan}, total_biaya: {$bahan->total_biaya}\n";

            // Recalculate total_biaya if it's 0 or null
            if ($bahan->total_biaya == 0 || $bahan->total_biaya == null) {
                // Use harga_per_satuan from bahan baku if harga_satuan is 0
                $hargaSatuan = $bahan->harga_satuan;
                if ($hargaSatuan == 0 && $bahan->bahanBaku) {
                    $hargaSatuan = $bahan->bahanBaku->harga_per_satuan;
                }
                $calculatedTotal = $hargaSatuan * $bahan->jumlah_digunakan;
                $bahan->update(['total_biaya' => $calculatedTotal, 'harga_satuan' => $hargaSatuan]);
                echo "      -> Recalculated total_biaya to: {$calculatedTotal} (harga_satuan: {$hargaSatuan})\n";
                $totalCost += $calculatedTotal;
            } else {
                $totalCost += $bahan->total_biaya;
            }
        }

        echo "  Calculated total cost: {$totalCost}\n";

        // Update biaya_produksi if different
        if ($produksi->biaya_produksi != $totalCost) {
            $produksi->update(['biaya_produksi' => $totalCost]);
            $updatedCount++;
            echo "  -> UPDATED biaya_produksi to: {$totalCost}\n";
        } else {
            echo "  -> No change needed\n";
        }
        echo "\n";
    }

    echo "\nCompleted! Updated {$updatedCount} out of {$totalRecords} records.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
<?php

namespace App\Services;

use App\Models\BahanBaku;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Produksi;
use App\Models\StokBahanBaku;
use App\Models\StokProduk;
use Carbon\Carbon;

class DashboardPerformanceService
{
    /**
     * Enrich stored performance configuration with live actual values.
     */
    public static function enrich(array $metrics): array
    {
        return array_map(function (array $metric) {
            return static::applyActual($metric);
        }, $metrics);
    }

    private static function applyActual(array $metric): array
    {
        $label = strtolower($metric['label'] ?? '');
        $actual = 0;

        // Detect metric type based on keywords in label
        if (str_contains($label, 'penjualan') || str_contains($label, 'pesanan') || str_contains($label, 'order')) {
            $actual = static::orderTotal();
        } elseif (str_contains($label, 'produksi') || str_contains($label, 'batch')) {
            $actual = static::productionTotal();
        } elseif (str_contains($label, 'stok produk') || str_contains($label, 'produk')) {
            $actual = static::productStockTotal();
        } elseif (str_contains($label, 'bahan baku') || str_contains($label, 'material')) {
            $actual = static::materialStockTotal();
        } elseif (str_contains($label, 'produk aktif')) {
            $actual = static::activeProductTotal();
        } else {
            // Default to order total if no keywords match
            $actual = static::orderTotal();
        }

        $metric['actual'] = (float) max(0, $actual);
        $metric['actual_value'] = $actual;

        return $metric;
    }

    private static function orderTotal(): float
    {
        [$start, $end] = static::currentMonthRange();

        return (float) Pesanan::whereBetween('created_at', [$start, $end])->count();
    }

    private static function productionTotal(): float
    {
        [$start, $end] = static::currentMonthRange();

        return (float) Produksi::whereBetween('tanggal_produksi', [$start, $end])
            ->where('status', 'selesai')
            ->count();
    }

    private static function productStockTotal(): float
    {
        return (float) StokProduk::sum('sisa_stok');
    }

    private static function materialStockTotal(): float
    {
        return (float) StokBahanBaku::sum('sisa_stok');
    }

    private static function activeProductTotal(): float
    {
        return (float) Produk::aktif()->count();
    }

    private static function currentMonthRange(): array
    {
        $now = Carbon::now();
        return [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
    }

}

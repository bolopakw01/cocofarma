<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $tables = [
            'users',
            'produks',
            'bahan_baku',
            'produksis',
            'transaksis',
            'stok_produks',
            'stok_bahan_baku',
            'produksi_bahans',
            'produk_bahans',
            'pesanans',
            'pesanan_items',
            'transaksi_items',
            'batch_produksis',
            'master_bahan_baku',
            'tungkus',
            'pengaturans'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                    if (!Schema::hasColumn($table, 'deleted_at')) {
                        $tableBlueprint->softDeletes();
                    }
                });
            }
        }
    }

    public function down()
    {
        $tables = [
            'users',
            'produks',
            'bahan_baku',
            'produksis',
            'transaksis',
            'stok_produks',
            'stok_bahan_baku',
            'produksi_bahans',
            'produk_bahans',
            'pesanans',
            'pesanan_items',
            'transaksi_items',
            'batch_produksis',
            'master_bahan_baku',
            'tungkus',
            'pengaturans'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                    if (Schema::hasColumn($table, 'deleted_at')) {
                        $tableBlueprint->dropSoftDeletes();
                    }
                });
            }
        }
    }
};

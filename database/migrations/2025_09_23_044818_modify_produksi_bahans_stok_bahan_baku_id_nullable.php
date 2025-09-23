<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('produksi_bahans', function (Blueprint $table) {
            $table->dropForeign(['stok_bahan_baku_id']);
            $table->foreignId('stok_bahan_baku_id')->nullable()->change()->constrained('stok_bahan_baku')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produksi_bahans', function (Blueprint $table) {
            $table->dropForeign(['stok_bahan_baku_id']);
            $table->foreignId('stok_bahan_baku_id')->nullable(false)->change()->constrained('stok_bahan_baku')->onDelete('cascade');
        });
    }
};

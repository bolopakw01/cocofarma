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
        Schema::table('master_bahan_baku', function (Blueprint $table) {
            $table->decimal('stok_minimum', 15, 4)->nullable()->after('harga_per_satuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_bahan_baku', function (Blueprint $table) {
            $table->dropColumn('stok_minimum');
        });
    }
};

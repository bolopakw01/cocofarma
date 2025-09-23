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
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_bahan_id')->constrained('master_bahan_baku')->onDelete('cascade');
            $table->string('kode_bahan')->unique();
            $table->string('nama_bahan');
            $table->string('satuan');
            $table->decimal('harga_per_satuan', 15, 2);
            $table->decimal('stok', 10, 4)->default(0);
            $table->decimal('stok_minimum', 10, 4)->default(0);
            $table->date('tanggal_masuk');
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->string('status')->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_baku');
    }
};

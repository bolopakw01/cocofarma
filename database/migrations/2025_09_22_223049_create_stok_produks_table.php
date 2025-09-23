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
        Schema::create('stok_produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->foreignId('batch_produksi_id')->constrained('batch_produksis')->onDelete('cascade');
            $table->decimal('jumlah_masuk', 10, 2);
            $table->decimal('jumlah_keluar', 10, 2)->default(0);
            $table->decimal('sisa_stok', 10, 2)->default(0);
            $table->decimal('harga_satuan', 15, 2);
            $table->string('grade_kualitas')->nullable();
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_produks');
    }
};

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
            $table->unsignedInteger('produk_id'); // Changed to match produks.id type
            $table->foreignId('batch_produksi_id')->constrained('batch_produksis')->onDelete('cascade');
            $table->decimal('jumlah_masuk', 10, 2)->default(0);
            $table->decimal('jumlah_keluar', 10, 2)->default(0);
            $table->decimal('sisa_stok', 10, 2)->default(0);
            $table->decimal('harga_satuan', 12, 2);
            $table->enum('grade_kualitas', ['A', 'B', 'C']);
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['produk_id', 'tanggal']);
            $table->index(['batch_produksi_id']);
            // Add foreign key manually to match produks.id type
            // $table->foreign('produk_id')->references('id')->on('produks')->onDelete('cascade');
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

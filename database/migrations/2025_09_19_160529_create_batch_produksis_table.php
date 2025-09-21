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
        Schema::create('batch_produksis', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_batch')->unique();
            $table->unsignedInteger('produk_id'); // Changed to match produks.id type
            $table->unsignedBigInteger('tungku_id')->nullable(); // Remove auto constraint to tungkus
            $table->date('tanggal_produksi');
            $table->enum('status', ['rencana', 'proses', 'selesai', 'gagal'])->default('rencana');
            $table->datetime('waktu_mulai')->nullable();
            $table->datetime('waktu_selesai')->nullable();
            $table->decimal('total_biaya_bahan', 15, 2)->default(0);
            $table->decimal('total_biaya_operasional', 15, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Add foreign key constraint manually to match produks.id type
            $table->foreign('produk_id')->references('id')->on('produks')->onDelete('cascade');
            // Foreign key to tungkus will be added after tungkus table is created
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_produksis');
    }
};

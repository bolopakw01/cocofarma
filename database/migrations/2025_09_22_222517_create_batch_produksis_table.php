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
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->foreignId('tungku_id')->nullable()->constrained('tungkus')->onDelete('set null');
            $table->date('tanggal_produksi');
            $table->enum('status', ['rencana', 'proses', 'selesai', 'gagal'])->default('rencana');
            $table->datetime('waktu_mulai')->nullable();
            $table->datetime('waktu_selesai')->nullable();
            $table->decimal('total_biaya_bahan', 15, 2)->default(0);
            $table->decimal('total_biaya_operasional', 15, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
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

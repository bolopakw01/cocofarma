<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produksis', function (Blueprint $table) {
            $table->string('status_transfer', 20)->default('pending')->after('status');
            $table->timestamp('tanggal_transfer')->nullable()->after('status_transfer');
        });

        DB::table('produksis')
            ->where('status', 'selesai')
            ->update([
                'status_transfer' => 'transferred',
                'tanggal_transfer' => DB::raw('COALESCE(tanggal_transfer, NOW())'),
            ]);
    }

    public function down(): void
    {
        Schema::table('produksis', function (Blueprint $table) {
            $table->dropColumn(['status_transfer', 'tanggal_transfer']);
        });
    }
};

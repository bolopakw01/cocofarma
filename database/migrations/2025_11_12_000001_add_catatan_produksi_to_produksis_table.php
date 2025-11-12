<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds a new nullable text column `catatan_produksi` and copies existing
     * values from `catatan` into it for backward compatibility.
     */
    public function up()
    {
        if (!Schema::hasTable('produksis')) {
            return;
        }

        Schema::table('produksis', function (Blueprint $table) {
            if (!Schema::hasColumn('produksis', 'catatan_produksi')) {
                $table->text('catatan_produksi')->nullable()->after('catatan');
            }
        });

        // Copy existing catatan -> catatan_produksi where catatan_produksi is null or empty
        try {
            DB::table('produksis')
                ->whereNull('catatan_produksi')
                ->whereNotNull('catatan')
                ->where('catatan', '!=', '')
                ->update(['catatan_produksi' => DB::raw('catatan')]);
        } catch (\Exception $e) {
            // If update fails for any reason, swallow the exception to not break migrations.
            // The admin can run the copy manually.
            logger()->warning('Could not auto-copy catatan to catatan_produksi: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (!Schema::hasTable('produksis')) {
            return;
        }

        Schema::table('produksis', function (Blueprint $table) {
            if (Schema::hasColumn('produksis', 'catatan_produksi')) {
                $table->dropColumn('catatan_produksi');
            }
        });
    }
};

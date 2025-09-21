<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Produk;
use App\Models\BahanBaku;
use App\Models\MasterBahanBaku;
use App\Models\StokBahanBaku;
use App\Models\StokProduk;
use App\Models\Pesanan;
use App\Models\PesananItem;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\Produksi;
use App\Models\ProduksiBahan;
use App\Models\BatchProduksi;
use App\Models\Tungku;

class PurgeExceptAdmins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:purge-except-admins {--yes : Skip confirmation} {--dry-run : Show what would be deleted without truncating} {--only= : Comma-separated list of table names to operate on (optional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge all application data except users with role super_admin or admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->warn('This command will DELETE data from many tables.');

        if (! $this->option('yes')) {
            if (! $this->confirm('Are you sure you want to continue? This cannot be undone.')) {
                $this->info('Aborted. No changes made.');
                return 0;
            }
        }

        // List of models/tables to purge - exclude users
        $tables = [
            (new Produk())->getTable(),
            (new MasterBahanBaku())->getTable(),
            (new BahanBaku())->getTable(),
            (new StokBahanBaku())->getTable(),
            (new StokProduk())->getTable(),
            (new PesananItem())->getTable(),
            (new Pesanan())->getTable(),
            (new TransaksiItem())->getTable(),
            (new Transaksi())->getTable(),
            (new ProduksiBahan())->getTable(),
            (new Produksi())->getTable(),
            (new BatchProduksi())->getTable(),
            (new Tungku())->getTable(),
            // add other tables you want to purge here
        ];

        // Support tables to optionally truncate
        $supportTables = ['personal_access_tokens', 'failed_jobs', 'jobs'];

        // Handle --only option (filter tables)
        $only = $this->option('only');
        if ($only) {
            $onlyList = array_map('trim', explode(',', $only));
            // Keep only valid tables
            $tables = array_values(array_filter($tables, function ($t) use ($onlyList) {
                return in_array($t, $onlyList, true);
            }));
            // Support tables intersection
            $supportTables = array_values(array_filter($supportTables, function ($t) use ($onlyList) {
                return in_array($t, $onlyList, true);
            }));

            if (empty($tables) && empty($supportTables)) {
                $this->error('No matching tables found for --only: ' . $only);
                return 1;
            }
        }

        // If dry-run, show counts and return
        if ($this->option('dry-run')) {
            $this->info('Dry run: showing row counts for tables that would be truncated.');

            foreach ($tables as $table) {
                try {
                    $count = DB::table($table)->count();
                } catch (\Exception $e) {
                    $count = 'N/A (table missing or error)';
                }
                $this->line("{$table}: {$count}");
            }

            foreach ($supportTables as $table) {
                if (DB::getSchemaBuilder()->hasTable($table)) {
                    $count = DB::table($table)->count();
                    $this->line("{$table}: {$count}");
                } else {
                    $this->line("{$table}: N/A (table not present)");
                }
            }

            $this->info('Dry run complete. No changes made.');
            return 0;
        }

        // Not a dry run: perform truncation
        DB::beginTransaction();

        try {
            $this->info('Disabling foreign key checks...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            foreach ($tables as $table) {
                $this->line("Truncating {$table}...");
                DB::table($table)->truncate();
            }

            // Optionally, remove personal access tokens and failed jobs, etc.
            foreach ($supportTables as $table) {
                if (DB::getSchemaBuilder()->hasTable($table)) {
                    DB::table($table)->truncate();
                }
            }

            $this->info('Re-enabling foreign key checks...');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            DB::commit();

            $this->info('Purge complete. Only users are preserved.');
            $this->info('Please verify admin users exist and re-seed any necessary configuration.');

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->error('Error during purge: ' . $e->getMessage());
            return 1;
        }
    }
}

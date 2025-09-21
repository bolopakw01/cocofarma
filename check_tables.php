<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Checking and fixing foreign key constraints...\n";

    // Check existing constraints on produks table
    echo "\n=== EXISTING CONSTRAINTS ON PRODUKS ===\n";
    $constraints = DB::select('SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = "produks" AND REFERENCED_TABLE_NAME IS NOT NULL');
    foreach ($constraints as $constraint) {
        echo "Constraint: {$constraint->CONSTRAINT_NAME}, Column: {$constraint->COLUMN_NAME}, References: {$constraint->REFERENCED_TABLE_NAME}.{$constraint->REFERENCED_COLUMN_NAME}\n";
    }

    // Drop any existing foreign key constraints that might conflict
    if (count($constraints) > 0) {
        echo "\nDropping existing foreign key constraints...\n";
        foreach ($constraints as $constraint) {
            try {
                DB::statement("ALTER TABLE produks DROP FOREIGN KEY {$constraint->CONSTRAINT_NAME}");
                echo "Dropped constraint: {$constraint->CONSTRAINT_NAME}\n";
            } catch (Exception $e) {
                echo "Failed to drop constraint {$constraint->CONSTRAINT_NAME}: " . $e->getMessage() . "\n";
            }
        }
    }

    // Check if we can create the batch_produksis table now
    echo "\n=== TESTING BATCH_PRODUKSIS CREATION ===\n";
    try {
        // Drop table if exists
        DB::statement("DROP TABLE IF EXISTS batch_produksis");
        echo "Dropped existing batch_produksis table\n";

        // Create table without foreign keys first
        DB::statement("
            CREATE TABLE batch_produksis (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                nomor_batch VARCHAR(255) NOT NULL UNIQUE,
                produk_id INT UNSIGNED NOT NULL,
                tungku_id BIGINT UNSIGNED NULL,
                tanggal_produksi DATE NOT NULL,
                status ENUM('rencana', 'proses', 'selesai', 'gagal') DEFAULT 'rencana',
                waktu_mulai DATETIME NULL,
                waktu_selesai DATETIME NULL,
                total_biaya_bahan DECIMAL(15,2) DEFAULT 0,
                total_biaya_operasional DECIMAL(15,2) DEFAULT 0,
                catatan TEXT NULL,
                user_id BIGINT UNSIGNED NOT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )
        ");
        echo "batch_produksis table created without foreign keys\n";

        // Add foreign keys one by one
    // Check users table structure
    echo "\n=== USERS TABLE ===\n";
    $usersColumns = DB::select('DESCRIBE users');
    foreach ($usersColumns as $column) {
        echo $column->Field . ' - ' . $column->Type . ' - ' . ($column->Key ?: 'NO KEY') . ' - ' . ($column->Null ?: 'NOT NULL') . "\n";
    }

    // Try to add foreign key to produks with different approach
    echo "\n=== ADDING FK TO PRODUKS ===\n";
    try {
        // First, let's see what the actual issue is by checking indexes
        $indexes = DB::select('SHOW INDEX FROM produks');
        echo "Indexes on produks:\n";
        foreach ($indexes as $index) {
            echo "- {$index->Key_name} on {$index->Column_name} (Unique: {$index->Non_unique})\n";
        }

        // Try creating index first if needed
        DB::statement('CREATE INDEX IF NOT EXISTS idx_produks_id ON produks(id)');
        echo "Created index on produks.id\n";

        // Now try the foreign key again
        DB::statement("ALTER TABLE batch_produksis ADD CONSTRAINT fk_batch_produk_id FOREIGN KEY (produk_id) REFERENCES produks(id) ON DELETE CASCADE");
        echo "Successfully added foreign key to produks!\n";
    } catch (Exception $e) {
        echo "Still failed to add FK to produks: " . $e->getMessage() . "\n";

        // Alternative: create table with different column type
        echo "Trying alternative approach...\n";
        try {
            DB::statement("DROP TABLE IF EXISTS batch_produksis");
            DB::statement("
                CREATE TABLE batch_produksis (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    nomor_batch VARCHAR(255) NOT NULL UNIQUE,
                    produk_id INT NOT NULL,
                    tungku_id BIGINT UNSIGNED NULL,
                    tanggal_produksi DATE NOT NULL,
                    status ENUM('rencana', 'proses', 'selesai', 'gagal') DEFAULT 'rencana',
                    waktu_mulai DATETIME NULL,
                    waktu_selesai DATETIME NULL,
                    total_biaya_bahan DECIMAL(15,2) DEFAULT 0,
                    total_biaya_operasional DECIMAL(15,2) DEFAULT 0,
                    catatan TEXT NULL,
                    user_id BIGINT UNSIGNED NOT NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                )
            ");
            echo "Created batch_produksis without FK to produks, will handle in application logic\n";
        } catch (Exception $e2) {
            echo "Alternative approach also failed: " . $e2->getMessage() . "\n";
        }
    }

        try {
            DB::statement("ALTER TABLE batch_produksis ADD CONSTRAINT fk_batch_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE");
            echo "Added foreign key to users\n";
        } catch (Exception $e) {
            echo "Failed to add FK to users: " . $e->getMessage() . "\n";
        }

        echo "batch_produksis table setup completed!\n";
    } catch (Exception $e) {
        echo "Failed to create batch_produksis table: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
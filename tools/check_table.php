<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;
$row = DB::selectOne("SELECT TABLE_NAME, TABLE_SCHEMA FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='produk_bahans'");
if ($row) {
    $create = DB::selectOne("SELECT TABLE_NAME, TABLE_SCHEMA, CREATE_TIME FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='produk_bahans'");
    echo "produk_bahans exists\n";
    $cols = DB::select("SHOW FULL COLUMNS FROM produk_bahans");
    foreach ($cols as $c) {
        echo $c->Field . " - " . $c->Type . " - " . $c->Null . "\n";
    }
} else {
    echo "produk_bahans missing\n";
}

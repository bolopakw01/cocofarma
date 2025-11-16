<?php

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

/** @var Kernel $kernel */
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$deleted = User::where('username', '!=', 'lopa123')->delete();

echo "Deleted {$deleted} users.\n";

<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "=== UPDATING USER ROLE ===\n\n";

// Find user lopa123
$user = User::where('username', 'lopa123')->first();

if (!$user) {
    echo "ERROR: User lopa123 not found!\n";
    exit(1);
}

echo "Current user role: {$user->role}\n";

// Update role to super_admin
$user->role = 'super_admin';
$user->save();

echo "✓ Role updated successfully to: {$user->role}\n\n";

// Verify the change
$updatedUser = User::where('username', 'lopa123')->first();
echo "Verification - New role: {$updatedUser->role}\n";

echo "\n=== UPDATE COMPLETE ===";
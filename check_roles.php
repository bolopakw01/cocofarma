<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "=== CHECKING USER ROLES ===\n";

    $user = DB::table('users')->where('username', 'lopa123')->first();
    if ($user) {
        echo "User lopa123 found:\n";
        echo "ID: {$user->id}\n";
        echo "Name: {$user->name}\n";
        echo "Username: {$user->username}\n";
        echo "Email: {$user->email}\n";
        echo "Role: {$user->role}\n";
        echo "Status: {$user->status}\n";

        // Check if role is correct
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            echo "✅ User has correct role for admin access\n";
        } else {
            echo "❌ User role '{$user->role}' is not sufficient for admin access\n";
            echo "Updating role to 'super_admin'...\n";

            DB::table('users')
                ->where('username', 'lopa123')
                ->update(['role' => 'super_admin']);

            echo "✅ Role updated to 'super_admin'\n";
        }
    } else {
        echo "❌ User lopa123 not found!\n";
    }

    // Check all users and their roles
    echo "\n=== ALL USERS AND ROLES ===\n";
    $allUsers = DB::table('users')->get();
    foreach ($allUsers as $u) {
        echo "{$u->username}: {$u->role} ({$u->status})\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
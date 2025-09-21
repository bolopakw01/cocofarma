<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

echo "=== DEBUGGING ROLE ACCESS ===\n\n";

// Check if user exists
$user = User::where('username', 'lopa123')->first();
if (!$user) {
    echo "ERROR: User lopa123 not found in database!\n";
    exit(1);
}

echo "User found: {$user->username}\n";
echo "User role: {$user->role}\n";
echo "User status: {$user->status}\n\n";

// Simulate login
Auth::login($user);
Session::put('role', $user->role);

echo "Login simulated successfully\n";
echo "Session role set to: " . Session::get('role') . "\n\n";

// Test role middleware logic
$allowedRoles = ['super_admin', 'admin'];
echo "Testing role middleware logic:\n";
echo "Allowed roles: " . implode(', ', $allowedRoles) . "\n";
echo "User role in allowed: " . (in_array($user->role, $allowedRoles) ? 'YES' : 'NO') . "\n";
echo "Session role in allowed: " . (in_array(Session::get('role'), $allowedRoles) ? 'YES' : 'NO') . "\n\n";

// Check authentication status
echo "Authentication status: " . (Auth::check() ? 'AUTHENTICATED' : 'NOT AUTHENTICATED') . "\n";
echo "Current user ID: " . (Auth::id() ?: 'NONE') . "\n";

echo "\n=== DEBUG COMPLETE ===";
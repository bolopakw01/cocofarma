<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

echo "=== TESTING PRODUCTION ROUTE ACCESS ===\n\n";

// Get the user
$user = User::where('username', 'lopa123')->first();
if (!$user) {
    echo "ERROR: User not found!\n";
    exit(1);
}

echo "User: {$user->username} (Role: {$user->role})\n\n";

// Simulate login
Auth::login($user);
Session::put('role', $user->role);

echo "Login simulated. Auth status: " . (Auth::check() ? 'YES' : 'NO') . "\n";
echo "Session role: " . Session::get('role') . "\n\n";

// Test the RoleMiddleware logic directly
$roles = ['super_admin', 'admin']; // From route middleware
echo "Testing RoleMiddleware with roles: " . implode(', ', $roles) . "\n";

if (!Auth::check()) {
    echo "FAIL: Not authenticated\n";
} else {
    $currentUser = Auth::user();
    echo "Current user role: {$currentUser->role}\n";

    if (!in_array($currentUser->role, $roles)) {
        echo "FAIL: Role {$currentUser->role} not in allowed roles\n";
    } else {
        echo "SUCCESS: Role check passed\n";
    }
}

echo "\n=== TEST COMPLETE ===";
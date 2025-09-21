<?php

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

// Simulate the middleware check
echo "=== DEBUGGING ROLE MIDDLEWARE ===\n\n";

// Check if user is authenticated
if (Auth::check()) {
    $user = Auth::user();
    echo "User authenticated: {$user->username}\n";
    echo "User role: {$user->role}\n";
    echo "User status: {$user->status}\n\n";

    // Check session role
    $sessionRole = Session::get('role');
    echo "Session role: " . ($sessionRole ?: 'NOT SET') . "\n\n";

    // Simulate role check for 'super_admin,admin'
    $allowedRoles = ['super_admin', 'admin'];
    echo "Allowed roles: " . implode(', ', $allowedRoles) . "\n";
    echo "User role in allowed roles: " . (in_array($user->role, $allowedRoles) ? 'YES' : 'NO') . "\n";
    echo "Session role in allowed roles: " . (in_array($sessionRole, $allowedRoles) ? 'YES' : 'NO') . "\n\n";

    // Check if user exists in database
    $dbUser = User::where('username', 'lopa123')->first();
    if ($dbUser) {
        echo "Database user found:\n";
        echo "- ID: {$dbUser->id}\n";
        echo "- Username: {$dbUser->username}\n";
        echo "- Role: {$dbUser->role}\n";
        echo "- Status: {$dbUser->status}\n";
    } else {
        echo "ERROR: User not found in database!\n";
    }

} else {
    echo "User not authenticated\n";
}

// Check current session data
echo "\n=== SESSION DATA ===\n";
$sessionData = Session::all();
if (isset($sessionData['login_web_' . md5('admin_guard')])) {
    echo "Admin guard session exists\n";
} else {
    echo "Admin guard session NOT found\n";
}

echo "\nSession keys: " . implode(', ', array_keys($sessionData)) . "\n";
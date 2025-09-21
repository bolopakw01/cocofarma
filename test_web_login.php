<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

echo "=== TESTING WEB LOGIN SIMULATION ===\n\n";

// Create a mock request for login
$request = new Request();
$request->merge([
    'username' => 'lopa123',
    'password' => 'lopa123'
]);

echo "Attempting login with username: lopa123, password: lopa123\n\n";

// Create AdminController instance
$adminController = new AdminController();

// Simulate the login method
try {
    // Manually call the login logic
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
        echo "✓ Auth::attempt() successful\n";

        // Simulate session regeneration
        $request->session()->regenerate();

        // Get user and store role in session
        $user = Auth::user();
        echo "✓ User authenticated: {$user->username}\n";
        echo "✓ User role: {$user->role}\n";

        session(['role' => $user->role]);
        echo "✓ Role stored in session: " . session('role') . "\n";

        echo "\n✓ LOGIN SUCCESSFUL\n";

        // Test accessing a protected route
        echo "\n=== TESTING ROUTE ACCESS ===\n";

        // Check if we can access the production index route
        $roles = ['super_admin', 'admin'];
        if (in_array($user->role, $roles)) {
            echo "✓ User has access to production routes\n";
        } else {
            echo "✗ User does NOT have access to production routes\n";
        }

    } else {
        echo "✗ Auth::attempt() failed\n";
    }

} catch (Exception $e) {
    echo "✗ Error during login: " . $e->getMessage() . "\n";
}

echo "\n=== SIMULATION COMPLETE ===";
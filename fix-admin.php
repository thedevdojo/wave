<?php

// Load the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get the user by ID (change this to your user ID if needed)
$userId = 1; // Default admin user ID
$user = \App\Models\User::find($userId);

if (!$user) {
    echo "User with ID {$userId} not found.\n";
    exit(1);
}

// Assign the admin role
$user->assignRole('admin');
echo "Admin role assigned to {$user->name} (ID: {$user->id}).\n";

// Check if the user now has the admin role
if ($user->hasRole('admin')) {
    echo "Confirmed: User now has the admin role.\n";
} else {
    echo "Warning: Failed to assign admin role.\n";
}

// Output all roles for verification
echo "Current roles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n"; 
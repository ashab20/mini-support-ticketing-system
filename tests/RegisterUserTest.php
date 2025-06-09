<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../models/User.php';

// data
$email = 'testuser@example.com';
$password = 'test123';
$name = 'Test User';
$role = 'User';

$user = new User();
$existing = $user->getByEmail($email);
if ($existing) {
    $user->delete($existing['id']);
}

// Create new user
$result = $user->create($email, $password, $name, $role);

echo " RegisterUserTest Response:\n";
if ($result) {
    echo "User registered successfully\n";
} else {
    echo "User registration failed\n";
}
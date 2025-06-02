<?php
require_once __DIR__ . '/env.php';
loadEnv();

$servername = $_ENV['server'];
$dbname     = $_ENV['database_name'] ?? 'ticket_system';
$username   = $_ENV['db_username'] ?? 'root';
$password   = $_ENV['db_password'] ?? 'ServBay.dev';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
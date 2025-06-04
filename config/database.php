<?php

require_once __DIR__ . '/env.php';
loadEnv();

$host = $_ENV['server'] ?? 'localhost';
$dbname = $_ENV['database_name'] ?? 'ticket_system';
$username = $_ENV['db_username'] ?? 'root';
$password = $_ENV['db_password'] ?? '';

class Database
{
    private static $instance;
    private $connection;

    private function __construct()
    {
        global $host, $dbname, $username, $password;

        $this->connection = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
}

// connection
try {
    $conn = Database::getInstance();
    echo "Connected successfully";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
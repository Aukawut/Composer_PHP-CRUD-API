<?php 
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$db_host = $_ENV['DB_HOST'] ;
$db_name = $_ENV['DB_NAME'] ;
$db_user = $_ENV['DB_USER'] ;
$db_password = $_ENV['DB_PASSWORD'] ;
try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully to the database.";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

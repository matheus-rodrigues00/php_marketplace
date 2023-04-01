<?php
$host = 'localhost';
$dbname = 'marketplace';
$user = 'postgres';
$password = 'postgres';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

require_once __DIR__ . '/../vendor/autoload.php';
use Bramus\Router\Router;

$router = new Router();

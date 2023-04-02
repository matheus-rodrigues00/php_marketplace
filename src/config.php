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

// Include the necessary files
require_once __DIR__ . '/models/Sale.php';
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/ProductType.php';
require_once __DIR__ . '/models/SaleItem.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/controllers/SalesController.php';
require_once __DIR__ . '/controllers/ProductsController.php';
require_once __DIR__ . '/controllers/ProductTypesController.php';
require_once __DIR__ . '/controllers/UsersController.php';

require_once __DIR__ . '/../vendor/autoload.php';
use Bramus\Router\Router;

$router = new Router();

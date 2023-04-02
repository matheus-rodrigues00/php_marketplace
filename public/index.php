<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header("HTTP/1.1 200 OK");
    die();
}

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/database.php';

$router = new \Bramus\Router\Router();
$db = new Database();
$productController = new ProductController($db);
$productTypesController = new ProductTypesController($db);
$salesController = new SalesController($db);

// Products
$router->get('/products', function () use ($productController) {
    $products = $productController->index();
    header('Content-Type: application/json');
    echo json_encode($products);
});


$router->get('/products/(\d+)', function ($id) use ($productController) {
    $product = $productController->show($id);
    header('Content-Type: application/json');
    echo json_encode($product);
});

$router->post('/products', function () use ($productController) {
    $request_body = file_get_contents('php://input');
    $request_data = json_decode($request_body, true);

    $name = $request_data['name'];
    $price = $request_data['price'];
    $product_type_id = $request_data['product_type_id'];

    $product = $productController->create($name, $price, $product_type_id);
    header('Content-Type: application/json');
    echo json_encode($product);
});


$router->put('/products/(\d+)', function ($id) use ($productController) {
    $request_body = json_decode(file_get_contents('php://input'), true);
    $name = $request_body['name'] ?? null;
    $price = $request_body['price'] ?? null;
    $product_type_id = $request_body['product_type_id'] ?? null;
    $product = $productController->update($id, $name, $price, $product_type_id);

    header('Content-Type: application/json');
    echo json_encode($product);
});

$router->delete('/products/(\d+)', function ($id) use ($productController) {
    $productController->destroy($id);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Product deleted']);
});

// Product Types
$router->get('/product-types', function () use ($productTypesController) {
    $productTypes = $productTypesController->index();
    header('Content-Type: application/json');
    echo json_encode($productTypes);
});

$router->get('/product-types/(\d+)', function ($id) use ($productTypesController) {
    $productType = $productTypesController->show($id);
    header('Content-Type: application/json');
    echo json_encode($productType);
});

$router->post('/product-types', function () use ($productTypesController) {
    $request_body = file_get_contents('php://input');
    $request_data = json_decode($request_body, true);

    $name = $request_data['name'];
    $tax_rate = $request_data['tax_rate'];

    $productType = $productTypesController->create($name, $tax_rate);
    header('Content-Type: application/json');
    echo json_encode($productType);
});

$router->put('/product-types/(\d+)', function ($id) use ($productTypesController) {
    $request_body = json_decode(file_get_contents('php://input'), true);
    $name = $request_body['name'] ?? null;
    $tax_rate = $request_body['tax_rate'] ?? null;
    $productType = $productTypesController->update($id, $name, $tax_rate);
    header('Content-Type: application/json');
    echo json_encode($productType);
});

$router->delete('/product-types/(\d+)', function ($id) use ($productTypesController) {
    $productTypesController->destroy($id);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Product type deleted']);
});

// Sales
// index
$router->get('/sales', function () use ($salesController) {
    $sales = $salesController->index();
    header('Content-Type: application/json');
    echo json_encode($sales);
});

$router->post('/sales', function () use ($salesController) {
    $request_body = file_get_contents('php://input');
    $request_data = json_decode($request_body, true);

    $sale_items = $request_data['sale_items'] ?? [];

    $sale = $salesController->create($sale_items);
    header('Content-Type: application/json');
    echo json_encode($sale);
});

$router->get('/sales/(\d+)', function ($id) use ($salesController) {
    $sale = $salesController->show($id);
    header('Content-Type: application/json');
    echo json_encode($sale);
});

// Sale Items
$router->post('/sales/items', function () use ($salesController) {
    $request_body = file_get_contents('php://input');
    $request_data = json_decode($request_body, true);
    
    $sale_id = $request_data['sale_id'];
    $product_id = $request_data['product_id'];
    $quantity = $request_data['quantity'];

    $sale_item = $salesController->addSaleItem($sale_id, $product_id, $quantity);
    header('Content-Type: application/json');
    if ($sale_item == null) {
        http_response_code(404);
        echo json_encode(['error' => 'Sale or product not found']);
    } else {
        echo json_encode($sale_item);
    }
});

$router->put('/sales/items', function () use ($salesController) {
    $request_body = file_get_contents('php://input');
    $request_data = json_decode($request_body, true);

    $sale_item_id = $request_data['sale_item_id'];
    $quantity = $request_data['quantity'] ?? 0;

    $sale_item = $salesController->update($sale_item_id, $quantity);
    header('Content-Type: application/json');
    if (is_null($sale_item)) {
        echo json_encode(['error' => 'Sale item not found']);
    } else if($sale_item == []) {
        http_response_code(404);
        echo json_encode(['message' => 'Item deleted']);
    } else {
        echo json_encode($sale_item);
    }
});

// Default
$router->get('/', function () {
    echo 'Ok!';
});

$router->run();

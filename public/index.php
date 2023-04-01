<?php
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/database.php';
require_once __DIR__ . '/../src/controllers/ProductsController.php';

$router = new \Bramus\Router\Router();
$db = new Database();
$productController = new ProductController($db);


$router->get('/products', function () use ($productController) {
    $products = $productController->index();
    header('Content-Type: application/json');
    echo json_encode($products);
});


$router->get('/products/(\d+)', function ($id) use ($productController) {
    $product = $productController->show($id);
    echo json_encode($product);
});
$router->post('/products', function () use ($productController) {
    $request_body = file_get_contents('php://input');
    $request_data = json_decode($request_body, true);

    $name = $request_data['name'];
    $price = $request_data['price'];

    $product = $productController->create($name, $price);
    header('Content-Type: application/json');
    echo json_encode($product);
});


$router->put('/products/(\d+)', function ($id) use ($productController) {
    $request_body = json_decode(file_get_contents('php://input'), true);
    $name = $request_body['name'] ?? null;
    $price = $request_body['price'] ?? null;
    $product = $productController->update($id, $name, $price);
    header('Content-Type: application/json');
    echo json_encode($product);
});

$router->delete('/products/(\d+)', function ($id) use ($productController) {
    $productController->destroy($id);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Product deleted']);
});

$router->get('/', function () {
    echo 'Hello World!';
});

$router->run();

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
$usersController = new UsersController($db);

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
$router->get('/sales', function () use ($salesController) {
    $sales = $salesController->index();
    header('Content-Type: application/json');
    echo json_encode($sales);
});

$router->post('/sales', function () use ($salesController) {
    $request_body = file_get_contents('php://input');
    $request_data = json_decode($request_body, true);

    $user_id = $request_data['user_id'] ?? [];

    $sale = $salesController->create($user_id);
    header('Content-Type: application/json');
    echo json_encode($sale);
});

// get sale of the user
$router->get('/sales/user/(\d+)', function ($user_id) use ($salesController) {
    $sale = $salesController->showUserSales($user_id);
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
    $quantity = $request_data['quantity'];

    $sale_item = $salesController->update($sale_item_id, $quantity);
    header('Content-Type: application/json');
    echo json_encode($sale_item);
});

// Users
$router->get('/users', function () use ($usersController) {
    $users = $usersController->index();
    header('Content-Type: application/json');
    echo json_encode($users);
});

// user by token
$router->get('/users/me', function () use ($usersController) {
    $user = $usersController->showMe();
    header('Content-Type: application/json');
    echo json_encode($user);
});

$router->get('/users/(\d+)', function ($id) use ($usersController) {
    $user = $usersController->show($id);
    header('Content-Type: application/json');
    echo json_encode($user);
});

$router->post('/users/register', function () use ($usersController) {
    $request_body = file_get_contents('php://input');
    $request_data = json_decode($request_body, true);

    $name = $request_data['name'];
    $email = $request_data['email'];
    $password = $request_data['password'];

    $user = $usersController->create($name, $email, $password);
    header('Content-Type: application/json');
    echo json_encode($user);
});

// login
$router->post('/users/login', function () use ($usersController) {
    $request_body = file_get_contents('php://input');
    $request_data = json_decode($request_body, true);

    $email = $request_data['email'];
    $password = $request_data['password'];

    $user = $usersController->login($email, $password);
    header('Content-Type: application/json');
    echo json_encode($user);
});

// logout
$router->post('/logout', function () use ($usersController) {
    $user = $usersController->logout();
    header('Content-Type: application/json');
    echo json_encode($user);
});

$router->put('/users/(\d+)', function ($id) use ($usersController) {
    $request_body = json_decode(file_get_contents('php://input'), true);
    $name = $request_body['name'] ?? null;
    $email = $request_body['email'] ?? null;
    $password = $request_body['password'] ?? null;
    $user = $usersController->update($id, $name, $email, $password);
    header('Content-Type: application/json');
    echo json_encode($user);
});

$router->delete('/users/(\d+)', function ($id) use ($usersController) {
    $usersController->destroy($id);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'User deleted']);
});



// Default
$router->get('/', function () {
    echo 'Ok!';
});

$router->run();

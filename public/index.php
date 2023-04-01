<?php
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

$router = new \Bramus\Router\Router();

$router->get('/', function () {
    echo 'Hello World!';
});

$router->run();

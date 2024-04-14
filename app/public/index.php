<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . '/../vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();

$router->setNamespace('Controllers');

// routes for the products endpoint
$router->get('/products', 'ProductController@getAll');
$router->get('/product', 'ProductController@getProduct');
$router->post('/create-product', 'ProductController@createProduct');
$router->put('/update-product', 'ProductController@updateProduct');
$router->delete('/delete-product', 'ProductController@deleteProduct');
$router->get('/detailed-product', 'ProductController@detailedProduct');
$router->get('/detailed-products', 'ProductController@detailedProducts');

// routes for the categories endpoint
$router->get('/categories', 'CategoryController@getAll');
$router->get('/category', 'CategoryController@getCategory');
$router->post('/create-category', 'CategoryController@createCategory');
$router->put('/update-category', 'CategoryController@updateCategory');
$router->delete('/delete-category', 'CategoryController@deleteCategory');


// routes for the users endpoint
$router->post('/register', 'UserController@signup');
$router->post('/login', 'UserController@login');
$router->get('/users', 'UserController@getUsers');
$router->get('/user', 'UserController@getUser');
$router->post("/create-user", 'UserController@createUser');
$router->delete('/delete-user', 'UserController@deleteUser');
$router->put('/update-user', 'UserController@updateUser');

// routes for the brands endpoint
$router->get('/brands', 'BrandController@getAll');
$router->get('/brand', 'BrandController@getBrand');
$router->post('/create-brand', 'BrandController@createBrand');
$router->put('/update-brand', 'BrandController@updateBrand');
$router->delete('/delete-brand', 'BrandController@deleteBrand');

// routes for sizes endpoint
$router->get('/sizes', 'SizeController@getAll');
$router->get('/size', 'SizeController@getSize');
$router->get('/sizes-by-product', 'SizeController@getSizesForProduct');
$router->post('/create-size', 'SizeController@createSize');
$router->put('/update-size', 'SizeController@updateSize');
$router->delete('/delete-size', 'SizeController@deleteSize');

// routes for stock endpoint
$router->get('/stocks', 'StockController@getAll');
$router->post('/stock', 'StockController@addStock');
$router->put('/stock', 'StockController@updateStock');
$router->get('/stock-history', 'StockController@getAllStockHistory');


// Run it!
$router->run();

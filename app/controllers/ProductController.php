<?php

namespace Controllers;

use Exception;
use Services\ProductService;
use Services\UserService;

class ProductController extends Controller
{
    private $productService;
    private $userService;

    public function __construct()
    {
        $this->productService = new ProductService();
        $this->userService = new UserService();
    }

    public function getAll()
    {
        try {
            if (! isset($_SERVER['HTTP_AUTHORIZATION'])) {
                $this->respondWithError(401, 'Unauthorized');
                exit;
            }
            if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
                $this->respondWithError(401, 'Unauthorized');
                exit;
            }

            $token = $matches[1];
            $decoded = $this->userService->decodeToken($token);

            if (! $decoded) {
                $this->respondWithError(401, 'Unauthorized');
                exit;
            }

            $this->respond($this->productService->getAll());

        } catch (Exception $e) {
            $this->respondWithError(400, 'Unauthorized');
        }
    }
}

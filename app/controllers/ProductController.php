<?php

namespace Controllers;

use Exception;
use Services\ProductService;
use Services\AuthService;

class ProductController extends Controller
{
    private $service;
    private $authService;

    public function __construct()
    {
        $this->service = new ProductService();
        $this->authService = new AuthService();
        
    }

    public function getAll()
    {
        try {

            $this->authService->decodeToken($this->authService->getBearerToken());
            $this->respond($this->service->getAll());

        } catch (Exception $e) {
            $this->respondWithError(400, $e->getMessage());
        }
    }
}

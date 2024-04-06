<?php

namespace Controllers;

use Exception;
use Services\CategoryService;
use Services\AuthService;

class CategoryController extends Controller
{
    private $categoryService;
    private $authService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
        $this->authService = new AuthService();
    }

    public function getAll()
    {
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            $categories = $this->categoryService->getAll();
            $this->respond($categories);
        } catch (Exception $e) {
            $this->respondWithError(400, $e->getMessage());
        }
    }
}

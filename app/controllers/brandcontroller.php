<?php
namespace Controllers;

use Exception;
use Services\BrandService;
use Services\AuthService;

class BrandController extends Controller
{
    private $brandService;
    private $authService;

    public function __construct()
    {
        $this->brandService = new BrandService();
        $this->authService = new AuthService();
    }

    public function getAll()
    {
        try {
            $brands = $this->brandService->getAll();
            $this->respond($brands);
        } catch (Exception $e) {
            $this->respondWithError(400, $e->getMessage());
        }
    }
}

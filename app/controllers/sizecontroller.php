<?php
namespace Controllers;

use Exception;

use Services\SizeService;
use Services\AuthService;

class SizeController extends Controller
{
    private $service;
    private $authService;

    public function __construct()
    {
        $this->service = new SizeService();
        $this->authService = new AuthService();
    }

    public function getAll()
    {
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            $sizes = $this->service->getAll();
            $this->respond($sizes);
        } catch (Exception $e) {
            $this->respondWithError(400, $e->getMessage());
        }
    }
}

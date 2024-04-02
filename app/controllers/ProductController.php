<?php

namespace Controllers;

use Exception;
use Services\ProductService;

class ProductController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new ProductService();
    }

    public function getAll(){
        try{
            $this->respond($this->service->getAll());
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }
}
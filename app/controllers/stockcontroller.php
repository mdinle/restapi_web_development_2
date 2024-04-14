<?php

namespace Controllers;

use Exception;

use Models\Product;
use Models\Size;
use Models\Stock;
use Services\StockService;
use Services\AuthService;

class StockController extends Controller
{
    private $service;
    private $authService;

    public function __construct()
    {
        $this->service = new StockService();
        $this->authService = new AuthService();
    }

    public function getAll()
    {
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            $stocks = $this->service->getAll();
            $this->respond($stocks);
        } catch (Exception $e) {
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function getAllStockHistory(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            $stockHistory = $this->service->getAllHistory();
            $this->respond($stockHistory);
        }catch (Exception $e) {
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function addStock()
    {
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());

            $json = file_get_contents('php://input');
            $data = json_decode($json);
            $stock = new Stock();
            $stock->stock_quantity = $data->stock_quantity;
            $stock->stock_movement = $data->stock_movement;
            $stock->reason = $data->reason;
            $product = new Product();
            $product->id = $data->product_id;
            $size = new Size();
            $size->size_id = $data->size_id;

            $stock->product = $product;
            $stock->size = $size;

            $this->service->insert($stock);
            $this->respond(["message" => "Stock added"]);
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function updateStock()
    {
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());

            $json = file_get_contents('php://input');
            $data = json_decode($json);
            $stock = new Stock();
            $stock->stock_quantity = $data->stock_quantity;
            $stock->stock_movement = $data->stock_movement;
            $stock->reason = $data->reason;
            $product = new Product();
            $product->id = $data->product_id;
            $size = new Size();
            $size->size_id = $data->size_id;

            $stock->product = $product;
            $stock->size = $size;

            $result = $this->service->update($stock);
            if($result){
                $this->respond(["message" => "Stock updated"]);
            } else{
                $this->respondWithError(400, 'Failed to updated');}
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }
}

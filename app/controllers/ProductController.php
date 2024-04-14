<?php

namespace Controllers;

use Exception;
use Models\Brand;
use Models\Category;
use Models\Product;
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

    public function getProduct(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            if(isset($_GET['productId']) && is_numeric($_GET['productId'])) {
                $productId = $_GET['productId'];
                $result = $this->service->getOne($productId);
                if($result){
                    $this->respond($result);
                } else{
                    $this->respondWithError(400, 'Failed to retrieve product');
                }
            }else{
                throw new Exception('No product id or product size id');
            }
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function createProduct(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());

            $json = file_get_contents('php://input');
            $data = json_decode($json);
            $product = new Product();
            $product->name = $data->name;
            $product->price = $data->price;
            $product->image = $data->image;
            $category = new Category();
            $category->category_id = $data->category_id;
            $brand = new Brand();
            $brand->brand_id = $data->brand_id;

            $product->brand = $brand;
            $product->category = $category;

            $this->service->insert($product);
            $this->respond(["message" => "Product added"]);
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function updateProduct(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());

            $json = file_get_contents('php://input');
            $data = json_decode($json);
            $product = new Product();
            $product->id = $data->id;
            $product->name = $data->name;
            $product->price = $data->price;
            $product->image = $data->image;
            $category = new Category();
            $category->category_id = $data->category_id;
            $brand = new Brand();
            $brand->brand_id = $data->brand_id;

            $product->brand = $brand;
            $product->category = $category;

            $result = $this->service->update($product);
            if($result){
                $this->respond(["message" => "Product updated"]);
            } else{
                $this->respondWithError(400, 'Failed to updated');}
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function deleteProduct(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            if(isset($_GET['productId']) && is_numeric($_GET['productId'])) {
                $productId = $_GET['productId'];
                $result = $this->service->delete($productId);
                if($result){
                    $this->respond(["message" => "Product deleted"]);
                } else{
                    $this->respondWithError(400, 'Failed to delete');
                }
            }else{
                throw new Exception('No product id or invalid product id');
            }
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function detailedProduct(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            if(isset($_GET['productId']) && is_numeric($_GET['productId'])) {
                $productId = $_GET['productId'];
                $result = $this->service->getDetailedProduct($productId);
                if($result){
                    $this->respond($result);
                } else{
                    $this->respondWithError(400, 'Failed to retrieve product');
                }
            }else{
                throw new Exception('No product id or product size id');
            }
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function detailedProducts(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            $this->respond($this->service->detailedProducts());
        } catch (Exception $e) {
            $this->respondWithError(400, $e->getMessage());
        }
    }
}

<?php
namespace Controllers;

use Exception;

use Models\Category;
use Models\Size;
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

    public function getSize(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            if(isset($_GET['sizeId']) && is_numeric($_GET['sizeId'])) {
                $sizeId = $_GET['sizeId'];
                $result = $this->service->getOne($sizeId);
                if($result){
                    $this->respond(['size' => ['size_id' => $result->size_id, 'size_name' => $result->size_name, 'category' => ['category_id' => $result->category->category_id, 'category_name' => $result->category->category_name]]]);
                } else{
                    $this->respondWithError(400, 'Failed to retrieve size');
                }
            }else{
                throw new Exception('No size id or invalid size id');
            }
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function createSize(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());

            $json = file_get_contents('php://input');
            $data = json_decode($json);
            $size = new Size();
            $size->size_name = $data->size_name;
            $category = new Category();
            $category->category_id = $data->category_id;

            $size->category = $category;

            $this->service->insert($size);
            $this->respond(["message" => "Size added"]);
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function updateSize(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());

            $json = file_get_contents('php://input');
            $data = json_decode($json);
            $size = new Size();
            $size->size_id = $data->size_id;
            $size->size_name = $data->size_name;
            $category = new Category();
            $category->category_id = $data->category_id;

            $size->category = $category;

            $result = $this->service->update($size);
            if($result){
                $this->respond(["message" => "Size updated"]);
            } else{
                $this->respondWithError(400, 'Failed to updated');}
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function deleteSize(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            if(isset($_GET['sizeId']) && is_numeric($_GET['sizeId'])) {
                $sizeId = $_GET['sizeId'];
                $result = $this->service->delete($sizeId);
                if($result){
                    $this->respond(["message" => "Size deleted"]);
                } else{
                    $this->respondWithError(400, 'Failed to delete');
                }
            }else{
                throw new Exception('No size id or invalid size id');
            }
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function getSizesForProduct(){
        try{
            $this->authService->decodeToken($this->authService->getBearerToken());
            if(isset($_GET['id']) && is_numeric($_GET['id'])) {
                $categoryId = $_GET['id'];
                $sizes = $this->service->sizesForCategory($categoryId);
                if($sizes){
                    $this->respond($sizes);
                }else{
                    $this->respondWithError(400, 'Failed to get sizes');
                }
            }else{
                $this->respondWithError(400, 'Invalid Id');
            }
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }
}

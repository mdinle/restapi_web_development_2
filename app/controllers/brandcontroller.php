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
            $this->authService->decodeToken($this->authService->getBearerToken());
            $brands = $this->brandService->getAll();
            $this->respond($brands);
        } catch (Exception $e) {
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function getBrand(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            if(isset($_GET['brandId']) && is_numeric($_GET['brandId'])) {
                $brandId = $_GET['brandId'];
                $result = $this->brandService->getOne($brandId);
                if($result){
                    $this->respond(['brand' => ['brand_id' => $result->brand_id, 'brand_name' => $result->brand_name, 'description' => $result->description]]);
                } else{
                    $this->respondWithError(400, 'Failed to retrieve brand');
                }
            }else{
                throw new Exception('No brand id or invalid brand id');
            }
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function createBrand(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            $brand = $this->createObjectFromPostedJson("Models\\Brand");
            $this->brandService->insert($brand);
            $this->respond(["message" => "Brand created"]);
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function updateBrand(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            $brand = $this->createObjectFromPostedJson('Models\\Brand');
            $result = $this->brandService->update($brand);
            if($result){
                $this->respond(["message" => "Brand updated"]);
            } else{
                $this->respondWithError(400, 'Failed to updated');}
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function deleteBrand(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            if(isset($_GET['brandId']) && is_numeric($_GET['brandId'])) {
                $brandId = $_GET['brandId'];
                $result = $this->brandService->delete($brandId);
                if($result){
                    $this->respond(["message" => "Brand deleted"]);
                } else{
                    $this->respondWithError(400, 'Failed to delete');
                }
            }else{
                throw new Exception('No brand id or invalid brand id');
            }
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }
}

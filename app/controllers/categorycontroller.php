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

    public function getCategory(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            if(isset($_GET['categoryId']) && is_numeric($_GET['categoryId'])) {
                $categoryId = $_GET['categoryId'];
                $result = $this->categoryService->getOne($categoryId);
                if($result){
                    $this->respond(['category' => ['category_id' => $result->category_id, 'category_name' => $result->category_name]]);
                } else{
                    $this->respondWithError(400, 'Failed to retrieve category');
                }
            }else{
                throw new Exception('No category id or invalid category id');
            }
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function createCategory(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            $category = $this->createObjectFromPostedJson("Models\\Category");
            $this->categoryService->insert($category);
            $this->respond(["message" => "Category created"]);
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function updateCategory(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            $category = $this->createObjectFromPostedJson('Models\\Category');
            $result = $this->categoryService->update($category);
            if($result){
                $this->respond(["message" => "Category updated"]);
            } else{
                $this->respondWithError(400, 'Failed to updated');}
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function deleteCategory(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            if(isset($_GET['categoryId']) && is_numeric($_GET['categoryId'])) {
                $categoryId = $_GET['categoryId'];
                $result = $this->categoryService->delete($categoryId);
                if($result){
                    $this->respond(["message" => "Category deleted"]);
                } else{
                    $this->respondWithError(400, 'Failed to delete');
                }
            }else{
                throw new Exception('No category id or invalid category id');
            }
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }
}

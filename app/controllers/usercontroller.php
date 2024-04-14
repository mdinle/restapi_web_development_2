<?php
namespace Controllers;

use Exception;
use Services\UserService;
use Services\AuthService;

class UserController extends Controller
{
    private $service;
    private $authService;


    // initialize services
    public function __construct()
    {
        $this->service = new UserService();
        $this->authService = new AuthService();
    }

    // signup a new user
    public function signup()
    {
        try {
            $user = $this->createObjectFromPostedJson('Models\\User');
            $result = $this->service->signup($user);
            if($result) {
                $token = $this->authService->createToken($result);
                $this->respond(['user' => ['username' => $result->username, 'email' => $result->email], 'token' => $token]);
            }
        } catch (Exception $e) {
            $this->respondWithError(400, $e->getMessage());
        }
    }

    // login a user
    public function login()
    {
        try {
            $user = $this->createObjectFromPostedJson('Models\\User');
            $result = $this->service->login($user->email, $user->password);
            if ($result) {
                $token = $this->authService->createToken($result);
                $this->respond(['user' => ['username' => $result->username, 'email' => $result->email], 'token' => $token]);
            }
        } catch (Exception $e) {
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function getUsers()
    {
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            $users = $this->service->getUsers();
            $this->respond($users);
        } catch (Exception $e) {
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function createUser(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            $user = $this->createObjectFromPostedJson("Models\\User");
            $this->service->createUser($user);
            $this->respond(["message" => "User created"]);
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function deleteUser(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            if(isset($_GET['userId']) && is_numeric($_GET['userId'])) {
                $userId = $_GET['userId'];
                $result = $this->service->deleteUser($userId);
                if($result){
                    $this->respond(["message" => "User deleted"]);
                } else{
                    $this->respondWithError(400, 'Failed to delete');
                }
            }else{
                throw new Exception('No user id or invalid user id');
            }
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function updateUser(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            $user = $this->createObjectFromPostedJson('Models\\User');
            $result = $this->service->updateUser($user);
            if($result){
                $this->respond(["message" => "User updated"]);
            } else{
                $this->respondWithError(400, 'Failed to updated');}
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }

    public function getUser(){
        try {
            $this->authService->decodeToken($this->authService->getBearerToken());
            if(isset($_GET['userId']) && is_numeric($_GET['userId'])){
                $result = $this->service->getUser($_GET['userId']);
                if($result){
                    $this->respond(['user' => ['id' => $result->id, 'username' => $result->username, 'email' => $result->email, 'status' => $result->status]]);
                }else{
                    $this->respondWithError(400, 'Failed to retrieve user');
                }
            }else{
                throw new Exception('No user id or invalid user id');
            }
        }catch (Exception $e){
            $this->respondWithError(400, $e->getMessage());
        }
    }


}

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
                $token = $this->service->createToken($result);
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
}

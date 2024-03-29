<?php

namespace Controllers;

use Exception;
use Services\UserService;
use \Firebase\JWT\JWT;

class UserController extends Controller
{
    private $service;

    // initialize services
    public function __construct()
    {
        $this->service = new UserService();
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
                $token = $this->service->createToken($result);
                $this->respond(['user' => ['username' => $result->username, 'email' => $result->email], 'token' => $token]);
            }
        } catch (Exception $e) {
            $this->respondWithError(400, $e->getMessage());
        }
    }
}

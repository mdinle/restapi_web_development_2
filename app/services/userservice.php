<?php
namespace Services;

use Exception;
use Repositories\UserRepository;
use Services\AuthService;

class UserService
{

    private $repository;
    private $authService;

    public function __construct()
    {
        $this->repository = new UserRepository();
        $this->authService = new AuthService();
    }

    public function login($email, $password)
    {
        return $this->repository->checkEmailPassword($email, $password);
    }

    public function signup($data)
    {
        try {
            if($data->password != $data->password_confirmation) {
                throw new Exception('Password and password confirmation do not match');
            }
            $passwordError = $this->authService->validatePassword($data->password);
            if($passwordError) {
                throw new Exception($passwordError);
            }
            return $this->repository->signup($data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getUsers()
    {
        return $this->repository->getUsers();
    }

    public function createUser($user){
        return $this->repository->signup($user);
    }

    public function deleteUser($userId){
        return $this->repository->deleteUser($userId);
    }

    public function updateUser($user){
        return $this->repository->updateUser($user);
    }

    public function getUser($userId){
        return $this->repository->getUser($userId);
    }
}

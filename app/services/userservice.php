<?php
namespace Services;

use Exception;
use Repositories\UserRepository;

class UserService
{

    private $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
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
            $passwordError = $this->validatePassword($data->password);
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
}

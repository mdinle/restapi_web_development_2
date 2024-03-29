<?php
namespace Services;

use Exception;
use Repositories\UserRepository;
use \Firebase\JWT\JWT;

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

    public function createToken($data)
    {
        $jwtSecret = (string)getenv('JWT_SECRET');
        return JWT::encode(['id' => $data->id], $jwtSecret, 'HS256');
    }

    public function decodeToken($token)
    {
        $jwtSecret = (string)getenv('JWT_SECRET');
        return JWT::decode($token, $jwtSecret, ['HS256']);
    }

    public function validatePassword($password)
    {
        $pattern = '/^(?=.*[A-Z])(?=.*[\W_])(?=.*[0-9])(?=.{8,})/';
    
        if (preg_match($pattern, $password)) {
            return null;
        } else {
            return "Password must be at least 8 characters long and contain at least one uppercase letter, one special character, and one digit.";
        }
    }
}

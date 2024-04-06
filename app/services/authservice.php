<?php
namespace Services;

use Exception;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AuthService
{
    
    private $jwtSecret;

    public function __construct()
    {
        require __DIR__ . '/../dbconfig.php';
        
        $this->jwtSecret = $jwtSecret;
    }


    public function createToken($data)
    {
        return JWT::encode(['id' => $data->id], $this->jwtSecret, 'HS256');
    }

    public function decodeToken($token)
    {
        try {
            $newToken = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
        } catch(Exception $e) {
            throw new Exception('Unauthorized');
        }

        return true;
    }

    public function getBearerToken()
    {
        if (! isset($_SERVER['HTTP_AUTHORIZATION'])) {
            throw new Exception("Unauthorized");
        }
        if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            throw new Exception("Unauthorized");
        }

        return $matches[1];
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

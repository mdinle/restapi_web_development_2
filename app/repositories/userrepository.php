<?php

namespace Repositories;

use PDO;
use PDOException;
use Exception;
use Repositories\Repository;

class UserRepository extends Repository
{
    // create a new user
    public function signup($data)
    {
        try {
            // hash the password
            $password = $this->hashPassword($data->password);

            // insert the user into the database
            $stmt = $this->connection->prepare("INSERT INTO user (username, password, email) VALUES (:username, :password, :email)");
            $stmt->bindParam(':username', $data->username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':email', $data->email);
            $stmt->execute();

            // retrieve the user with the given username
            $stmt = $this->connection->prepare("SELECT id, username, email FROM user WHERE username = :username");
            $stmt->bindParam(':username', $data->username);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
            $user = $stmt->fetch();

            return $user;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                if(strpos($e->getMessage(), 'username') !== false) {
                    throw new Exception('Username already exists, please choose another one');
                } elseif(strpos($e->getMessage(), 'email') !== false) {
                    throw new Exception('Email already exist');
                }
            } else {
                throw new Exception($e->getMessage());
            }
        }
    }

    public function checkEmailPassword($email, $password)
    {
        try {
            // retrieve the user with the given username
            $stmt = $this->connection->prepare("SELECT id, username, password, email FROM user WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
            $user = $stmt->fetch();

            if (!$user) {
                throw new Exception('Account does not exist, please sign up first');
            }
            // verify if the password matches the hash in the database
            $result = $this->verifyPassword($password, $user->password);

            if (!$result) {
                throw new Exception('Invalid password, please try again');
            }

            // do not pass the password hash to the caller
            $user->password = "";

            return $user;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getUsers()
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, username, email, created_at, status FROM user");
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
            $users = $stmt->fetchAll();

            return $users;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function deleteUser($id){
        try {
            $stmt = $this->connection->prepare("DELETE FROM user WHERE id = :id;");
            $stmt->execute([
                ":id" => $id,
            ]);

            return $stmt->rowCount() > 0;

        } catch (PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    public function updateUser($user){
        try{
            $stmt = $this->connection->prepare("UPDATE user SET username = :username, status = :status, email = :email WHERE id = :id");
            $stmt->execute([
                ":id" => $user->id,
                ':username' => $user->username,
                ':status' => $user->status,
                ':email' => $user->email,
            ]);

            return $stmt->rowCount() > 0;

        }catch (PDOException $e){
            if ($e->getCode() === '23000') {
                if(strpos($e->getMessage(), 'username') !== false) {
                    throw new Exception('Username already exists');
                } elseif(strpos($e->getMessage(), 'email') !== false) {
                    throw new Exception('Email already exist');
                }
            } else {
                throw new Exception($e->getMessage());
            }
        }
    }

    public function getUser($id){
        try {
            $stmt = $this->connection->prepare("SELECT id, username, email, created_at, status FROM user WHERE id = :id LIMIT 1;");
            $stmt->execute([
                ":id" => $id
            ]);

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
            $user = $stmt->fetch();

            if($user){
                return $user;
            }
            return false;
        }catch (PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    // hash the password (currently uses bcrypt)
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // verify the password hash
    public function verifyPassword($input, $hash)
    {
        return password_verify($input, $hash);
    }
}

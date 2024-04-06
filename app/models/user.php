<?php
namespace Models;

class User
{

    public int $id;
    public string $username;
    public string $password;
    public string $email;
    public $created_at;
    public $status;
    public string $password_confirmation;

}

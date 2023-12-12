<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password']; // Adjust according to your database

    // Method to register a new user
    public function registerUser($data)
    {
        return $this->insert($data);
    }

    // Method to fetch user by email
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
}

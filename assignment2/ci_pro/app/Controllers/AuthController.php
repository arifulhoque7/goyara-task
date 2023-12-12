<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function register()
    {
        helper(['form']);

        $model = new UserModel();

        if ($this->request->getPost()) {
            $rules = [
                'username' => 'required|min_length[3]|max_length[20]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]'
            ];

            if (!$this->validate($rules)) {
                return view('register', ['validation' => $this->validator]);
            } else {
                $password = $this->request->getPost('password');
                if (!empty($password) && is_string($password)) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                }
                $model->registerUser([
                    'username' => $this->request->getPost('username'),
                    'email' => $this->request->getPost('email'),
                    'password' => $hashedPassword
                ]);

                return redirect()->to('/login');
            }
        }

        return view('register');
    }

    public function login()
    {
        helper(['form']);

        if ($this->request->getPost()) {
            $model = new UserModel();
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $user = $model->getUserByEmail($email);
            var_dump($user);
            if ($user && password_verify($password, $user['password'])) {
                // Start session and store user data
                $session = session();
                $sessionData = [
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'isLoggedIn' => true
                ];
                $session->set($sessionData);

                return redirect()->to('/dashboard');
            } else {
                return redirect()->to('/login')->with('error', 'Invalid email or password');
            }
        }

        return view('login');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}

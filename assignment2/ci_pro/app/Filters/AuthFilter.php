<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if the user is not logged in
        $session = session();
        // var_dump($session->isLoggedIn && $request->uri->getPath() === '/login');
        if (!$session->isLoggedIn && $request->uri->getPath() !== '/login') {
            // Redirect to login page
            return redirect()->to('/login');
        }
        if($session->isLoggedIn && $request->uri->getPath() === '/login') {
            return redirect()->to('/dashboard');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }
}

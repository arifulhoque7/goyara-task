<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        // Load the dashboard view
        return view('dashboard');
    }
}

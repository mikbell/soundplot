<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController
{
    public function index()
    {
        return inertia('Dashboard');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function userDashboard()
    {
        return view('user.dashboard');
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }
} 
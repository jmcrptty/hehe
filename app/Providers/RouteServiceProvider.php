<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public function redirectTo() {
        if (Auth::check()) {
            $userRole = strtolower(Auth::user()->Role);
            if ($userRole === 'admin' || $userRole === 'super_admin') {
                return route('admin.dashboard');
            }
            return route('user.dashboard');
        }
        return '/login';
    }

    // ... kode lainnya ...
} 
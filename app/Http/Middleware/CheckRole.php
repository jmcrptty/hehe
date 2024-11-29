<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$Roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $userRole = strtolower(Auth::user()->Role);

        // Cek apakah Role user ada dalam Roles yang diizinkan
        if (in_array($userRole, array_map('strtolower', $Roles))) {
            return $next($request);
        }

        // Redirect berdasarkan role
        if ($userRole === 'admin' || $userRole === 'super_admin') {
            return redirect()->route('admin.dashboard');
        }
        // Dosen dan mahasiswa diarahkan ke user.dashboard
        return redirect()->route('user.dashboard');
        
        $userRole = strtolower(Auth::user()->role);

        if (in_array($userRole, array_map('strtolower', $Roles))) {
            return $next($request);
        }

        abort(403, 'Akses ditolak.');
    }
    
}
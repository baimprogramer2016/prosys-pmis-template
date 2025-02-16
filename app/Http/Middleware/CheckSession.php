<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class CheckSession
{
    public function handle($request, Closure $next)
    {
        if (
            $request->is('login') ||  // URL login
            $request->is('register') ||  // URL register
            $request->is('password/*') ||  // URL reset password
            Auth::check() // User sudah login
        ) {
            return $next($request);
        }

        // Jika session habis, redirect ke halaman login
        return redirect()->route('login')->with('message', 'Session expired, please login again.');
       
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Ja lietotājs nav ielogojies
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Admins nedrīkst izmantot parasto lietotāja sadaļu
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin');
        }

        // Parasts lietotājs turpina
        return $next($request);
    }
}
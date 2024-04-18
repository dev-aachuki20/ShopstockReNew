<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PasswordProtectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if password is entered
        if ($request->session()->has('password_entered')) {
            return $next($request);
        }

        // Render password modal form HTML
        return response(view('admin.protect-password-pages.index'));
        //return $next($request);
    }
}

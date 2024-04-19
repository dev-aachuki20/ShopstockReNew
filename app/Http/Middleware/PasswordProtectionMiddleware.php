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

        if($request->ajax()){
            return $next($request);
        }

        if (!$request->ajax() && $request->session()->has('password_entered'))
        {
            $request->session()->forget('password_entered');
            return $next($request);
        }

        return response(view('admin.protect-password-pages.index'));
    }
}

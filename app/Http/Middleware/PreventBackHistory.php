<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (Auth::check()) {
            if (Auth::user()->is_active != 1) {
                Auth::logout();
                return redirect('/login')->with('error', 'Your account is not active.');
            }
        }

        $headers = [
            'Cache-Control'      => 'nocache, no-store, max-age=0, must-revalidate',
            'Pragma'     => 'no-cache',
            'Expires' => 'Sun, 02 Jan 1990 00:00:00 GMT'

        ];
        $response = $next($request);

        foreach ($headers as $key => $value) {

            $response->headers->set($key, $value);
        }

        return $response;
    }
}

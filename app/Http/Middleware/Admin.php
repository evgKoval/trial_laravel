<?php

namespace App\Http\Middleware;

use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user() && auth()->user()->is_admin == 1) {
            return $next($request);
        }

        \Session::put('error', 'You don\'t have admin access');
        return redirect('/');
    }
}

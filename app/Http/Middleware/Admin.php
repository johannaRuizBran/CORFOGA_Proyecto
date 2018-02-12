<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();
        if($user->role == 'i') {
            return redirect()->route('inspectores');
        }
        elseif ($user->role == 'p') {
            return redirect()->route('productores');
        }
        return $next($request);
    }
}

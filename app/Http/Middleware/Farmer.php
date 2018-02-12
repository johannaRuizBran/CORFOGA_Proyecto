<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class Farmer
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
        if($user->role == 'a') {
            return redirect()->route('admin.inicio');
        }
        elseif ($user->role == 'i') {
            return redirect()->route('inspectores');
        }
        return $next($request);
    }
}

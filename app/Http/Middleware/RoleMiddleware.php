<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kullanıcının rolünü kontrol ediyoruz
        if (Auth::check() && Auth::user()->role == 1) {
            return $next($request); // Rol 1 ise devam et
        }

        // Rol 1 değilse yetki hatası döndürüyoruz
        return response()->json(['error' => 'Unauthorized'], 403);
    }

}

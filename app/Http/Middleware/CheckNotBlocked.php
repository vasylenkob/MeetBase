<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckNotBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->is_blocked) {
            // Дозволяємо вийти з акаунту та перейти на головну
            if ($request->routeIs('logout') || $request->routeIs('home')) {
                return $next($request);
            }
            abort(403, 'Ваш акаунт заблоковано адміністратором. Якщо ви вважаєте це помилкою — зверніться до підтримки.');
        }

        return $next($request);
    }
}

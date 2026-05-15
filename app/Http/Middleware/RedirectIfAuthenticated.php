<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next, string ...$guards): Response
{
    $guards = empty($guards) ? [null] : $guards;

    foreach ($guards as $guard) {
        if (Auth::guard($guard)->check()) {
            $user = Auth::user();
            
            // Si c'est l'admin (ton ID 2 ou via le champ role)
            if ($user->role === 'admin') {
                return redirect()->route('admin.property.index'); 
            }

            // Sinon, redirection vers la page d'accueil par défaut
            return redirect(RouteServiceProvider::HOME);
        }
    }

    return $next($request);
}
}

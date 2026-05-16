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
                
                // Si l'utilisateur a le rôle d'administrateur
                if ($user && $user->role === 'admin') {
                    /**
                     * CORRECTION : Remplacement de 'admin.property.index' par 'admin.listings.index'
                     * Si ton préfixe d'administration n'utilise pas de sous-domaine/groupe nommé, 
                     * tu peux simplement utiliser 'listings.index'.
                     */
                    if (\Route::has('admin.listings.index')) {
                        return redirect()->route('admin.listings.index');
                    } elseif (\Route::has('listings.index')) {
                        return redirect()->route('listings.index');
                    }
                }

                // Sinon, redirection vers la page d'accueil ou tableau de bord par défaut
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
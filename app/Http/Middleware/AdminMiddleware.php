<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Correction : On vérifie si l'utilisateur est connecté ET si son rôle vaut strictement 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Si ce n'est pas le cas, on bloque l'accès avec une erreur 403
        abort(403, 'Accès réservé aux administrateurs.');
    }
}
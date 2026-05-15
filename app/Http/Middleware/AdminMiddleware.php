<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie si l'utilisateur est connecté ET si son champ is_admin est à 1 (ou son rôle est admin)
        if (Auth::check() && Auth::user()->is_admin == 1) {
            return $next($request);
        }

        // Sinon, on bloque l'accès
        abort(403, 'Accès réservé aux administrateurs.');
    }
}
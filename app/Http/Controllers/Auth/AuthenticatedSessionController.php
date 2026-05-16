<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Valide l'email et le mot de passe via la LoginRequest
        $request->authenticate();

        // 2. Régénère la session pour éviter les attaques de fixation de session
        $request->session()->regenerate();

        // 3. LOGIQUE DE REDIRECTION CORRIGÉE
        // On vérifie si la colonne 'role' en base de données vaut exactement 'admin'
        if ($request->user()->role === 'admin') {
            return redirect()->route('admin.index'); 
        }

        // Sinon, on redirige l'utilisateur simple vers sa page par défaut (/dashboard)
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
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
    // 1. Valide l'email et le mot de passe via la LoginRequest que tu as envoyée
    $request->authenticate();

    // 2. Régénère la session pour éviter les attaques de fixation de session
    $request->session()->regenerate();

    // 3. LOGIQUE DE REDIRECTION PERSONNALISÉE
    // On vérifie si l'utilisateur qui vient de se connecter est admin
    if ($request->user()->is_admin) {
        return redirect()->route('admin.index'); 
    }

    // Sinon, on le redirige vers sa page par défaut (souvent /dashboard)
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

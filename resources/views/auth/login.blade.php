@extends('layouts.app-immo')

@section('content')
<div class="auth-terminal-bg">
    
    <div class="auth-card-terminal">
        
        {{-- HEADER D'AUTHENTIFICATION --}}
        <div class="auth-header">
            <div class="logo-shield">
                <x-application-logo />
            </div>
            <h2 class="auth-title">Espace <span class="accent">Client</span></h2>
            <div class="auth-status-bar">
                <span class="status-dot"></span>
                <p>Connexion sécurisée à votre compte</p>
            </div>
        </div>

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            {{-- CHAMP EMAIL --}}
            <div class="auth-group">
                <label for="email">Adresse Email</label>
                <div class="input-control">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="votre@email.com">
                    <div class="input-glow"></div>
                </div>
                <x-input-error :messages="$errors->get('email')" class="auth-error" />
            </div>

            {{-- CHAMP PASSWORD --}}
            <div class="auth-group">
                <label for="password">Mot de passe</label>
                <div class="input-control">
                    <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                    <div class="input-glow"></div>
                </div>
                <x-input-error :messages="$errors->get('password')" class="auth-error" />
            </div>

            {{-- OPTIONS & RECOUVREMENT --}}
            <div class="auth-meta">
                <label class="remember-me" for="remember">
                    {{-- Utilisation de la classe visually-hidden pour préserver l'accessibilité clavier --}}
                    <input type="checkbox" id="remember" name="remember">
                    <span class="checkmark"></span>
                    <span class="label-text">Se souvenir de moi</span>
                </label>
                
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
                @endif
            </div>

            {{-- BOUTON VALIDATION --}}
            <button type="submit" class="btn-auth-submit">
                <span class="btn-shimmer"></span>
                <span class="btn-content">Se connecter</span>
            </button>

            {{-- FOOTER --}}
            <div class="auth-footer">
                <p>Pas encore membre ? <a href="{{ route('register') }}" class="register-link">Créer un compte</a></p>
            </div>
        </form>
    </div>
</div>

<style>
    :root {
        --auth-bg: #f1f5f9;
        --auth-accent: #3182ce;
        --auth-dark: #1e293b;
        --auth-border: #e2e8f0;
        --auth-text: #475569;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .auth-terminal-bg {
        min-height: 90vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at 0% 0%, #ffffff 0%, #e2e8f0 100%);
        padding: 30px 15px;
    }

    .auth-card-terminal {
        width: 100%;
        max-width: 460px;
        background: white;
        padding: 50px 40px;
        border-radius: 32px;
        box-shadow: 0 25px 60px -15px rgba(30, 41, 59, 0.12);
        border: 1px solid white;
        position: relative;
        animation: authEntrance 0.7s cubic-bezier(0.23, 1, 0.32, 1);
        box-sizing: border-box;
    }

    /* Header & Status */
    .auth-header { text-align: center; margin-bottom: 40px; }
    
    .logo-shield {
        display: inline-flex;
        background: var(--auth-dark);
        color: white;
        padding: 14px;
        border-radius: 20px;
        margin-bottom: 20px;
        box-shadow: 0 12px 24px rgba(30, 41, 59, 0.2);
    }
    .logo-shield svg { width: 35px; height: 35px; fill: currentColor; }

    .auth-title { font-size: 1.9rem; font-weight: 950; color: #0f172a; letter-spacing: -1.5px; margin: 0; }
    .auth-title .accent { color: var(--auth-accent); }

    .auth-status-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 12px;
        padding: 0 10px;
    }
    .status-dot {
        width: 6px;
        height: 6px;
        background: var(--auth-accent);
        border-radius: 50%;
        animation: blink 1.5s infinite;
        flex-shrink: 0;
    }
    .auth-status-bar p {
        color: #94a3b8;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 1.2px;
        margin: 0;
        text-transform: uppercase;
    }

    /* Form Fields */
    .auth-form { display: flex; flex-direction: column; gap: 24px; }
    .auth-group label {
        display: block;
        font-size: 0.65rem;
        font-weight: 900;
        color: var(--auth-text);
        text-transform: uppercase;
        margin-bottom: 10px;
        letter-spacing: 1.2px;
        padding-left: 4px;
    }

    .input-control { position: relative; }
    .input-control input {
        width: 100%;
        background: #f8fafc;
        border: 2px solid var(--auth-border);
        padding: 16px 20px;
        border-radius: 16px;
        font-weight: 700;
        font-size: 0.95rem;
        transition: var(--transition);
        color: var(--auth-dark);
        box-sizing: border-box;
    }

    .input-control input:focus {
        border-color: var(--auth-accent);
        background: white;
        outline: none;
    }

    .input-glow {
        position: absolute;
        inset: 0;
        border-radius: 16px;
        pointer-events: none;
        transition: var(--transition);
        box-shadow: 0 0 0 0 rgba(49, 130, 206, 0);
    }
    .input-control input:focus + .input-glow {
        box-shadow: 0 0 0 5px rgba(49, 130, 206, 0.08);
    }

    /* Checkbox Custom accessible */
    .auth-meta { display: flex; justify-content: space-between; align-items: center; gap: 10px; }
    .remember-me { display: flex; align-items: center; cursor: pointer; position: relative; }
    
    /* Masquage propre préservant le focus clavier et l'accessibilité */
    .remember-me input {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0;
    }
    
    .checkmark {
        height: 20px; width: 20px; background: #f1f5f9; border: 2px solid var(--auth-border);
        border-radius: 7px; transition: var(--transition); flex-shrink: 0;
    }
    .remember-me:hover input ~ .checkmark { border-color: var(--auth-accent); }
    .remember-me input:checked ~ .checkmark { background: var(--auth-accent); border-color: var(--auth-accent); }
    
    /* Indicateur visuel pour la navigation au clavier (Focus) */
    .remember-me input:focus-visible ~ .checkmark {
        outline: 2px solid var(--auth-accent);
        outline-offset: 2px;
    }
    
    .label-text { margin-left: 10px; font-size: 0.8rem; color: #64748b; font-weight: 700; user-select: none; }
    .forgot-link { font-size: 0.8rem; color: var(--auth-accent); text-decoration: none; font-weight: 800; transition: var(--transition); white-space: nowrap; }
    .forgot-link:hover { opacity: 0.7; }

    /* Button Auth */
    .btn-auth-submit {
        position: relative;
        background: var(--auth-dark);
        color: white;
        border: none;
        padding: 18px;
        border-radius: 16px;
        font-weight: 950;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        cursor: pointer;
        overflow: hidden;
        transition: var(--transition);
        box-shadow: 0 10px 30px rgba(30, 41, 59, 0.2);
        width: 100%;
    }

    .btn-auth-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(30, 41, 59, 0.3);
        background: #0f172a;
    }

    .btn-shimmer {
        position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
        transition: 0.5s;
    }
    .btn-auth-submit:hover .btn-shimmer { left: 100%; transition: 0.5s; }

    /* Footer */
    .auth-footer {
        text-align: center;
        margin-top: 10px;
        border-top: 2px solid #f8fafc;
        padding-top: 25px;
    }
    .auth-footer p { font-size: 0.85rem; color: #94a3b8; font-weight: 600; margin: 0; }
    .register-link { color: var(--auth-accent); text-decoration: none; font-weight: 800; margin-left: 4px; white-space: nowrap; }

    /* Animations */
    @keyframes authEntrance {
        from { opacity: 0; transform: translateY(20px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    .auth-error { color: #ef4444; font-size: 0.75rem; margin-top: 8px; font-weight: 700; list-style: none; padding-left: 4px; }

    /* Media Query Mobile */
    @media (max-width: 480px) {
        .auth-card-terminal { padding: 40px 24px; border-radius: 24px; }
        .auth-title { font-size: 1.6rem; }
        .auth-status-bar p { font-size: 0.62rem; letter-spacing: 0.5px; }
        .auth-meta { flex-direction: column; align-items: flex-start; gap: 16px; }
        .forgot-link { padding-left: 4px; }
        .btn-auth-submit { padding: 16px; font-size: 0.8rem; letter-spacing: 1px; }
    }
</style>
@endsection
@extends('layouts.app-immo')

@section('content')
<div class="auth-terminal-bg">
    
    <div class="auth-card-terminal">
        
        {{-- HEADER D'AUTHENTIFICATION --}}
        <div class="auth-header">
            <div class="logo-shield">
                <x-application-logo />
            </div>
            <h2 class="auth-title">Accès <span class="accent">Sentinel</span></h2>
            <div class="auth-status-bar">
                <span class="status-dot"></span>
                <p>VÉRIFICATION DES IDENTIFIANTS REQUIS</p>
            </div>
        </div>

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            {{-- CHAMP EMAIL --}}
            <div class="auth-group">
                <label>Identifiant Opérateur (Email)</label>
                <div class="input-control">
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nom@agence.immo">
                    <div class="input-glow"></div>
                </div>
                <x-input-error :messages="$errors->get('email')" class="auth-error" />
            </div>

            {{-- CHAMP PASSWORD --}}
            <div class="auth-group">
                <label>Code d'accès sécurisé</label>
                <div class="input-control">
                    <input type="password" name="password" required placeholder="••••••••">
                    <div class="input-glow"></div>
                </div>
                <x-input-error :messages="$errors->get('password')" class="auth-error" />
            </div>

            {{-- OPTIONS & RECOUVREMENT --}}
            <div class="auth-meta">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    <span class="checkmark"></span>
                    <span class="label-text">Maintenir la session</span>
                </label>
                
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Passcode perdu ?</a>
                @endif
            </div>

            {{-- BOUTON VALIDATION --}}
            <button type="submit" class="btn-auth-submit">
                <span class="btn-shimmer"></span>
                <span class="btn-content">INITIALISER LA CONNEXION</span>
            </button>

            {{-- FOOTER --}}
            <div class="auth-footer">
                <p>Nouvel opérateur ? <a href="{{ route('register') }}">Enregistrer l'accès</a></p>
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
        padding: 30px;
    }

    .auth-card-terminal {
        width: 100%;
        max-width: 460px;
        background: white;
        padding: 60px 45px;
        border-radius: 32px;
        box-shadow: 0 25px 60px -15px rgba(30, 41, 59, 0.12);
        border: 1px solid white;
        position: relative;
        animation: authEntrance 0.7s cubic-bezier(0.23, 1, 0.32, 1);
    }

    /* Header & Status */
    .auth-header { text-align: center; margin-bottom: 45px; }
    
    .logo-shield {
        display: inline-flex;
        background: var(--auth-dark);
        color: white;
        padding: 14px;
        border-radius: 20px;
        margin-bottom: 25px;
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
    }
    .status-dot {
        width: 6px;
        height: 6px;
        background: var(--auth-accent);
        border-radius: 50%;
        animation: blink 1.5s infinite;
    }
    .auth-status-bar p {
        color: #94a3b8;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 1.5px;
        margin: 0;
    }

    /* Form Fields */
    .auth-form { display: flex; flex-direction: column; gap: 28px; }
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
        padding: 16px 22px;
        border-radius: 16px;
        font-weight: 700;
        font-size: 0.95rem;
        transition: var(--transition);
        color: var(--auth-dark);
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

    /* Checkbox Custom */
    .auth-meta { display: flex; justify-content: space-between; align-items: center; }
    .remember-me { display: flex; align-items: center; cursor: pointer; position: relative; }
    .remember-me input { position: absolute; opacity: 0; cursor: pointer; }
    
    .checkmark {
        height: 20px; width: 20px; background: #f1f5f9; border: 2px solid var(--auth-border);
        border-radius: 7px; transition: var(--transition);
    }
    .remember-me:hover input ~ .checkmark { border-color: var(--auth-accent); }
    .remember-me input:checked ~ .checkmark { background: var(--auth-accent); border-color: var(--auth-accent); }
    
    .label-text { margin-left: 12px; font-size: 0.8rem; color: #64748b; font-weight: 700; }
    .forgot-link { font-size: 0.8rem; color: var(--auth-accent); text-decoration: none; font-weight: 800; transition: var(--transition); }
    .forgot-link:hover { opacity: 0.7; }

    /* Button Auth */
    .btn-auth-submit {
        position: relative;
        background: var(--auth-dark);
        color: white;
        border: none;
        padding: 20px;
        border-radius: 18px;
        font-weight: 950;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        cursor: pointer;
        overflow: hidden;
        transition: var(--transition);
        box-shadow: 0 10px 30px rgba(30, 41, 59, 0.2);
    }

    .btn-auth-submit:hover {
        transform: translateY(-3px);
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
        margin-top: 15px;
        border-top: 2px solid #f8fafc;
        padding-top: 30px;
    }
    .auth-footer p { font-size: 0.85rem; color: #94a3b8; font-weight: 600; }
    .auth-footer a { color: var(--auth-accent); text-decoration: none; font-weight: 800; margin-left: 6px; }

    /* Animations */
    @keyframes authEntrance {
        from { opacity: 0; transform: translateY(30px) scale(0.96); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    .auth-error { color: #ef4444; font-size: 0.75rem; margin-top: 8px; font-weight: 700; list-style: none; padding-left: 4px; }
</style>
@endsection
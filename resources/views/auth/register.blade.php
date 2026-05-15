@extends('layouts.app-immo')

@section('content')
<div class="register-terminal-bg">
    
    <div class="register-card">
        
        {{-- HEADER D'ENREGISTREMENT --}}
        <div class="register-header">
            <div class="icon-deploy">
                <x-application-logo />
            </div>
            <h2 class="register-title">Nouveau <span class="accent">Profil</span></h2>
            <div class="system-path">
                <span class="path-root">SENTINEL</span>
                <span class="path-separator">/</span>
                <span class="path-active">INIT_ACCESS</span>
            </div>
        </div>

        <form method="POST" action="{{ route('register') }}" class="register-form">
            @csrf

            {{-- NOM COMPLET --}}
            <div class="form-field">
                <label>Nom complet de l'opérateur</label>
                <div class="input-container">
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Ex: Jean Dupont">
                    <div class="input-indicator"></div>
                </div>
                <x-input-error :messages="$errors->get('name')" class="field-error" />
            </div>

            {{-- EMAIL --}}
            <div class="form-field">
                <label>Identifiant de messagerie</label>
                <div class="input-container">
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nom@exemple.com">
                    <div class="input-indicator"></div>
                </div>
                <x-input-error :messages="$errors->get('email')" class="field-error" />
            </div>

            {{-- PASSWORDS GRID --}}
            <div class="password-grid">
                <div class="form-field">
                    <label>Clé de sécurité</label>
                    <div class="input-container">
                        <input type="password" name="password" required placeholder="••••••••">
                    </div>
                </div>

                <div class="form-field">
                    <label>Confirmation</label>
                    <div class="input-container">
                        <input type="password" name="password_confirmation" required placeholder="••••••••">
                    </div>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="field-error error-mt" />

            {{-- BOUTON ACTION --}}
            <button type="submit" class="btn-deploy">
                <span class="btn-label">DÉPLOYER LE COMPTE</span>
                <svg class="btn-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </button>

            {{-- REDIRECTION LOGIN --}}
            <div class="register-footer">
                <p>Déjà membre du réseau ? 
                    <a href="{{ route('login') }}" class="login-link">S'identifier</a>
                </p>
            </div>
        </form>
    </div>
</div>

<style>
    :root {
        --reg-blue: #3182ce;
        --reg-dark: #1a202c;
        --reg-border: #e2e8f0;
        --reg-bg-soft: #f8fafc;
        --reg-text-muted: #94a3b8;
        --ease: cubic-bezier(0.16, 1, 0.3, 1);
    }

    .register-terminal-bg {
        min-height: 95vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at bottom left, #ffffff 0%, #e2e8f0 100%);
        padding: 40px 20px;
    }

    .register-card {
        width: 100%;
        max-width: 520px;
        background: white;
        padding: 60px 50px;
        border-radius: 40px;
        box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.08);
        border: 1px solid white;
        animation: slideUp 0.8s var(--ease);
    }

    /* Header & Breadcrumbs */
    .register-header { text-align: center; margin-bottom: 50px; }
    
    .icon-deploy {
        display: inline-flex;
        background: var(--reg-blue);
        color: white;
        padding: 16px;
        border-radius: 22px;
        margin-bottom: 25px;
        box-shadow: 0 15px 30px rgba(49, 130, 206, 0.3);
    }
    .icon-deploy svg { width: 32px; height: 32px; }

    .register-title { font-size: 2.2rem; font-weight: 950; color: var(--reg-dark); margin: 0; letter-spacing: -2px; text-transform: uppercase; }
    .register-title .accent { color: var(--reg-blue); }

    .system-path {
        margin-top: 15px;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 2px;
        display: flex;
        justify-content: center;
        gap: 8px;
    }
    .path-root { color: var(--reg-text-muted); }
    .path-separator { color: #cbd5e1; }
    .path-active { color: var(--reg-blue); }

    /* Fields */
    .register-form { display: flex; flex-direction: column; gap: 30px; }
    
    .form-field label {
        display: block;
        font-size: 0.65rem;
        font-weight: 900;
        color: #475569;
        text-transform: uppercase;
        margin-bottom: 12px;
        letter-spacing: 1.5px;
        padding-left: 6px;
    }

    .input-container { position: relative; }
    .input-container input {
        width: 100%;
        background: var(--reg-bg-soft);
        border: 2px solid var(--reg-border);
        padding: 18px 22px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.95rem;
        transition: all 0.4s var(--ease);
        color: var(--reg-dark);
    }

    .input-container input:focus {
        background: white;
        border-color: var(--reg-blue);
        outline: none;
        box-shadow: 0 10px 25px -5px rgba(49, 130, 206, 0.1);
    }

    .password-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

    /* Button Action */
    .btn-deploy {
        background: var(--reg-blue);
        color: white;
        border: none;
        padding: 22px;
        border-radius: 24px;
        font-weight: 950;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 3px;
        cursor: pointer;
        transition: all 0.4s var(--ease);
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        box-shadow: 0 15px 35px rgba(49, 130, 206, 0.2);
        margin-top: 10px;
    }

    .btn-deploy:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 20px 40px rgba(49, 130, 206, 0.3);
        background: #2b6cb0;
    }

    .btn-svg { width: 20px; height: 20px; }

    /* Footer */
    .register-footer {
        text-align: center;
        margin-top: 20px;
        border-top: 2px solid #f8fafc;
        padding-top: 35px;
    }
    .register-footer p { font-size: 0.85rem; color: var(--reg-text-muted); font-weight: 700; }
    .login-link {
        color: var(--reg-dark);
        text-decoration: none;
        font-weight: 900;
        margin-left: 10px;
        padding-bottom: 2px;
        border-bottom: 2px solid #e2e8f0;
        transition: 0.3s;
    }
    .login-link:hover { border-color: var(--reg-blue); color: var(--reg-blue); }

    /* Animations & Utils */
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .field-error { color: #ef4444; font-size: 0.7rem; font-weight: 800; margin-top: 8px; list-style: none; padding-left: 6px; }
    .error-mt { margin-top: -15px; }

    @media (max-width: 600px) {
        .password-grid { grid-template-columns: 1fr; }
        .register-card { padding: 40px 30px; }
    }
</style>
@endsection
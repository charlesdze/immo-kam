@extends('layouts.app-immo')

@section('content')
<div class="directory-container">
    
    {{-- HEADER : NAVIGATION --}}
    <div class="directory-header">
        <div class="header-left">
            <h1 class="directory-title">
                Sentinel <span class="directory-accent">Directory</span>
            </h1>
            <div class="header-badge">
                <span class="pulse-icon"></span>
                <p>CONTRÔLE DES ACCÈS ET PRIVILÈGES</p>
            </div>
        </div>
        <a href="{{ route('admin.index') }}" class="btn-dashboard">
            <span class="icon">⬗</span> DASHBOARD
        </a>
    </div>

    {{-- LISTE DES UTILISATEURS --}}
    <div class="table-card">
        <table class="sentinel-table">
            <thead>
                <tr>
                    <th>Identité Numérique</th>
                    <th>Niveau de Privilège</th>
                    <th>Date d'entrée</th>
                    <th class="text-right">Action Commande</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="user-row">
                        <td>
                            <div class="user-identity">
                                {{-- Avatar Stylisé avec Initiale --}}
                                <div class="avatar-box {{ $user->is_admin ? 'admin-glow' : 'user-glow' }}">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="user-info">
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="user-id-tag">UID_{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }} • {{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($user->is_admin)
                                <div class="privilege-badge admin">
                                    <span class="shield-icon">🛡️</span> ROOT_ACCESS
                                </div>
                            @else
                                <div class="privilege-badge user">
                                    <span class="shield-icon">👤</span> STANDARD_USER
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="date-stamp">
                                {{ $user->created_at ? $user->created_at->format('d/m/Y') : 'DATA_NULL' }}
                            </span>
                        </td>
                        <td class="text-right">
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('ACTION CRITIQUE : Confirmer la révocation définitive ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-revoke">
                                        RÉVOQUER L'ACCÈS
                                    </button>
                                </form>
                            @else
                                <span class="session-badge">ACTIVE_SESSION</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- PAGINATION CUSTOM --}}
    <div class="pagination-wrapper">
        {{ $users->links() }}
    </div>
</div>

<style>
    :root {
        --sentinel-dark: #1a202c;
        --sentinel-blue: #3182ce;
        --sentinel-purple: #805ad5;
        --sentinel-red: #e53e3e;
        --sentinel-border: #e2e8f0;
        --sentinel-bg: #f8fafc;
        --font-mono: 'JetBrains Mono', 'Fira Code', monospace;
    }

    .directory-container {
        max-width: 1240px;
        margin: 40px auto;
        padding: 0 25px;
        animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Header */
    .directory-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        border-bottom: 3px solid var(--sentinel-border);
        padding-bottom: 25px;
    }

    .directory-title { font-size: 2.2rem; font-weight: 950; color: var(--sentinel-dark); margin: 0; letter-spacing: -2px; text-transform: uppercase; }
    .directory-accent { color: #cbd5e0; font-weight: 300; }

    .header-badge {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 8px;
    }
    .header-badge p { color: #94a3b8; font-weight: 800; font-size: 0.7rem; letter-spacing: 2px; margin: 0; }

    .pulse-icon {
        width: 8px; height: 8px; background: var(--sentinel-blue); border-radius: 50%;
        box-shadow: 0 0 0 rgba(49, 130, 206, 0.4);
        animation: pulse 2s infinite;
    }

    .btn-dashboard {
        background: var(--sentinel-dark);
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 900;
        text-decoration: none;
        font-size: 0.7rem;
        letter-spacing: 1px;
        transition: 0.3s;
    }
    .btn-dashboard:hover { transform: translateX(-5px); background: var(--sentinel-blue); }

    /* Table Design */
    .table-card {
        background: white;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.04);
        border: 1px solid var(--sentinel-border);
    }

    .sentinel-table { width: 100%; border-collapse: collapse; }
    
    .sentinel-table thead tr { background: #f8fafc; border-bottom: 2px solid var(--sentinel-border); }
    .sentinel-table th {
        padding: 22px 35px; font-size: 0.65rem; font-weight: 900; color: #64748b;
        text-transform: uppercase; letter-spacing: 1.5px; text-align: left;
    }

    .user-row { border-bottom: 1px solid #f1f5f9; transition: 0.2s; }
    .user-row:hover { background: #fcfdfe; }

    .user-identity { display: flex; align-items: center; gap: 20px; }
    
    .avatar-box {
        height: 48px; width: 48px; border-radius: 15px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 900; font-size: 1.2rem;
    }
    .admin-glow { background: #faf5ff; color: var(--sentinel-purple); border: 2px solid #e9d8fd; }
    .user-glow { background: #f0f9ff; color: var(--sentinel-blue); border: 2px solid #bae6fd; }

    .user-name { font-weight: 900; color: var(--sentinel-dark); font-size: 0.95rem; }
    .user-id-tag { font-family: var(--font-mono); font-size: 0.7rem; color: #94a3b8; margin-top: 3px; }

    /* Badges */
    .privilege-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 14px; border-radius: 8px;
        font-size: 0.65rem; font-weight: 900; letter-spacing: 0.5px;
    }
    .privilege-badge.admin { background: #805ad5; color: white; }
    .privilege-badge.user { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

    .date-stamp { font-family: var(--font-mono); font-weight: 700; color: #64748b; font-size: 0.8rem; }

    /* Buttons */
    .btn-revoke {
        background: transparent; border: 2px solid #fee2e2; color: var(--sentinel-red);
        padding: 10px 18px; border-radius: 12px; font-size: 0.65rem; font-weight: 900;
        cursor: pointer; transition: 0.3s;
    }
    .btn-revoke:hover { background: var(--sentinel-red); color: white; border-color: var(--sentinel-red); transform: scale(1.05); }

    .session-badge {
        font-size: 0.65rem; color: #cbd5e1; font-weight: 900;
        padding: 10px 18px; border: 2px dashed #e2e8f0; border-radius: 12px;
    }

    .text-right { text-align: right !important; }

    /* Pagination Overlay */
    .pagination-wrapper { margin-top: 40px; display: flex; justify-content: center; }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(49, 130, 206, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(49, 130, 206, 0); }
        100% { box-shadow: 0 0 0 0 rgba(49, 130, 206, 0); }
    }
</style>
@endsection
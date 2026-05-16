@extends('layouts.app-immo')

@section('content')
<div class="admin-node-container">
    
    {{-- BARRE DE STATUS SUPÉRIEURE : NODE INFO --}}
    <div class="status-bar">
        <div class="node-identity">
            <div class="status-indicator">
                <span class="pulse-red"></span>
                <h1 class="node-title">Sentinel <span class="accent-red">Admin</span> Node</h1>
            </div>
            <p class="node-meta">
                ID_OPERATOR: <span class="meta-value">{{ Auth::user()->name }}</span> | 
                STATUS: <span class="meta-online">ACTIVE_LINK</span>
            </p>
        </div>
        <div class="access-level-badge">
            <span class="badge-label">NIVEAU D'ACCÈS :</span>
            <span class="badge-value">SUPER_ADMIN</span>
        </div>
    </div>

    {{-- GRILLE DE TÉLÉMÉTRIE (STATS) --}}
    <div class="telemetry-grid">
        <div class="tele-card blue-accent">
            <div class="tele-label">PUBLICATION ASSETS</div>
            <div class="tele-value-group">
                <span class="tele-number">{{ $stats['listings_count'] }}</span>
                <span class="tele-unit">UNITS</span>
            </div>
            <div class="tele-graph-mini"></div>
        </div>

        <div class="tele-card dark-accent">
            <div class="tele-label">NODES UTILISATEURS</div>
            <div class="tele-value-group">
                <span class="tele-number">{{ $stats['users_count'] }}</span>
                <span class="tele-unit">ACTIVE</span>
            </div>
        </div>

        <div class="tele-card orange-accent">
            <div class="tele-label">TRAFIC MESSAGES</div>
            <div class="tele-value-group">
                <span class="tele-number color-orange">{{ $stats['messages_count'] }}</span>
                <span class="tele-unit">FLUX</span>
            </div>
        </div>
    </div>

    {{-- CONSOLE D'ACTIONS RAPIDES --}}
    <div class="action-console">
        <div class="console-header">
            <span class="terminal-dot"></span>
            <h2 class="console-title">DIRECT_SYSTEM_ACTIONS</h2>
        </div>
        <div class="action-row">
            <a href="#" class="btn-terminal">📂 CONFIG_CATEGORIES</a>
            <a href="{{ route('admin.users.index') }}" class="btn-terminal">👥 MAP_USERS</a>
            <a href="{{ route('listings.create') }}" class="btn-master">⚡ NEW_MASTER_LISTING</a>
        </div>
    </div>

    {{-- LOGS DES PUBLICATIONS RÉCENTES --}}
    <div class="logs-container">
        <div class="logs-header">
            <h3 class="logs-title">Dernières Entrées Base de Données</h3>
            <div class="real-time-badge">REAL_TIME_STREAM</div>
        </div>
        
        <div class="table-responsive">
            <table class="logs-table">
                <thead>
                    <tr>
                        <th>ASSET_ID</th>
                        <th>OPERATOR</th>
                        <th>TIMESTAMP</th>
                        <th class="text-right">COMMAND</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentListings as $listing)
                        <tr class="log-row">
                            <td data-label="ASSET_ID">
                                <div class="asset-cell">
                                    <div class="asset-preview">
                                        @php $photos = json_decode($listing->images); @endphp
                                        @if($listing->cover_image)
                                            <img src="{{ asset('storage/' . $listing->cover_image) }}">
                                        @elseif($photos && count($photos) > 0)
                                            <img src="{{ asset('storage/' . $photos[0]) }}">
                                        @else
                                            <div style="width:100%; height:100%; background:#e2e8f0;"></div>
                                        @endif
                                    </div>
                                    <div class="asset-info">
                                        <div class="asset-name">{{ $listing->title }}</div>
                                        <div class="asset-tag">{{ $listing->category->name ?? 'SYS_DEF' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="OPERATOR">
                                <span class="operator-name">{{ $listing->user->name ?? 'ANONYMOUS' }}</span>
                            </td>
                            <td data-label="TIMESTAMP">
                                <span class="mono-date">[{{ $listing->created_at ? $listing->created_at->format('Y-m-d') : '--' }}]</span>
                            </td>
                            <td class="text-right" data-label="COMMAND">
                                <form action="{{ route('admin.listings.destroy', $listing) }}" method="POST" style="display: inline-block; width: 100%;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('ACTION IRREVERSIBLE : Confirmer la purge ?');" class="btn-purge">
                                        PURGE
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-log">NO_DATA_DETECTED_IN_BUFFER</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    :root {
        --sentinel-red: #e74c3c;
        --sentinel-blue: #3498db;
        --sentinel-dark: #2c3e50;
        --sentinel-bg: #f4f7f6;
        --font-mono: 'JetBrains Mono', 'Fira Code', monospace;
    }

    .admin-node-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
        animation: slideIn 0.5s ease-out;
    }

    /* Status Bar */
    .status-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding: 20px 35px;
        border-radius: 20px;
        border-left: 6px solid var(--sentinel-red);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-bottom: 40px;
    }

    .status-indicator { display: flex; align-items: center; gap: 15px; }
    .node-title { font-size: 1.4rem; font-weight: 950; margin: 0; text-transform: uppercase; letter-spacing: -1px; }
    .accent-red { color: var(--sentinel-red); }

    .node-meta { font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-top: 5px; font-family: var(--font-mono); }
    .meta-online { color: #27ae60; }

    .access-level-badge {
        background: #fdf2f2;
        border: 1px solid #fadbd8;
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 0.65rem;
        font-weight: 900;
        white-space: nowrap;
    }
    .badge-label { color: #94a3b8; margin-right: 5px; }
    .badge-value { color: var(--sentinel-red); }

    /* Telemetry Cards */
    .telemetry-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-bottom: 40px; }
    .tele-card {
        background: white;
        padding: 35px;
        border-radius: 24px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.03);
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .tele-card:hover { transform: translateY(-10px); }
    .blue-accent { border-bottom: 5px solid var(--sentinel-blue); }
    .dark-accent { border-bottom: 5px solid var(--sentinel-dark); }
    .orange-accent { border-bottom: 5px solid #f39c12; }

    .tele-label { color: #94a3b8; font-weight: 900; font-size: 0.7rem; letter-spacing: 2px; text-transform: uppercase; }
    .tele-value-group { display: flex; align-items: baseline; gap: 12px; margin-top: 15px; }
    .tele-number { font-size: 3.2rem; font-weight: 950; color: var(--sentinel-dark); line-height: 1; }
    .tele-unit { font-weight: 800; font-size: 0.8rem; color: #cbd5e1; }
    .color-orange { color: #f39c12; }

    /* Console Action */
    .action-console {
        background: var(--sentinel-dark);
        border-radius: 24px;
        padding: 35px;
        margin-bottom: 45px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.15);
    }
    .console-header { display: flex; align-items: center; gap: 15px; margin-bottom: 30px; }
    .terminal-dot { width: 12px; height: 12px; background: var(--sentinel-red); border-radius: 50%; box-shadow: 0 0 10px var(--sentinel-red); }
    .console-title { color: white; font-size: 0.75rem; font-weight: 900; letter-spacing: 2px; margin: 0; font-family: var(--font-mono); }

    .action-row { display: flex; flex-wrap: wrap; gap: 18px; }
    .btn-terminal {
        background: rgba(255,255,255,0.06);
        color: white;
        padding: 14px 28px;
        border-radius: 14px;
        text-decoration: none;
        font-weight: 800;
        font-size: 0.75rem;
        border: 1px solid rgba(255,255,255,0.1);
        transition: 0.3s;
        font-family: var(--font-mono);
        text-align: center;
    }
    .btn-terminal:hover { background: rgba(255,255,255,0.12); transform: translateY(-3px); }
    .btn-master {
        background: var(--sentinel-blue);
        color: white;
        padding: 14px 28px;
        border-radius: 14px;
        text-decoration: none;
        font-weight: 950;
        font-size: 0.75rem;
        box-shadow: 0 6px 0 #2980b9;
        transition: 0.2s;
        text-align: center;
    }
    .btn-master:hover { transform: translateY(2px); box-shadow: 0 2px 0 #2980b9; }

    /* Logs Table */
    .logs-container {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.04);
        border: 1px solid #eef2f3;
    }
    .logs-header { padding: 30px; border-bottom: 1px solid #f4f7f6; display: flex; justify-content: space-between; align-items: center; background: #fafbfc; }
    .logs-title { margin: 0; font-weight: 900; color: var(--sentinel-dark); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; }
    .real-time-badge { font-family: var(--font-mono); font-size: 0.6rem; font-weight: 900; background: #f1f5f9; color: #64748b; padding: 6px 14px; border-radius: 8px; }

    .logs-table { width: 100%; border-collapse: collapse; }
    .logs-table th { padding: 20px 30px; font-size: 0.6rem; font-weight: 900; color: #cbd5e1; text-transform: uppercase; letter-spacing: 2px; text-align: left; }
    
    .log-row { border-bottom: 1px solid #f8fafb; transition: 0.2s; }
    .log-row:hover { background: #fcfdfe; box-shadow: inset 4px 0 0 var(--sentinel-red); }
    .logs-table td { padding: 20px 30px; }

    .asset-cell { display: flex; align-items: center; gap: 18px; }
    .asset-preview { width: 50px; height: 50px; background: #f1f5f9; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; flex-shrink: 0; }
    .asset-preview img { width: 100%; height: 100%; object-fit: cover; }
    
    .asset-name { font-weight: 900; color: var(--sentinel-dark); font-size: 0.9rem; margin-bottom: 2px; }
    .asset-tag { font-size: 0.6rem; color: var(--sentinel-blue); font-weight: 900; text-transform: uppercase; font-family: var(--font-mono); }

    .operator-name { font-size: 0.8rem; font-weight: 800; color: #64748b; }
    .mono-date { font-family: var(--font-mono); font-size: 0.8rem; color: #94a3b8; }

    .btn-purge {
        background: #fff5f5; color: var(--sentinel-red); border: 1px solid #feb2b2;
        padding: 8px 18px; border-radius: 10px; font-weight: 900; font-size: 0.65rem;
        text-transform: uppercase; cursor: pointer; transition: 0.3s;
        width: 100%; max-width: 120px; text-align: center;
    }
    .btn-purge:hover { background: var(--sentinel-red); color: white; border-color: var(--sentinel-red); transform: scale(1.05); }

    .empty-log { padding: 80px; text-align: center; color: #cbd5e1; font-family: var(--font-mono); font-weight: 800; font-size: 0.8rem; letter-spacing: 3px; }
    .text-right { text-align: right !important; }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .pulse-red {
        width: 10px; height: 10px; background: var(--sentinel-red); border-radius: 50%;
        box-shrink: 0;
        box-shadow: 0 0 0 rgba(231, 76, 60, 0.4);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(231, 76, 60, 0); }
        100% { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0); }
    }

    /* ==========================================
       MEDIA QUERIES : ADAPTATION TERMINAL MOBILE
       ========================================== */
    @media (max-width: 768px) {
        .admin-node-container { margin: 15px auto; padding: 0 10px; }
        
        /* 1. Status Bar en Colonne */
        .status-bar { flex-direction: column; align-items: flex-start; gap: 15px; padding: 20px; }
        .access-level-badge { width: 100%; text-align: center; box-sizing: border-box; }

        /* 2. Alignement Console d'actions */
        .action-row { flex-direction: column; gap: 12px; }
        .btn-terminal, .btn-master { width: 100%; box-sizing: border-box; padding: 12px; }

        /* 3. Déstructuration responsive de la table de Logs */
        .logs-table thead { display: none; } /* On cache les headers */
        .log-row { display: block; padding: 15px; border-bottom: 2px solid #edf2f7; }
        .logs-table td { display: flex; justify-content: space-between; align-items: center; padding: 10px 0 !important; border: none; text-align: right !important; }
        
        /* Injection de labels via data-label */
        .logs-table td::before { content: attr(data-label); font-family: var(--font-mono); font-size: 0.65rem; font-weight: 900; color: #a0aec0; text-align: left; }
        
        /* Ajustements d'affichage internes */
        .asset-cell { width: 100%; justify-content: flex-end; text-align: right; }
        .text-right { text-align: right !important; }
        .btn-purge { width: auto; min-width: 100px; }
    }
</style>
@endsection
@extends('layouts.app-immo')

@section('content')
<div class="dashboard-container" style="max-width: 1200px; margin: 0 auto; padding: 20px; animation: fadeInPage 0.8s ease-out;">
    
    {{-- HEADER STRATÉGIQUE --}}
    <div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; background: white; padding: 30px; border-radius: 12px; border: 1px solid #e0e0e0; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
        <div>
            <h1 class="dashboard-title" style="font-weight: 900; color: #2c3e50; margin: 0; text-transform: uppercase; letter-spacing: -1px;">
                Console <span style="color: #16a085;">Propriétaire</span>
            </h1>
            <p style="color: #7f8c8d; font-weight: 700; margin-top: 5px; text-transform: uppercase; font-size: 0.8em; letter-spacing: 1px;">
                <span style="color: #16a085;">●</span> Session active : {{ auth()->user()->name }}
            </p>
        </div>
        <a href="{{ route('listings.create') }}" 
           class="btn-new-annonce"
           style="background-color: #16a085; color: white; padding: 18px 35px; border-radius: 6px; font-weight: 800; text-decoration: none; box-shadow: 0 5px 0 #0e6655; transition: all 0.2s; font-size: 0.85em; text-transform: uppercase; letter-spacing: 1px; text-align: center;">
            + Enregistrer un nouveau bien
        </a>
    </div>

    {{-- GRILLE DE KPIS (INDICATEURS DE PERFORMANCE) --}}
    <div class="kpi-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 25px; margin-bottom: 40px;">
        <div class="kpi-card" style="background: #2c3e50; padding: 25px; border-radius: 12px; color: white; transition: 0.3s;">
            <p style="text-transform: uppercase; font-size: 0.7em; font-weight: 800; color: #16a085; margin-bottom: 10px;">Portefeuille Actif</p>
            <div style="display: flex; align-items: baseline; gap: 10px;">
                <span style="font-size: 3.2em; font-weight: 900;">{{ $myListings->count() }}</span>
                <span style="font-size: 0.9em; font-weight: 600; color: #bdc3c7;">Unités</span>
            </div>
        </div>

        <div class="kpi-card" style="background: white; padding: 25px; border-radius: 12px; border: 1px solid #e0e0e0; transition: 0.3s;">
            <p style="text-transform: uppercase; font-size: 0.7em; font-weight: 800; color: #95a5a6; margin-bottom: 10px;">Portée Audience</p>
            <div style="display: flex; align-items: baseline; gap: 10px;">
                <span style="font-size: 3.2em; font-weight: 900; color: #2c3e50; font-style: italic; opacity: 0.2;">---</span>
                <span style="font-size: 0.7em; font-weight: 800; color: white; background: #2c3e50; padding: 2px 8px; border-radius: 4px;">ANALYTICS BIENTÔT</span>
            </div>
        </div>

        {{-- KPI MESSAGERIE --}}
        <a href="{{ route('admin.messages') }}" style="text-decoration: none; display: block;">
            <div class="kpi-card" style="background: white; padding: 25px; border-radius: 12px; border: 1px solid #e0e0e0; transition: 0.3s; border-right: 8px solid #f39c12; cursor: pointer;">
                <p style="text-transform: uppercase; font-size: 0.7em; font-weight: 800; color: #95a5a6; margin-bottom: 10px;">Flux de Messages</p>
                <div style="display: flex; align-items: baseline; gap: 10px;">
                    <span style="font-size: 3.2em; font-weight: 900; color: #f39c12;">{{ $unreadMessagesCount ?? 0 }}</span>
                    <span style="font-size: 0.9em; font-weight: 600; color: #95a5a6;">Demandes</span>
                </div>
            </div>
        </a>
    </div>

    {{-- LISTING TECHNIQUE --}}
    <div style="background: white; border-radius: 12px; border: 1px solid #ddd; box-shadow: 0 15px 40px rgba(0,0,0,0.08); overflow: hidden;">
        <div style="padding: 25px 35px; background: #2c3e50; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 0.9em; font-weight: 800; color: white; text-transform: uppercase; margin: 0; letter-spacing: 2px;">Inventaire des publications</h2>
            <div style="width: 50px; height: 3px; background: #16a085;"></div>
        </div>

        @if($myListings->isEmpty())
            <div style="padding: 60px 20px; text-align: center; background: #fdfdfd;">
                <p style="color: #bdc3c7; font-size: 1.2em; font-weight: 800; text-transform: uppercase;">Base de données vide</p>
                <a href="{{ route('listings.create') }}" style="color: #16a085; font-weight: 900; text-decoration: none; border-bottom: 2px solid #16a085; padding-bottom: 5px;">INITIALISER MA PREMIÈRE ANNONCE</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="responsive-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f9fa; border-bottom: 2px solid #eee;">
                            <th style="padding: 20px 35px; color: #95a5a6; font-size: 0.7em; text-transform: uppercase; letter-spacing: 1px; text-align: left;">Désignation & Localisation</th>
                            <th style="padding: 20px 35px; color: #95a5a6; font-size: 0.7em; text-transform: uppercase; letter-spacing: 1px; text-align: center;">Valorisation</th>
                            <th style="padding: 20px 35px; color: #95a5a6; font-size: 0.7em; text-transform: uppercase; letter-spacing: 1px; text-align: center;">État Système</th>
                            <th style="padding: 20px 35px; color: #95a5a6; font-size: 0.7em; text-transform: uppercase; letter-spacing: 1px; text-align: right;">Opérations</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($myListings as $listing)
                            <tr class="table-row" style="border-bottom: 1px solid #eee; transition: 0.2s;">
                                <td style="padding: 25px 35px;" data-label="Bien">
                                    <div class="table-cell-flex" style="display: flex; align-items: center; gap: 20px;">
                                        <div style="position: relative; flex-shrink: 0;">
                                            <img src="{{ $listing->cover_image ? asset('storage/' . $listing->cover_image) : 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=100&q=80' }}" 
                                                 style="height: 70px; width: 70px; border-radius: 8px; object-fit: cover; border: 2px solid #eee;">
                                            <span style="position: absolute; top: -5px; left: -5px; background: #2c3e50; color: white; font-size: 0.6em; padding: 2px 5px; border-radius: 3px; font-weight: 900;">ID: {{ $listing->id }}</span>
                                        </div>
                                        <div>
                                            <p class="item-title" style="font-weight: 900; color: #2c3e50; margin: 0; font-size: 1.1em; text-transform: uppercase;">{{ $listing->title }}</p>
                                            <p style="font-size: 0.8em; font-weight: 700; color: #16a085; margin-top: 4px;">📍 {{ $listing->location }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 25px 35px; text-align: center;" data-label="Valorisation">
                                    <span style="font-size: 1.2em; font-weight: 900; color: #2c3e50;">{{ number_format($listing->price, 0, ',', ' ') }}</span>
                                    <span style="font-size: 0.7em; font-weight: 800; color: #95a5a6;">XAF</span>
                                </td>
                                <td style="padding: 25px 35px; text-align: center;" data-label="État">
                                    <span style="padding: 6px 15px; background: #e8f6f3; color: #16a085; border-radius: 4px; font-size: 0.7em; font-weight: 900; text-transform: uppercase; border: 1px solid #d1f2eb; display: inline-block; animation: pulse 2s infinite;">
                                        En Ligne
                                    </span>
                                </td>
                                <td style="padding: 25px 35px; text-align: right;" data-label="Opérations">
                                    <div class="actions-container" style="display: flex; justify-content: flex-end; gap: 10px;">
                                        <a href="{{ route('listings.show', $listing->id) }}" class="action-btn" style="padding: 8px 15px; background: #f8f9fa; color: #2c3e50; border-radius: 4px; text-decoration: none; font-weight: 800; font-size: 0.7em; border: 1px solid #ddd; text-transform: uppercase;">Consulter</a>
                                        <a href="#" class="action-btn" style="padding: 8px 15px; background: #f8f9fa; color: #f39c12; border-radius: 4px; text-decoration: none; font-weight: 800; font-size: 0.7em; border: 1px solid #f39c12; text-transform: uppercase;">Éditer</a>
                                        
                                        <form action="{{ route('admin.listings.destroy', $listing->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('ALERTE : Confirmation de suppression définitive ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-btn" style="background: #e74c3c; color: white; border: none; padding: 8px 15px; border-radius: 4px; font-weight: 800; font-size: 0.7em; cursor: pointer; text-transform: uppercase; box-shadow: 0 3px 0 #c0392b;">
                                                Destituer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- DÉCONNEXION STYLISÉE --}}
    <div style="margin-top: 60px; text-align: right; padding-bottom: 50px;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background: none; border: none; color: #e74c3c; font-weight: 900; font-size: 0.8em; text-transform: uppercase; cursor: pointer; letter-spacing: 2px; border-bottom: 2px solid transparent; transition: 0.3s;"
                    onmouseover="this.style.borderBottomColor='#e74c3c'" onmouseout="this.style.borderBottomColor='transparent'">
                Terminer la session administrateur ➔
            </button>
        </form>
    </div>
</div>

<style>
    @keyframes fadeInPage {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }
    .table-row:hover {
        background-color: #fcfcfc !important;
        transform: scale(1.002);
        box-shadow: inset 4px 0 0 #16a085;
    }
    .btn-new-annonce:hover {
        background-color: #1abc9c !important;
        transform: translateY(-2px);
        box-shadow: 0 7px 0 #0e6655 !important;
    }
    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    .action-btn:hover {
        background-color: #2c3e50 !important;
        color: white !important;
        border-color: #2c3e50 !important;
    }

    /* ===================================================
       MEDIA QUERIES : CORRECTIONS POUR LE MOBILE (<= 768px)
       =================================================== */
    @media (max-width: 768px) {
        .dashboard-container { padding: 10px !important; }
        
        /* 1. Entête en colonne */
        .dashboard-header { flex-direction: column !important; align-items: stretch !important; gap: 20px !important; padding: 20px !important; }
        .dashboard-title { font-size: 1.8em !important; text-align: center; }
        .dashboard-header p { text-align: center; }
        .btn-new-annonce { width: 100%; padding: 14px 20px !important; box-sizing: border-box; }

        /* 2. KPIs empilés */
        .kpi-grid { grid-template-columns: 1fr !important; gap: 15px !important; }
        .kpi-card { padding: 20px !important; }

        /* 3. Transformation du tableau rigide en blocs fluides */
        .responsive-table thead { display: none; } /* Cache les entêtes de colonne */
        .responsive-table tr { display: block; margin-bottom: 20px; background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 15px; }
        .responsive-table td { display: flex; justify-content: space-between; align-items: center; padding: 10px 0 !important; border-bottom: 1px dashed #efefef; text-align: right !important; }
        .responsive-table td:last-child { border-bottom: none; }
        
        /* Injecte le titre des colonnes via l'attribut data-label */
        .responsive-table td::before { content: attr(data-label); font-weight: 800; color: #95a5a6; text-transform: uppercase; font-size: 0.75em; text-align: left; }
        
        /* Ajustements internes */
        .table-cell-flex { width: 100%; justify-content: flex-end; }
        .item-title { font-size: 0.95em !important; }
        .actions-container { width: 100%; justify-content: center !important; margin-top: 5px; flex-wrap: wrap; }
        .actions-container > * { flex: 1; text-align: center; font-size: 0.65em !important; padding: 10px 5px !important; }
        .delete-btn { box-shadow: none !important; }
    }
</style>
@endsection
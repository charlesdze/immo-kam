@extends('layouts.app-immo')

@section('content')
<div class="terminal-container">
    
    {{-- HEADER : NAVIGATION SYSTÈME --}}
    <div class="terminal-header">
        <div class="header-main">
            <h1>Immo-Kam <span>Terminal</span></h1>
            <p class="header-subtitle">Exploration de la base de données immobilière au Cameroun</p>
        </div>
        <a href="{{ route('listings.create') }}" class="btn-inject">
            <span class="plus">+</span> Injecter une annonce
        </a>
    </div>

    {{-- SECTION FILTRES : INTERFACE DE COMMANDE --}}
    <div class="filter-console">
        <form action="{{ route('listings.index') }}" method="GET" class="filter-form">
            <div class="input-group">
                <label for="search">Requête textuelle</label>
                <input type="text" id="search" name="search" placeholder="Ville, quartier..." value="{{ request('search') }}">
            </div>

            <div class="input-group">
                <label for="category_id">Secteur / Catégorie</label>
                <div class="select-wrapper">
                    <select id="category_id" name="category_id">
                        <option value="">Tout l'inventaire</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="input-group">
                <label for="max_price">Budget Max (XAF)</label>
                <input type="number" id="max_price" name="max_price" placeholder="Illimité" value="{{ request('max_price') }}">
            </div>

            <div class="action-group">
                <button type="submit" class="btn-execute">Exécuter</button>
                <a href="{{ route('listings.index') }}" class="btn-reset" title="Réinitialiser les filtres">↺</a>
            </div>
        </form>
    </div>

    {{-- LISTE DES RÉSULTATS : TABLEAU DATA-DRIVEN RESPONSIVE --}}
    @if($listings->isEmpty())
        <div class="empty-terminal">
            <div class="empty-radar" aria-hidden="true">📡</div>
            <h3>Aucune donnée interceptée</h3>
            <p>Modifiez vos paramètres de filtrage pour explorer d'autres segments.</p>
        </div>
    @else
        <div class="data-terminal-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col">Asset</th>
                        <th scope="col">Spécifications</th>
                        <th scope="col">Prix (XAF)</th>
                        <th scope="col">Mode</th>
                        <th scope="col" class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($listings as $listing)
                        <tr class="data-row">
                            <td class="td-asset">
                                <div class="asset-frame">
                                    @if($listing->cover_image)
                                        <img src="{{ asset('storage/' . $listing->cover_image) }}" alt="Couverture de l'annonce {{ $listing->title }}">
                                    @else
                                        <div class="no-data-tag">NO_DATA</div>
                                    @endif
                                </div>
                            </td>
                            <td class="td-specs" data-label="Spécifications">
                                <span class="spec-category">{{ $listing->category->name ?? 'IMMOBILIER' }}</span>
                                <div class="spec-title">{{ $listing->title }}</div>
                                <div class="spec-location">📍 {{ $listing->location }}</div>
                            </td>
                            <td class="td-price" data-label="Prix (XAF)">
                                <div class="price-val">
                                    {{ number_format($listing->price, 0, ',', ' ') }}
                                </div>
                            </td>
                            <td class="td-mode" data-label="Mode">
                                {{-- Utilisation de Str::lower pour garantir la correspondance exacte avec les classes CSS --}}
                                <span class="mode-badge {{ \Illuminate::support\Str::lower($listing->type) }}">
                                    {{ $listing->type }}
                                </span>
                            </td>
                            <td class="td-action">
                                <a href="{{ route('listings.show', $listing->id) }}" class="btn-consult">
                                    Consulter
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $listings->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<style>
    :root {
        --primary: #16a085;
        --dark: #2c3e50;
        --border-soft: #f0f3f5;
        --bg-terminal: #ffffff;
        --text-mono: 'Roboto Mono', 'Courier New', monospace;
        --shadow-subtle: 0 10px 30px rgba(0,0,0,0.04);
        --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .terminal-container {
        max-width: 1350px;
        margin: 40px auto;
        padding: 0 25px;
        font-family: 'Inter', system-ui, sans-serif;
        animation: fadeInTerminal 0.6s ease-out;
        box-sizing: border-box;
    }

    /* Header */
    .terminal-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
        margin-bottom: 40px;
        border-bottom: 4px solid var(--border-soft);
        padding-bottom: 25px;
    }

    .header-main h1 {
        font-size: 2.8rem;
        font-weight: 950;
        color: var(--dark);
        margin: 0;
        letter-spacing: -2.5px;
        text-transform: uppercase;
    }

    .header-main h1 span { color: var(--primary); }
    .header-subtitle {
        color: #95a5a6;
        font-weight: 800;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-top: 5px;
    }

    .btn-inject {
        display: inline-block;
        white-space: nowrap;
        background: var(--dark);
        color: white;
        padding: 16px 32px;
        border-radius: 12px;
        font-weight: 900;
        text-decoration: none;
        font-size: 0.8rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        box-shadow: 0 4px 0 var(--primary);
        transition: var(--transition);
    }

    .btn-inject:hover { transform: translateY(-3px); box-shadow: 0 6px 0 var(--primary); }

    /* Filter Console */
    .filter-console {
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: var(--shadow-subtle);
        border: 1px solid var(--border-soft);
        margin-bottom: 40px;
    }

    .filter-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 25px;
        align-items: flex-end;
    }

    .input-group label {
        display: block;
        font-size: 0.65rem;
        font-weight: 900;
        color: var(--primary);
        text-transform: uppercase;
        margin-bottom: 10px;
        letter-spacing: 1.2px;
    }

    .input-group input, .input-group select {
        width: 100%;
        background: #f8fafb;
        border: 2px solid #edf2f7;
        padding: 14px;
        border-radius: 10px;
        font-weight: 700;
        transition: var(--transition);
        color: var(--dark);
        box-sizing: border-box;
    }

    .input-group input:focus, .input-group select:focus { border-color: var(--primary); outline: none; background: white; }

    .action-group { display: flex; gap: 12px; width: 100%; }
    .btn-execute {
        flex: 3;
        background: var(--primary);
        color: white;
        border: none;
        padding: 15px;
        border-radius: 10px;
        font-weight: 900;
        cursor: pointer;
        text-transform: uppercase;
        transition: var(--transition);
    }
    .btn-execute:hover { background: var(--dark); }

    .btn-reset {
        flex: 1;
        background: #f1f5f9;
        color: #64748b;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        text-decoration: none;
        font-weight: 900;
        transition: var(--transition);
    }
    .btn-reset:hover { background: #e2e8f0; color: var(--dark); }

    /* Data Table & Containers */
    .data-terminal-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: var(--shadow-subtle);
        border: 1px solid var(--border-soft);
        margin-bottom: 30px;
    }

    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead tr { background: var(--dark); color: rgba(255,255,255,0.6); }
    .data-table th { padding: 20px 35px; font-size: 0.65rem; font-weight: 900; text-transform: uppercase; text-align: left; letter-spacing: 1.5px; }
    .data-table th.text-right { text-align: right; }

    .data-row { border-bottom: 1px solid var(--border-soft); transition: var(--transition); }
    .data-row:hover { background: #f8fafc; box-shadow: inset 5px 0 0 var(--primary); }

    .td-asset { padding: 25px 35px; width: 140px; }
    .asset-frame {
        width: 130px;
        height: 85px;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid var(--border-soft);
        background: #f1f5f9;
    }
    .asset-frame img { width: 100%; height: 100%; object-fit: cover; transition: var(--transition); }
    .data-row:hover .asset-frame img { transform: scale(1.05); }

    .no-data-tag { height: 100%; display: flex; align-items: center; justify-content: center; font-size: 0.6rem; font-weight: 950; color: #cbd5e1; letter-spacing: 1px; }

    .td-specs { padding: 25px 35px; }
    .spec-category { font-size: 0.6rem; color: var(--primary); font-weight: 900; text-transform: uppercase; margin-bottom: 6px; display: block; letter-spacing: 0.5px; }
    .spec-title { font-weight: 900; font-size: 1.15rem; color: var(--dark); letter-spacing: -0.5px; line-height: 1.3; }
    .spec-location { color: #94a3b8; font-size: 0.85rem; font-weight: 600; margin-top: 6px; }

    .td-price { padding: 25px 35px; }
    .price-val { font-family: var(--text-mono); font-size: 1.35rem; font-weight: 900; color: var(--dark); white-space: nowrap; }

    .td-mode { padding: 25px 35px; }
    .mode-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 0.65rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid transparent;
        white-space: nowrap;
    }
    .mode-badge.vente { background: #fff7ed; color: #c2410c; border-color: #ffedd5; }
    .mode-badge.location { background: #f0fdf4; color: #15803d; border-color: #dcfce7; }

    .td-action { padding: 25px 35px; text-align: right; }
    .btn-consult {
        display: inline-block;
        background: white;
        color: var(--dark);
        padding: 12px 28px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 900;
        font-size: 0.7rem;
        text-transform: uppercase;
        border: 2px solid #edf2f7;
        transition: var(--transition);
        white-space: nowrap;
    }
    .btn-consult:hover { background: var(--dark); color: white; border-color: var(--dark); }

    /* Empty State */
    .empty-terminal { background: white; border-radius: 24px; padding: 80px 40px; text-align: center; border: 2px dashed #e2e8f0; }
    .empty-radar { font-size: 3.5rem; margin-bottom: 20px; animation: pulseRadar 2s infinite; }
    .empty-terminal h3 { font-weight: 900; color: var(--dark); margin: 0 0 10px 0; font-size: 1.4rem; }
    .empty-terminal p { color: #94a3b8; font-weight: 600; margin: 0; font-size: 0.9rem; }

    .pagination-wrapper { margin-top: 30px; }

    /* Animations */
    @keyframes fadeInTerminal {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulseRadar {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(1.08); }
    }

    /* Passerelle CSS : Transformation responsive du tableau */
    @media (max-width: 992px) {
        .terminal-header { flex-direction: column; align-items: flex-start; gap: 20px; }
        .btn-inject { width: 100%; text-align: center; box-sizing: border-box; }

        /* Conversion du conteneur de tableau en grille de cartes */
        .data-table, .data-table thead, .data-table tbody, .data-table th, .data-table td, .data-table tr { 
            display: block; 
        }
        
        .data-table thead { display: none; } /* Masquage propre des en-têtes de colonnes */
        
        .data-row {
            padding: 20px;
            position: relative;
            border-bottom: 2px solid var(--border-soft);
        }
        .data-row:hover { box-shadow: inset 0 5px 0 var(--primary); }

        .td-asset { width: 100%; padding: 0 0 15px 0; }
        .asset-frame { width: 100%; height: 200px; }

        .td-specs { padding: 0 0 15px 0; }
        
        /* Injection sémantique des libellés de données via attribut HTML data-label */
        .td-price, .td-mode { 
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed var(--border-soft);
        }
        
        .td-price::before, .td-mode::before {
            content: attr(data-label);
            font-size: 0.7rem;
            font-weight: 900;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }

        .price-val { font-size: 1.2rem; }
        .td-action { padding: 15px 0 0 0; text-align: left; }
        .btn-consult { width: 100%; text-align: center; box-sizing: border-box; padding: 14px; }
    }
</style>
@endsection
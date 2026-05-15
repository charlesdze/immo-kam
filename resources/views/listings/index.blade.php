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
                <label>Requête textuelle</label>
                <input type="text" name="search" placeholder="Ville, quartier..." value="{{ request('search') }}">
            </div>

            <div class="input-group">
                <label>Secteur / Catégorie</label>
                <div class="select-wrapper">
                    <select name="category_id">
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
                <label>Budget Max (XAF)</label>
                <input type="number" name="max_price" placeholder="Illimité" value="{{ request('max_price') }}">
            </div>

            <div class="action-group">
                <button type="submit" class="btn-execute">Exécuter</button>
                <a href="{{ route('listings.index') }}" class="btn-reset">↺</a>
            </div>
        </form>
    </div>

    {{-- LISTE DES RÉSULTATS : TABLEAU DATA-DRIVEN --}}
    @if($listings->isEmpty())
        <div class="empty-terminal">
            <div class="empty-radar">📡</div>
            <h3>Aucune donnée interceptée</h3>
            <p>Modifiez vos paramètres de filtrage pour explorer d'autres segments.</p>
        </div>
    @else
        <div class="data-terminal-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Asset</th>
                        <th>Spécifications</th>
                        <th>Prix (XAF)</th>
                        <th>Mode</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($listings as $listing)
                        <tr class="data-row">
                            <td class="td-asset">
                                <div class="asset-frame">
                                    @if($listing->cover_image)
                                        <img src="{{ asset('storage/' . $listing->cover_image) }}">
                                    @else
                                        <div class="no-data-tag">NO_DATA</div>
                                    @endif
                                </div>
                            </td>
                            <td class="td-specs">
                                <span class="spec-category">{{ $listing->category->name ?? 'IMMOBILIER' }}</span>
                                <div class="spec-title">{{ $listing->title }}</div>
                                <div class="spec-location">📍 {{ $listing->location }}</div>
                            </td>
                            <td class="td-price">
                                <div class="price-val">
                                    {{ number_format($listing->price, 0, ',', ' ') }}
                                </div>
                            </td>
                            <td class="td-mode">
                                <span class="mode-badge {{ $listing->type }}">
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
    }

    /* Header */
    .terminal-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
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
    }

    .input-group input:focus { border-color: var(--primary); outline: none; background: white; }

    .action-group { display: flex; gap: 12px; }
    .btn-execute {
        flex: 2;
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
    .btn-execute:hover { background: var(--dark); transform: scale(1.02); }

    .btn-reset {
        flex: 0.5;
        background: #f1f5f9;
        color: #64748b;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        text-decoration: none;
        font-weight: 900;
        transition: var(--transition);
    }

    /* Data Table */
    .data-terminal-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: var(--shadow-subtle);
        border: 1px solid var(--border-soft);
    }

    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead tr { background: var(--dark); color: rgba(255,255,255,0.5); }
    .data-table th { padding: 20px 35px; font-size: 0.65rem; font-weight: 900; text-transform: uppercase; text-align: left; letter-spacing: 1.5px; }

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
    .asset-frame img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .data-row:hover .asset-frame img { transform: scale(1.1); }

    .no-data-tag { height: 100%; display: flex; align-items: center; justify-content: center; font-size: 0.6rem; font-weight: 950; color: #cbd5e1; }

    .td-specs { padding: 25px 35px; }
    .spec-category { font-size: 0.6rem; color: var(--primary); font-weight: 900; text-transform: uppercase; margin-bottom: 6px; display: block; }
    .spec-title { font-weight: 900; font-size: 1.15rem; color: var(--dark); letter-spacing: -0.5px; }
    .spec-location { color: #94a3b8; font-size: 0.85rem; font-weight: 600; margin-top: 5px; }

    .td-price { padding: 25px 35px; }
    .price-val { font-family: var(--text-mono); font-size: 1.4rem; font-weight: 900; color: var(--dark); }

    .mode-badge {
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 0.65rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid transparent;
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
    }
    .btn-consult:hover { background: var(--dark); color: white; border-color: var(--dark); transform: translateX(5px); }

    /* Empty State */
    .empty-terminal { background: white; border-radius: 24px; padding: 120px; text-align: center; border: 2px dashed #e2e8f0; }
    .empty-radar { font-size: 4rem; margin-bottom: 25px; animation: pulseRadar 2s infinite; }

    @keyframes fadeInTerminal {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulseRadar {
        0% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.1); }
        100% { opacity: 1; transform: scale(1); }
    }
</style>
@endsection
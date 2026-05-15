{{-- CORRECTION : Utilisation du layout existant --}}
@extends('layouts.app-immo')

@section('title', $listing->title . ' - Dossier Technique')

@section('content')
<div class="technical-dossier-wrapper">
    
    {{-- Fil d'ariane --}}
    <nav class="breadcrumb-nav">
        <a href="{{ route('listings.index') }}" class="breadcrumb-link">
            <span class="icon">📦</span> Inventaire Global
        </a>
        <span class="separator">/</span>
        <span class="current-ref">Référence #{{ $listing->id }}</span>
    </nav>

    <div class="main-technical-card">
        <div class="grid-container">
            
            {{-- BLOC VISUEL (GAUCHE) --}}
            <div class="visual-engine-section">
                <div class="main-display-container">
                    <img src="{{ $listing->cover_image ? asset('storage/' . $listing->cover_image) : 'https://via.placeholder.com/800x600' }}" 
                         id="main-display" alt="Cover">
                    
                    <div class="type-badge-overlay">
                        {{ strtoupper($listing->type) }}
                    </div>
                </div>

                {{-- Galerie Matrix --}}
                @php $photos = json_decode($listing->images); @endphp
                <div class="thumbnail-matrix">
                    {{-- Image principale toujours en premier --}}
                    <div class="thumb-node active" onclick="changeImage('{{ $listing->cover_image ? asset('storage/' . $listing->cover_image) : 'https://via.placeholder.com/800x600' }}', this)">
                        <img src="{{ $listing->cover_image ? asset('storage/' . $listing->cover_image) : 'https://via.placeholder.com/100' }}">
                    </div>
                    
                    @if($photos && is_array($photos))
                        @foreach($photos as $photo)
                            <div class="thumb-node" onclick="changeImage('{{ asset('storage/' . $photo) }}', this)">
                                <img src="{{ asset('storage/' . $photo) }}">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- BLOC DATA (DROITE) --}}
            <div class="data-engine-section">
                <div class="header-data">
                    <span class="category-pill">{{ $listing->category->name ?? 'IMMOBILIER' }}</span>
                    <h1 class="main-title">{{ $listing->title }}</h1>
                    <p class="location-tag">
                        <span class="geo-icon">📍</span> {{ $listing->location }}
                    </p>
                </div>

                {{-- Dashboard de Prix --}}
                <div class="price-dashboard">
                    <div class="price-info">
                        <p class="price-label">Valorisation Estimée</p>
                        <p class="price-amount">
                            {{ number_format($listing->price, 0, ',', ' ') }} <small>XAF</small>
                        </p>
                    </div>
                    <div class="status-indicator">
                        <span class="status-dot"></span>
                        <span class="status-text">Système Actif</span>
                    </div>
                </div>

                {{-- Spécifications techniques --}}
                <div class="specs-section">
                    <h3 class="section-title">Description Technique</h3>
                    <div class="description-box">
                        <p>{{ $listing->description }}</p>
                    </div>
                </div>

                {{-- Console de Contact --}}
                <div class="contact-console">
                    <div class="operator-profile">
                        <div class="operator-avatar">
                            {{ strtoupper(substr($listing->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="operator-meta">
                            <p class="meta-label">ID Opérateur</p>
                            <p class="meta-value">{{ $listing->user->name ?? 'Anonyme' }}</p>
                        </div>
                    </div>

                    @auth
                        @if(auth()->id() !== $listing->user_id)
                            <form action="{{ route('messages.store') }}" method="POST" class="contact-form">
                                @csrf
                                <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                                <input type="hidden" name="receiver_id" value="{{ $listing->user_id }}">
                                <textarea name="content" rows="4" class="modern-textarea" placeholder="Initialiser une communication officielle..." required></textarea>
                                <button type="submit" class="premium-submit-btn">
                                    <span>🚀</span> Envoyer la requête
                                </button>
                            </form>
                        @else
                            <div class="admin-notice">
                                🛡️ Administration : Votre Publication
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="auth-required-btn">
                            🔒 Authentification requise pour contacter
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* ... Tes styles CSS restent identiques, ils sont très bien ... */
    :root {
        --primary: #16a085;
        --primary-glow: rgba(22, 160, 133, 0.3);
        --dark-panel: #1a252f;
        --text-main: #2c3e50;
        --text-muted: #95a5a6;
        --bg-glass: rgba(255, 255, 255, 0.9);
        --radius-lg: 24px;
        --radius-md: 12px;
        --shadow-fx: 0 20px 40px -15px rgba(0,0,0,0.1);
        --transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .technical-dossier-wrapper {
        max-width: 1250px;
        margin: 40px auto;
        padding: 0 25px;
        animation: fadeInUp 0.8s var(--transition);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* Styles Breadcrumb */
    .breadcrumb-nav { margin-bottom: 30px; display: flex; align-items: center; gap: 12px; }
    .breadcrumb-link { color: var(--text-muted); font-weight: 700; text-decoration: none; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; }
    .breadcrumb-link:hover { color: var(--primary); }
    .current-ref { color: var(--text-main); font-weight: 800; font-size: 0.75rem; text-transform: uppercase; }

    /* Layout Grille */
    .main-technical-card { background: white; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-fx); border: 1px solid rgba(0,0,0,0.05); }
    .grid-container { display: flex; flex-wrap: wrap; }

    /* Visual Section */
    .visual-engine-section { flex: 1.2; min-width: 500px; background: var(--dark-panel); padding: 40px; display: flex; flex-direction: column; gap: 25px; }
    .main-display-container { position: relative; border-radius: var(--radius-md); overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.08); }
    #main-display { width: 100%; height: 550px; object-fit: cover; transition: var(--transition); }
    .type-badge-overlay { position: absolute; bottom: 25px; left: 25px; background: var(--primary); color: white; padding: 10px 22px; border-radius: 8px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; backdrop-filter: blur(8px); }

    .thumbnail-matrix { display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px; }
    .thumb-node { aspect-ratio: 1/1; border-radius: 10px; overflow: hidden; border: 2px solid transparent; cursor: pointer; opacity: 0.4; transition: var(--transition); }
    .thumb-node img { width: 100%; height: 100%; object-fit: cover; }
    .thumb-node.active { opacity: 1; border-color: var(--primary); box-shadow: 0 0 15px var(--primary-glow); }

    /* Data Section */
    .data-engine-section { flex: 1; min-width: 450px; padding: 60px; background: #fff; }
    .category-pill { background: #e8f6f3; color: var(--primary); padding: 6px 18px; border-radius: 50px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; }
    .main-title { color: var(--text-main); font-size: 3.2rem; font-weight: 900; margin: 20px 0; letter-spacing: -2px; line-height: 0.9; }

    /* Price Box */
    .price-dashboard { background: var(--text-main); border-radius: 20px; padding: 35px; margin: 45px 0; display: flex; justify-content: space-between; align-items: center; position: relative; overflow: hidden; }
    .price-amount { font-size: 2.5rem; font-weight: 900; color: white; margin: 0; }
    .status-dot { display: inline-block; width: 10px; height: 10px; background: #2ecc71; border-radius: 50%; margin-right: 8px; animation: blink 2s infinite; }
    .status-text { color: white; font-weight: 800; font-size: 0.7rem; text-transform: uppercase; }

    /* Description */
    .description-box { background: #fbfcfc; border-left: 4px solid var(--primary); padding: 30px; border-radius: 12px; color: #4a5568; line-height: 1.8; }

    /* Contact Console */
    .contact-console { margin-top: 50px; background: #f8fafc; border-radius: 20px; padding: 35px; border: 1px solid #edf2f7; }
    .operator-avatar { width: 65px; height: 65px; background: var(--text-main); color: var(--primary); border-radius: 15px; display: flex; align-items: center; justify-content: center; font-weight: 900; border: 2px solid var(--primary); }
    .modern-textarea { width: 100%; border: 2px solid #e2e8f0; border-radius: 14px; padding: 20px; margin-bottom: 20px; resize: none; }
    .premium-submit-btn { width: 100%; background: var(--primary); color: white; padding: 22px; border-radius: 14px; font-weight: 900; text-transform: uppercase; cursor: pointer; border: none; transition: 0.3s; }
    .premium-submit-btn:hover { background: #1abc9c; transform: translateY(-3px); }

    @keyframes fadeInUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

    @media (max-width: 900px) {
        .visual-engine-section, .data-engine-section { flex: 1 1 100%; min-width: 100%; }
        .main-title { font-size: 2.2rem; }
    }
</style>

<script>
    function changeImage(src, element) {
        const display = document.getElementById('main-display');
        display.style.opacity = '0.4';
        display.style.transform = 'scale(0.98)';
        
        setTimeout(() => {
            display.src = src;
            display.style.opacity = '1';
            display.style.transform = 'scale(1)';
        }, 250);

        document.querySelectorAll('.thumb-node').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
    }
</script>
@endsection
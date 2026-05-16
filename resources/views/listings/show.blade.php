{{-- CORRECTION : Utilisation du layout existant --}}
@extends('layouts.app-immo')

@section('title', $listing->title . ' - Dossier Technique')

@section('content')
<div class="technical-dossier-wrapper">
    
    {{-- Fil d'ariane --}}
    <nav class="breadcrumb-nav" aria-label="Breadcrumb">
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
                @php 
                    $defaultPlaceholder = 'https://via.placeholder.com/800x600';
                    $mainImageUrl = $listing->cover_image ? asset('storage/' . $listing->cover_image) : $defaultPlaceholder;
                    
                    // Sécurisation du décodage selon la configuration du modèle Eloquent (Cast ou String)
                    $photos = is_array($listing->images) ? $listing->images : json_decode($listing->images, true);
                @endphp

                <div class="main-display-container">
                    <img src="{{ $mainImageUrl }}" id="main-display" alt="{{ $listing->title }}">
                    
                    <div class="type-badge-overlay">
                        {{ strtoupper($listing->type) }}
                    </div>
                </div>

                {{-- Galerie Matrix --}}
                <div class="thumbnail-matrix">
                    {{-- Image principale toujours en premier --}}
                    <div class="thumb-node active" onclick="changeImage('{{ $mainImageUrl }}', this)">
                        <img src="{{ $mainImageUrl }}" alt="Miniature principale">
                    </div>
                    
                    @if(!empty($photos) && is_array($photos))
                        @foreach($photos as $photo)
                            <div class="thumb-node" onclick="changeImage('{{ asset('storage/' . $photo) }}', this)">
                                <img src="{{ asset('storage/' . $photo) }}" alt="Miniature additionnelle">
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
                                
                                <div class="textarea-wrapper" style="margin-top: 20px;">
                                    <textarea name="content" rows="4" class="modern-textarea" placeholder="Initialiser une communication officielle..." required></textarea>
                                </div>
                                
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
                        <div class="auth-required-box" style="margin-top: 20px;">
                            <a href="{{ route('login') }}" class="auth-required-btn">
                                🔒 Authentification requise pour contacter
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

        </div>
    </div>
</div>

<style>
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
        box-sizing: border-box;
    }

    /* Styles Breadcrumb */
    .breadcrumb-nav { margin-bottom: 30px; display: flex; align-items: center; gap: 12px; }
    .breadcrumb-link { color: var(--text-muted); font-weight: 700; text-decoration: none; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; transition: var(--transition); }
    .breadcrumb-link:hover { color: var(--primary); }
    .separator { color: var(--text-muted); }
    .current-ref { color: var(--text-main); font-weight: 800; font-size: 0.75rem; text-transform: uppercase; }

    /* Layout Grille */
    .main-technical-card { background: white; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-fx); border: 1px solid rgba(0,0,0,0.05); }
    .grid-container { display: flex; flex-wrap: wrap; }

    /* Visual Section */
    .visual-engine-section { flex: 1.2; min-width: 500px; background: var(--dark-panel); padding: 40px; display: flex; flex-direction: column; gap: 25px; box-sizing: border-box; }
    .main-display-container { position: relative; border-radius: var(--radius-md); overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.08); background: #111; }
    #main-display { width: 100%; height: 550px; object-fit: cover; transition: var(--transition); display: block; }
    .type-badge-overlay { position: absolute; bottom: 25px; left: 25px; background: var(--primary); color: white; padding: 10px 22px; border-radius: 8px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; backdrop-filter: blur(8px); box-shadow: 0 4px 12px rgba(0,0,0,0.2); }

    .thumbnail-matrix { display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 15px; }
    .thumb-node { aspect-ratio: 1/1; border-radius: 10px; overflow: hidden; border: 2px solid transparent; cursor: pointer; opacity: 0.4; transition: var(--transition); background: #000; }
    .thumb-node img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .thumb-node:hover { opacity: 0.8; }
    .thumb-node.active { opacity: 1; border-color: var(--primary); box-shadow: 0 0 15px var(--primary-glow); }

    /* Data Section */
    .data-engine-section { flex: 1; min-width: 450px; padding: 60px; background: #fff; box-sizing: border-box; }
    .category-pill { display: inline-block; background: #e8f6f3; color: var(--primary); padding: 6px 18px; border-radius: 50px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; }
    .main-title { color: var(--text-main); font-size: 3.2rem; font-weight: 900; margin: 20px 0; letter-spacing: -2px; line-height: 1.0; }
    .location-tag { color: var(--text-muted); font-weight: 700; font-size: 0.9rem; margin: 0; }

    /* Price Box */
    .price-dashboard { background: var(--text-main); border-radius: 20px; padding: 35px; margin: 45px 0; display: flex; justify-content: space-between; align-items: center; position: relative; overflow: hidden; }
    .price-label { color: #bdc3c7; font-weight: 800; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 8px 0; }
    .price-amount { font-size: 2.5rem; font-weight: 900; color: white; margin: 0; line-height: 1; }
    .price-amount small { font-size: 1.2rem; color: var(--primary); font-weight: 800; margin-left: 5px; }
    .status-indicator { display: flex; align-items: center; background: rgba(255,255,255,0.05); padding: 10px 16px; border-radius: 30px; border: 1px solid rgba(255,255,255,0.1); }
    .status-dot { display: inline-block; width: 8px; height: 8px; background: #2ecc71; border-radius: 50%; margin-right: 10px; animation: blink 2s infinite; box-shadow: 0 0 10px #2ecc71; }
    .status-text { color: white; font-weight: 800; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px; }

    /* Description */
    .section-title { font-size: 0.8rem; font-weight: 900; text-transform: uppercase; color: var(--text-main); letter-spacing: 1px; margin-bottom: 15px; }
    .description-box { background: #fbfcfc; border-left: 4px solid var(--primary); padding: 30px; border-radius: 12px; color: #4a5568; line-height: 1.8; font-size: 0.95rem; }
    .description-box p { margin: 0; }

    /* Contact Console */
    .contact-console { margin-top: 50px; background: #f8fafc; border-radius: 20px; padding: 35px; border: 1px solid #edf2f7; }
    .operator-profile { display: flex; align-items: center; gap: 20px; }
    .operator-avatar { width: 55px; height: 55px; background: var(--text-main); color: var(--primary); border-radius: 15px; display: flex; align-items: center; justify-content: center; font-weight: 900; border: 2px solid var(--primary); font-size: 1.2rem; }
    .meta-label { margin: 0 0 4px 0; color: var(--text-muted); font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
    .meta-value { margin: 0; color: var(--text-main); font-weight: 800; font-size: 1rem; }
    
    .modern-textarea { width: 100%; border: 2px solid #e2e8f0; border-radius: 14px; padding: 20px; margin-bottom: 20px; resize: none; font-family: inherit; font-weight: 600; color: var(--text-main); box-sizing: border-box; transition: var(--transition); }
    .modern-textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px var(--primary-glow); background: white; }
    
    .premium-submit-btn { width: 100%; background: var(--primary); color: white; padding: 20px; border-radius: 14px; font-weight: 900; text-transform: uppercase; cursor: pointer; border: none; transition: var(--transition); letter-spacing: 1px; box-shadow: 0 10px 20px var(--primary-glow); }
    .premium-submit-btn:hover { background: #1abc9c; transform: translateY(-3px); box-shadow: 0 15px 25px var(--primary-glow); }
    
    .admin-notice { margin-top: 25px; background: #ebf5fb; color: #2980b9; padding: 18px; border-radius: 12px; text-align: center; font-weight: 800; font-size: 0.85rem; border: 1px dashed #aed6f1; }
    .auth-required-btn { display: block; text-align: center; background: #e2e8f0; color: #64748b; padding: 18px; border-radius: 12px; text-decoration: none; font-weight: 800; font-size: 0.8rem; transition: var(--transition); }
    .auth-required-btn:hover { background: var(--text-main); color: white; }

    @keyframes fadeInUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

    @media (max-width: 1024px) {
        .grid-container { flex-direction: column; }
        .visual-engine-section, .data-engine-section { flex: 1 1 100%; min-width: 100%; }
        .data-engine-section { padding: 40px; }
    }
    @media (max-width: 768px) {
        .visual-engine-section { padding: 20px; min-width: 100%; }
        #main-display { height: 350px; }
        .main-title { font-size: 2.2rem; }
    }
</style>

<script>
    function changeImage(src, element) {
        const display = document.getElementById('main-display');
        if(!display || display.src === src) return;

        display.style.opacity = '0.3';
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
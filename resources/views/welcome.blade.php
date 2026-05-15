@extends('layouts.app-immo')

@section('content')
<div style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc;">

    {{-- 1. HERO SECTION --}}
    <div style="position: relative; height: 500px; background-image: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'); background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; text-align: center; color: white;">
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); z-index: 1;"></div>
        
        <div style="position: relative; z-index: 2; width: 100%; max-width: 900px; padding: 0 20px;">
            <h1 style="font-size: 3.5em; font-weight: 800; margin-bottom: 10px; line-height: 1.2; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                Trouvez, <span style="color: #16a085;">vendez</span> ou <span style="color: #16a085;">louez</span> <br> votre bien au Cameroun
            </h1>
            <p style="font-size: 1.3em; margin-bottom: 40px; opacity: 0.9;">L'immobilier en direct, sans intermédiaire.</p>

            <div style="background: white; padding: 15px; border-radius: 12px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); display: flex; gap: 10px; align-items: center; max-width: 800px; margin: 0 auto;">
                <div style="flex: 1; display: flex; align-items: center; padding: 10px; background: #f1f5f9; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <span style="margin-right: 10px; color: #64748b;">📍</span>
                    <input type="text" placeholder="Rechercher une ville, un quartier..." style="border: none; background: transparent; width: 100%; outline: none; color: #1e293b; font-weight: 500;">
                </div>
                <button style="background: #2c3e50; color: white; padding: 15px 35px; border-radius: 8px; border: none; font-weight: 700; cursor: pointer; transition: 0.3s;" onmouseover="this.style.background='#16a085'" onmouseout="this.style.background='#2c3e50'">
                    Rechercher
                </button>
            </div>
        </div>
    </div>

    <div style="max-width: 1400px; margin: 0 auto; padding: 60px 20px;">
        
        {{-- 2. SECTION CATEGORIES --}}
        <div style="margin-bottom: 60px;">
            <h2 style="font-size: 1.8em; font-weight: 800; color: #1e293b; margin-bottom: 30px;">Parcourir par type de bien</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                
                @php
                    $catImages = [
                        'appartement' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=400&q=80',
                        'villa'       => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=400&q=80',
                        'maison'      => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=400&q=80',
                        'studio'      => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=400&q=80',
                        'terrain'     => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=400&q=80',
                        'chambre'     => 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=400&q=80',
                        'bureau'      => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=400&q=80',
                        'commerce'    => 'https://images.unsplash.com/photo-1534452207594-1a7ee595a655?auto=format&fit=crop&w=400&q=80',
                        'parking'     => 'https://images.unsplash.com/photo-1573348722427-f1d6819fdf98?auto=format&fit=crop&w=400&q=80',
                        'garage'      => 'https://images.unsplash.com/photo-1590674852885-7c602052994e?auto=format&fit=crop&w=400&q=80',
                    ];
                @endphp

                @foreach($categories as $category)
                    @php
                        $bgImage = 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=400&q=80';
                        $catNameLower = strtolower(trim($category->name));

                        foreach($catImages as $keyword => $url) {
                            if(str_contains($catNameLower, $keyword)) {
                                $bgImage = $url;
                                break; 
                            }
                        }
                    @endphp

                    <a href="{{ route('listings.index', ['category_id' => $category->id]) }}" 
                       class="category-tile" 
                       style="background-image: url('{{ $bgImage }}');">
                        <div class="category-overlay">
                            <span style="font-size: 1.1em; font-weight: 800; text-transform: uppercase;">{{ $category->name }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- 3. SECTION ANNONCES AVEC FILTRES À GAUCHE --}}
        <div style="display: flex; gap: 30px; align-items: flex-start;">
            
            {{-- SIDEBAR --}}
            <div style="width: 280px; background: white; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0; position: sticky; top: 20px;">
                <h3 style="font-size: 1.1em; font-weight: 800; color: #1e293b; margin-bottom: 20px; border-bottom: 2px solid #16a085; padding-bottom: 10px;">Filtres</h3>
                <form action="{{ route('listings.index') }}" method="GET">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 700; color: #64748b; margin-bottom: 8px; font-size: 0.85em;">Ville</label>
                        <select name="location" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc; outline: none;">
                            <option value="">Toutes les villes</option>
                            <option value="Douala">Douala</option>
                            <option value="Yaoundé">Yaoundé</option>
                            <option value="Kribi">Kribi</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 25px;">
                        <label style="display: block; font-weight: 700; color: #64748b; margin-bottom: 8px; font-size: 0.85em;">Budget Max</label>
                        <div style="display: flex; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 5px 12px;">
                            <input type="number" name="price_max" placeholder="FCFA" style="border: none; background: transparent; width: 100%; outline: none;">
                        </div>
                    </div>
                    <button type="submit" style="width: 100%; background: #16a085; color: white; padding: 12px; border-radius: 8px; border: none; font-weight: 700; cursor: pointer;">Filtrer</button>
                </form>
            </div>

            {{-- GRILLE D'ANNONCES (4 COLONNES) --}}
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                    <h2 style="font-size: 1.5em; font-weight: 800; color: #1e293b; margin: 0;">Annonces à la une</h2>
                    <span style="color: #64748b; font-size: 0.85em;">{{ $lastListings->count() }} disponibles</span>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
                    @foreach($lastListings as $listing)
                        <div class="immo-card">
                            <div style="position: relative; overflow: hidden;">
                                <img src="{{ $listing->cover_image ? asset('storage/' . $listing->cover_image) : 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=400&q=80' }}" class="immo-img">
                                <div class="immo-badge">✓</div>
                            </div>
                            <div style="padding: 15px;">
                                <p style="color: #16a085; font-size: 0.75em; margin-bottom: 5px; font-weight: 700; text-transform: uppercase;">{{ $listing->location }}</p>
                                <h3 style="font-size: 0.95em; font-weight: 700; color: #1e293b; margin-bottom: 12px; height: 2.8em; overflow: hidden; line-height: 1.4;">{{ $listing->title }}</h3>
                                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 12px;">
                                    <span style="color: #2c3e50; font-weight: 800; font-size: 1em;">
                                        {{ number_format($listing->price, 0, ',', ' ') }} <small style="font-size: 0.7em;">F</small>
                                    </span>
                                    <a href="{{ route('listings.show', $listing->id) }}" class="btn-inspect">Voir</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .category-tile { height: 160px; background-size: cover; background-position: center; border-radius: 12px; position: relative; overflow: hidden; display: block; transition: 0.4s; }
    .category-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white; transition: 0.4s; text-align: center; padding: 10px; }
    .category-tile:hover .category-overlay { background: rgba(22, 160, 133, 0.6); }
    .category-tile:hover { transform: translateY(-3px); }
    .immo-card { border-radius: 12px; overflow: hidden; background: white; border: 1px solid #e2e8f0; transition: 0.3s; }
    .immo-card:hover { transform: translateY(-5px); box-shadow: 0 12px 20px rgba(0,0,0,0.05); }
    .immo-img { width: 100%; height: 180px; object-fit: cover; }
    .immo-badge { position: absolute; top: 10px; right: 10px; background: #22c55e; color: white; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 0.7em; font-weight: bold; }
    .btn-inspect { background: #f8fafc; color: #1e293b; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: 700; font-size: 0.75em; border: 1px solid #e2e8f0; transition: 0.3s; }
    .btn-inspect:hover { background: #2c3e50; color: white; border-color: #2c3e50; }
</style>
@endsection
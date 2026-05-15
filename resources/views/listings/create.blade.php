@extends('layouts.app-immo')

@section('content')
<div class="injection-wrapper">
    
    {{-- HEADER : NAVIGATION --}}
    <div class="injection-header">
        <div class="header-content">
            <h1 class="main-title">Injection <span class="accent">Asset</span></h1>
            <p class="subtitle">Enregistrement d'une nouvelle unité dans la base Immo-Kam</p>
        </div>
        <a href="{{ route('listings.index') }}" class="btn-cancel">
            <span class="icon">←</span> ANNULER L'OPÉRATION
        </a>
    </div>

    {{-- FORMULAIRE DE CRÉATION --}}
    <div class="form-container">
        <form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data" class="grid-form">
            @csrf

            {{-- TITRE --}}
            <div class="form-group full-width">
                <label>Désignation de l'Asset</label>
                <div class="input-wrapper">
                    <input type="text" name="title" placeholder="ex: Villa moderne avec piscine..." required>
                    <span class="focus-border"></span>
                </div>
            </div>

            {{-- CATÉGORIE --}}
            <div class="form-group">
                <label>Secteur / Catégorie</label>
                <div class="select-container">
                    <select name="category_id" required>
                        <option value="">Sélectionner un secteur...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- PRIX --}}
            <div class="form-group">
                <label>Valeur estimée (XAF)</label>
                <div class="input-wrapper">
                    <input type="number" name="price" placeholder="Montant en FCFA" required>
                    <span class="unit-tag">XAF</span>
                </div>
            </div>

            {{-- LOCALISATION --}}
            <div class="form-group">
                <label>Coordonnées / Localisation</label>
                <div class="input-wrapper">
                    <input type="text" name="location" placeholder="ex: Bastos, Yaoundé" required>
                </div>
            </div>

            {{-- TYPE --}}
            <div class="form-group">
                <label>Mode de transaction</label>
                <div class="select-container">
                    <select name="type" required>
                        <option value="location">Location</option>
                        <option value="vente">Vente</option>
                    </select>
                </div>
            </div>

            {{-- DESCRIPTION --}}
            <div class="form-group full-width">
                <label>Spécifications techniques (Description)</label>
                <textarea name="description" rows="5" placeholder="Détails complets de l'unité..." required></textarea>
            </div>

            {{-- IMAGE --}}
            <div class="form-group full-width">
                <label>Capture visuelle (Image de couverture)</label>
                <div class="file-upload-zone" id="drop-zone">
                    <input type="file" name="cover_image" id="file-input" accept="image/*" required>
                    <div class="upload-content">
                        <span class="upload-icon">📷</span>
                        <p id="file-name">Glissez une image ici ou cliquez pour explorer</p>
                        <small>Formats acceptés : JPG, PNG, WEBP</small>
                    </div>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="form-actions full-width">
                <button type="submit" class="btn-execute">
                    <span class="btn-text">EXÉCUTER L'INJECTION</span>
                    <span class="btn-icon">🚀</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    :root {
        --primary: #16a085;
        --primary-light: #1abc9c;
        --dark: #2c3e50;
        --gray-bg: #f8fafc;
        --border-color: #e2e8f0;
        --radius-lg: 20px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .injection-wrapper {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 20px;
        animation: slideUp 0.6s ease-out;
        font-family: 'Inter', system-ui, sans-serif;
    }

    /* Header */
    .injection-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 35px;
        border-bottom: 3px solid var(--border-color);
        padding-bottom: 20px;
    }

    .main-title { font-size: 2.5rem; font-weight: 950; color: var(--dark); margin: 0; letter-spacing: -2px; text-transform: uppercase; }
    .main-title .accent { color: var(--primary); }
    .subtitle { color: #94a3b8; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; }

    .btn-cancel {
        background: #f1f5f9;
        color: #64748b;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 900;
        text-decoration: none;
        font-size: 0.7rem;
        letter-spacing: 1px;
        transition: var(--transition);
        border: 1px solid transparent;
    }
    .btn-cancel:hover { background: #fee2e2; color: #ef4444; border-color: #fecaca; }

    /* Form Layout */
    .form-container {
        background: white;
        padding: 50px;
        border-radius: 30px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.04);
        border: 1px solid var(--border-color);
    }

    .grid-form { display: grid; grid-template-columns: 1fr 1fr; gap: 35px; }
    .full-width { grid-column: span 2; }

    .form-group label {
        display: block;
        font-size: 0.65rem;
        font-weight: 900;
        color: var(--primary);
        text-transform: uppercase;
        margin-bottom: 12px;
        letter-spacing: 1.5px;
    }

    /* Inputs & Selects */
    .input-wrapper { position: relative; }
    input[type="text"], input[type="number"], select, textarea {
        width: 100%;
        background: var(--gray-bg);
        border: 2px solid #edf2f7;
        padding: 18px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--dark);
        transition: var(--transition);
    }

    input:focus, select:focus, textarea:focus {
        background: white;
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 5px rgba(22, 160, 133, 0.1);
    }

    .unit-tag {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        font-weight: 900;
        font-size: 0.7rem;
        color: #cbd5e1;
    }

    /* File Upload Zone */
    .file-upload-zone {
        position: relative;
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        border-radius: 18px;
        padding: 40px;
        text-align: center;
        transition: var(--transition);
        cursor: pointer;
    }

    .file-upload-zone:hover { border-color: var(--primary); background: #f0fdfa; }
    .file-upload-zone input[type="file"] {
        position: absolute; width: 100%; height: 100%; top: 0; left: 0; opacity: 0; cursor: pointer;
    }

    .upload-icon { font-size: 2.5rem; display: block; margin-bottom: 15px; }
    .upload-content p { font-weight: 800; color: var(--dark); margin: 0; }
    .upload-content small { color: #94a3b8; font-weight: 600; }

    /* Submit Button */
    .btn-execute {
        width: 100%;
        background: var(--dark);
        color: white;
        border: none;
        padding: 24px;
        border-radius: 18px;
        font-weight: 950;
        font-size: 0.9rem;
        letter-spacing: 2px;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        box-shadow: 0 10px 25px rgba(44, 62, 80, 0.2);
    }

    .btn-execute:hover {
        background: var(--primary);
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(22, 160, 133, 0.3);
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .grid-form { grid-template-columns: 1fr; }
        .full-width { grid-column: span 1; }
        .form-container { padding: 30px; }
    }
</style>

<script>
    // Petit script pour afficher le nom du fichier sélectionné
    const fileInput = document.getElementById('file-input');
    const fileNameDisplay = document.getElementById('file-name');

    fileInput.addEventListener('change', function(e) {
        const name = e.target.files[0] ? e.target.files[0].name : "Glissez une image ici ou cliquez pour explorer";
        fileNameDisplay.innerText = name;
        fileNameDisplay.style.color = "#16a085";
    });
</script>
@endsection
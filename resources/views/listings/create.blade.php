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
                <label for="title">Désignation de l'Asset</label>
                <div class="input-wrapper @error('title') has-error @enderror">
                    <input type="text" id="title" name="title" placeholder="ex: Villa moderne avec piscine..." value="{{ old('title') }}" required>
                    <span class="focus-border"></span>
                </div>
                @error('title')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            {{-- CATÉGORIE --}}
            <div class="form-group">
                <label for="category_id">Secteur / Catégorie</label>
                <div class="select-container @error('category_id') has-error @enderror">
                    <select id="category_id" name="category_id" required>
                        <option value="">Sélectionner un secteur...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('category_id')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            {{-- PRIX --}}
            <div class="form-group">
                <label for="price">Valeur estimée (XAF)</label>
                <div class="input-wrapper @error('price') has-error @enderror">
                    <input type="number" id="price" name="price" placeholder="Montant en FCFA" value="{{ old('price') }}" required>
                    <span class="unit-tag">XAF</span>
                </div>
                @error('price')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            {{-- LOCALISATION --}}
            <div class="form-group">
                <label for="location">Coordonnées / Localisation</label>
                <div class="input-wrapper @error('location') has-error @enderror">
                    <input type="text" id="location" name="location" placeholder="ex: Bastos, Yaoundé" value="{{ old('location') }}" required>
                </div>
                @error('location')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            {{-- TYPE --}}
            <div class="form-group">
                <label for="type">Mode de transaction</label>
                <div class="select-container @error('type') has-error @enderror">
                    <select id="type" name="type" required>
                        <option value="location" {{ old('type') == 'location' ? 'selected' : '' }}>Location</option>
                        <option value="vente" {{ old('type') == 'vente' ? 'selected' : '' }}>Vente</option>
                    </select>
                </div>
                @error('type')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            {{-- DESCRIPTION --}}
            <div class="form-group full-width">
                <label for="description">Spécifications techniques (Description)</label>
                <div class="input-wrapper @error('description') has-error @enderror">
                    <textarea id="description" name="description" rows="5" placeholder="Détails complets de l'unité..." required>{{ old('description') }}</textarea>
                </div>
                @error('description')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            {{-- IMAGE --}}
            <div class="form-group full-width">
                <label>Capture visuelle (Image de couverture)</label>
                <div class="file-upload-zone @error('cover_image') has-error-zone @enderror" id="drop-zone">
                    <input type="file" name="cover_image" id="file-input" accept="image/*" required>
                    <div class="upload-content">
                        <span class="upload-icon" id="upload-icon">📷</span>
                        <p id="file-name">Glissez une image ici ou cliquez pour explorer</p>
                        <small>Formats acceptés : JPG, PNG, WEBP</small>
                    </div>
                </div>
                @error('cover_image')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
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
        --error-color: #ef4444;
        --radius-lg: 20px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .injection-wrapper {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 20px;
        animation: slideUp 0.6s ease-out;
        font-family: 'Inter', system-ui, sans-serif;
        box-sizing: border-box;
    }

    /* Header */
    .injection-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
        margin-bottom: 35px;
        border-bottom: 3px solid var(--border-color);
        padding-bottom: 20px;
    }

    .main-title { font-size: 2.5rem; font-weight: 950; color: var(--dark); margin: 0; letter-spacing: -2px; text-transform: uppercase; }
    .main-title .accent { color: var(--primary); }
    .subtitle { color: #94a3b8; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; }

    .btn-cancel {
        display: inline-block;
        white-space: nowrap;
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
    .btn-cancel:hover { background: #fee2e2; color: var(--error-color); border-color: #fecaca; }

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

    .form-group { display: flex; flex-direction: column; }
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
    .input-wrapper { position: relative; width: 100%; }
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
        box-sizing: border-box;
    }

    input:focus, select:focus, textarea:focus {
        background: white;
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 5px rgba(22, 160, 133, 0.1);
    }

    /* State Error Styles */
    .has-error input, .has-error select, .has-error textarea {
        border-color: var(--error-color) !important;
        background-color: #fffbfa;
    }
    .has-error input:focus, .has-error select:focus, .has-error textarea:focus {
        box-shadow: 0 0 0 5px rgba(239, 68, 68, 0.1);
    }
    .error-msg {
        color: var(--error-color);
        font-size: 0.75rem;
        font-weight: 700;
        margin-top: 8px;
        animation: fadeInError 0.3s ease-out;
    }

    .unit-tag {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        font-weight: 900;
        font-size: 0.7rem;
        color: #cbd5e1;
        pointer-events: none;
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
    .file-upload-zone.drag-over {
        border-color: var(--primary);
        background: #f0fdfa;
        transform: scale(1.01);
    }
    .has-error-zone {
        border-color: var(--error-color) !important;
        background: #fffbfa;
    }

    .file-upload-zone input[type="file"] {
        position: absolute; width: 100%; height: 100%; top: 0; left: 0; opacity: 0; cursor: pointer;
    }

    .upload-icon { font-size: 2.5rem; display: block; margin-bottom: 15px; transition: var(--transition); }
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
    @keyframes fadeInError {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .grid-form { grid-template-columns: 1fr; gap: 25px; }
        .full-width { grid-column: span 1; }
        .form-container { padding: 30px; }
        .injection-header { flex-direction: column; align-items: flex-start; }
        .btn-cancel { width: 100%; text-align: center; box-sizing: border-box; }
    }
</style>

<script>
    const fileInput = document.getElementById('file-input');
    const fileNameDisplay = document.getElementById('file-name');
    const dropZone = document.getElementById('drop-zone');
    const uploadIcon = document.getElementById('upload-icon');

    // Changement via explorateur de fichiers classique
    fileInput.addEventListener('change', function(e) {
        handleFileFeedback(e.target.files[0]);
    });

    // Gestion du Drag & Drop natif pour empêcher l'ouverture d'onglet indésirable
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
        }, false);
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;

        if(files.length > 0 && files[0].type.startsWith('image/')) {
            fileInput.files = files; // Assigne le fichier déposé au composant input
            handleFileFeedback(files[0]);
        }
    });

    function handleFileFeedback(file) {
        if (file) {
            fileNameDisplay.innerText = file.name;
            fileNameDisplay.style.color = "#16a085";
            uploadIcon.innerText = "✅";
        } else {
            fileNameDisplay.innerText = "Glissez une image ici ou cliquez pour explorer";
            fileNameDisplay.style.color = "var(--dark)";
            uploadIcon.innerText = "📷";
        }
    }
</script>
@endsection
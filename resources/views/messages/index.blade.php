@extends('layouts.app-immo')

@section('content')
<div class="messaging-container">
    
    {{-- Header de la Messagerie --}}
    <div class="messaging-header">
        <div class="header-titles">
            <h1>Centre de <span>Communications</span></h1>
            <p>Gestion des interactions prospects et clients</p>
        </div>
        <div class="stats-badge">
            <span class="stats-label">Total Messages</span>
            <span class="stats-value">{{ $messages->count() }}</span>
        </div>
    </div>

    @if($messages->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">💬</div>
            <p class="empty-title">Aucun flux entrant détecté.</p>
            <p class="empty-subtitle">Les messages de vos clients apparaîtront ici en temps réel.</p>
        </div>
    @else
        <div class="messages-list">
            @foreach($messages as $message)
                {{-- Style dynamique si le message n'est pas lu --}}
                <div class="message-card {{ !$message->is_read ? 'unread-card' : '' }}">
                    
                    {{-- Header du Message --}}
                    <div class="card-header">
                        <div class="sender-info">
                            <div class="sender-avatar">
                                {{ substr($message->sender->name, 0, 1) }}
                                @if(!$message->is_read)
                                    <span class="unread-indicator"></span>
                                @endif
                            </div>
                            <div class="sender-details">
                                <div class="sender-name-wrapper">
                                    <h3>{{ $message->sender->name }}</h3>
                                    @if(!$message->is_read)
                                        <span class="new-label">Nouveau</span>
                                    @endif
                                </div>
                                <p class="timestamp">Reçu {{ $message->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="reference-badge">
                            Ref: {{ Str::limit($message->listing->title ?? 'Bien inconnu', 25) }}
                        </div>
                    </div>
                    
                    {{-- Corps du Message --}}
                    <div class="card-body">
                        <div class="quote-wrapper">
                            {{ Str::limit($message->content, 150) }}
                        </div>
                    </div>

                    {{-- Actions Footer --}}
                    <div class="card-footer">
                        <div class="footer-actions">
                            <a href="mailto:{{ $message->sender->email }}" class="btn-email">
                                📧 Email
                            </a>
                            {{-- Route synchronisée sur 'messages.show' --}}
                            <a href="{{ route('messages.show', $message->id) }}" class="btn-reply">
                                <span>💬</span> Répondre / Détails
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 30px;">
            {{ $messages->links() }}
        </div>
    @endif
</div>

<style>
    :root {
        --primary: #16a085;
        --dark: #2c3e50;
        --text-muted: #95a5a6;
        --unread-border: #f39c12;
        --shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
        --transition: all 0.3s ease;
    }

    .messaging-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 20px;
        font-family: 'Inter', sans-serif;
    }

    /* Style spécifique pour messages NON LUS */
    .unread-card {
        border-left: 5px solid var(--unread-border) !important;
        background-color: #fffcf5 !important;
    }

    .unread-indicator {
        position: absolute;
        top: -3px;
        right: -3px;
        width: 12px;
        height: 12px;
        background: var(--unread-border);
        border: 2px solid white;
        border-radius: 50%;
        animation: pulseIndicator 2s infinite;
        flex-shrink: 0;
    }

    .new-label {
        background: var(--unread-border);
        color: white;
        font-size: 0.6rem;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .messaging-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 40px;
        gap: 20px;
    }

    .header-titles h1 { font-size: 2.2rem; font-weight: 900; margin: 0; text-transform: uppercase; }
    .header-titles h1 span { color: var(--primary); }

    .stats-badge {
        background: white;
        padding: 12px 20px;
        border-radius: 12px;
        box-shadow: var(--shadow);
        text-align: center;
        border: 1px solid #eee;
        white-space: nowrap;
    }

    .stats-value { font-size: 1.5rem; font-weight: 900; color: var(--primary); display: block; }

    .messages-list { display: flex; flex-direction: column; gap: 20px; }

    .message-card {
        background: white;
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.08);
        box-shadow: var(--shadow);
        transition: var(--transition);
        overflow: hidden;
    }

    .message-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    .card-header {
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .sender-avatar {
        width: 45px;
        height: 45px;
        background: var(--dark);
        color: var(--primary);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        position: relative;
        flex-shrink: 0;
    }

    .sender-info { display: flex; align-items: center; gap: 15px; min-width: 0; }
    .sender-details { min-width: 0; }
    .sender-name-wrapper { display: flex; align-items: center; gap: 10px; min-width: 0; }
    .sender-name-wrapper h3 { margin: 0; font-size: 1.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .timestamp { margin: 0; font-size: 0.8rem; color: var(--text-muted); }

    .reference-badge {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--primary);
        background: #e8f6f3;
        padding: 5px 12px;
        border-radius: 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }

    .card-body { padding: 20px 25px; }
    .quote-wrapper {
        font-size: 1rem;
        line-height: 1.6;
        color: #555;
        border-left: 3px solid var(--primary);
        padding-left: 15px;
        word-break: break-word;
    }

    .card-footer { padding: 15px 25px; background: #fafafa; display: flex; justify-content: flex-end; }
    .footer-actions { display: flex; gap: 12px; }

    .btn-reply, .btn-email {
        text-decoration: none;
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        transition: 0.2s;
        text-align: center;
    }

    .btn-reply { background: var(--dark); color: white; }
    .btn-reply:hover { background: var(--primary); }
    .btn-email { background: white; color: var(--dark); border: 1px solid #ddd; }

    .empty-state {
        background: white;
        padding: 60px;
        border-radius: 20px;
        text-align: center;
        border: 2px dashed #ddd;
    }

    @keyframes pulseIndicator {
        0% { box-shadow: 0 0 0 0 rgba(243, 156, 18, 0.4); }
        70% { box-shadow: 0 0 0 8px rgba(243, 156, 18, 0); }
        100% { box-shadow: 0 0 0 0 rgba(243, 156, 18, 0); }
    }

    /* ========================================================
       MEDIA QUERIES : ADAPTATION HUB MESSAGERIE (MOBILE)
       ======================================================== */
    @media (max-width: 768px) {
        .messaging-container { margin: 15px auto; padding: 0 10px; }

        /* 1. Header principal empilé */
        .messaging-header { flex-direction: column; align-items: flex-start; gap: 15px; margin-bottom: 25px; }
        .stats-badge { width: 100%; box-sizing: border-box; }

        /* 2. Réorganisation de l'en-tête de carte (Card Header) */
        .card-header { flex-direction: column; align-items: flex-start; gap: 12px; padding: 15px; }
        .sender-info { width: 100%; }
        .sender-name-wrapper h3 { font-size: 1rem; }
        .reference-badge { align-self: flex-start; max-width: 100%; width: auto; }

        /* 3. Zone de texte et Footer */
        .card-body { padding: 15px; }
        .card-footer { padding: 12px 15px; }
        .footer-actions { width: 100%; gap: 10px; }
        .btn-reply, .btn-email { flex: 1; padding: 10px 5px; font-size: 0.7rem; }
    }
</style>
@endsection
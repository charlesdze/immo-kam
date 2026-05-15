@extends('layouts.app-immo')

@section('content')
{{-- Suppression des paddings latéraux sur le fond pour gagner de la place --}}
<div class="d-flex align-items-center justify-content-center" style="min-height: 85vh; background-color: #f4f7f6; padding: 20px 0;">
    <div class="container-fluid p-0"> {{-- p-0 pour coller aux bords si nécessaire --}}
        <div class="row justify-content-center m-0"> {{-- m-0 pour éviter le scroll horizontal --}}
            
            {{-- Utilisation de col-12 pour occuper toute la largeur horizontale --}}
            <div class="col-12 col-xl-12 px-md-5"> 
                
                {{-- Navigation de retour --}}
                <div class="mb-4 px-3">
                    <a href="{{ route('admin.messages') }}" class="btn btn-link text-decoration-none p-0" style="color: #7f8c8d; font-weight: 600; font-size: 1.1rem;">
                        <i class="fas fa-chevron-left"></i> Retour à la messagerie
                    </a>
                </div>

                <div class="card shadow-lg border-0" style="border-radius: 30px; overflow: hidden;">
                    
                    {{-- Header de discussion --}}
                    <div class="card-header bg-white py-4 border-0">
                        <div class="row align-items-center">
                            <div class="col-md-12 text-center">
                                <h3 class="mb-1" style="font-weight: 800; color: #2c3e50; letter-spacing: -1px;">
                                    Discussion avec <span style="color: #16a085;">{{ $message->sender->name }}</span>
                                </h3>
                                <div class="d-flex justify-content-center gap-3 mt-2">
                                    <span class="text-muted"><i class="far fa-envelope me-1"></i> {{ $message->sender->email }}</span>
                                    <span class="text-muted">|</span>
                                    <span class="text-muted"><i class="far fa-clock me-1"></i> Reçu le {{ $message->created_at->format('d/m/Y à H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- card-body avec padding latéral réduit pour élargir le contenu --}}
                    <div class="card-body px-3 px-md-4 py-5">
                        
                        {{-- Message Reçu --}}
                        <div class="received-section mb-5">
                            <div class="label-badge mb-3">
                                <span class="badge px-4 py-2" style="background-color: #16a085; color: white; border-radius: 50px; font-size: 0.8rem; text-transform: uppercase;">Message du client</span>
                            </div>
                            <div class="message-bubble-received p-4 shadow-sm" style="background-color: #fcfcfc; border-radius: 25px; border: 1px solid #edf2f7; border-left: 8px solid #16a085;">
                                <p class="mb-0" style="font-size: 1.2rem; color: #4a5568; line-height: 1.8;">
                                    {{ $message->content }}
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center my-5">
                            <div class="flex-grow-1" style="height: 1px; background: linear-gradient(to right, transparent, #e2e8f0, transparent);"></div>
                            <span class="mx-4 text-muted small fw-bold" style="letter-spacing: 2px;">VOTRE RÉPONSE PROFESSIONNELLE</span>
                            <div class="flex-grow-1" style="height: 1px; background: linear-gradient(to right, transparent, #e2e8f0, transparent);"></div>
                        </div>

                        {{-- Formulaire de réponse --}}
                        <form action="{{ route('messages.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $message->sender_id }}">
                            <input type="hidden" name="listing_id" value="{{ $message->listing_id }}">

                            <div class="form-group mb-5">
                                {{-- Extension horizontale maximale --}}
                                <textarea name="content" class="form-control border-0 p-4 shadow-sm" 
                                    rows="6" 
                                    placeholder="Écrivez ici votre réponse..." 
                                    style="width: 100%; border-radius: 20px; background-color: #f8fafc; font-size: 1.2rem; border: 1px solid #e2e8f0 !important; resize: none;" 
                                    required></textarea>
                            </div>

                            <div class="text-center pb-3">
                                <button type="submit" class="btn btn-success px-5 py-3 shadow-lg" 
                                    style="background-color: #16a085; border: none; border-radius: 50px; font-weight: 800; font-size: 1.2rem; width: 100%; max-width: 600px;">
                                    <i class="fas fa-paper-plane me-2"></i> ENVOYER LA RÉPONSE
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    /* On s'assure que le container prend toute la largeur de l'écran */
    .container-fluid {
        max-width: 100% !important;
    }

    .form-control:focus {
        background-color: #ffffff !important;
        border-color: #16a085 !important;
        box-shadow: 0 15px 30px rgba(22, 160, 133, 0.1) !important;
    }

    .btn-success:hover {
        background-color: #138d75 !important;
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(22, 160, 133, 0.4) !important;
    }

    .card {
        animation: fadeInWide 0.6s ease-out;
    }
    
    @keyframes fadeInWide {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
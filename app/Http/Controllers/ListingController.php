<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    /**
     * Affiche la liste des annonces avec filtres de recherche.
     */
    public function index(Request $request)
    {
        // On prépare la requête de base
        $query = Listing::with(['category', 'user']);

        // Filtre par mot-clé (Titre ou Localisation)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        // Filtre par catégorie
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filtre par prix maximum
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Pagination de 10 annonces par page
        $listings = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('listings.index', compact('listings', 'categories'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
{
    // Récupère toutes les catégories depuis la base de données
    $categories = \App\Models\Category::all();

    // Vérifie si la variable est bien passée ici
    return view('listings.create', compact('categories'));
}

    /**
     * Enregistre une nouvelle annonce.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string',
            'type' => 'required|in:vente,location',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('listings', 'public');
        }

        Listing::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'location' => $request->location,
            'type' => $request->type,
            'cover_image' => $path
        ]);

        return redirect()->route('listings.index')->with('success', 'Annonce publiée avec succès !');
        $request->validate([
        'title' => 'required',
        'images.*' => 'image|mimes:jpeg,png,jpg|max:2048' // Max 2Mo par image
    ]);

    $listing = new Listing();
    $listing->title = $request->title;
    // ... autres champs ...
    $listing->user_id = auth()->id();

    $imagesPaths = [];

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            // Stocke l'image dans storage/app/public/listings
            $path = $image->store('listings', 'public');
            $imagesPaths[] = $path;
        }
    }

    // On enregistre les chemins sous forme de JSON dans la base de données
    $listing->images = json_encode($imagesPaths); 
    $listing->save();

    return redirect()->route('admin.index')->with('success', 'Annonce créée avec photos !');

    }

    /**
     * Affiche les détails d'une annonce.
     */
    public function show(Listing $listing)
    {
        $listing->load(['category', 'user']);
        return view('listings.show', compact('listing'));
    }

    /**
     * Dashboard Admin : Voir toutes les annonces.
     */
    public function adminIndex() 
    {
        $listings = Listing::with('user')->latest()->get();
        return view('admin.dashboard', compact('listings'));
    }

    /**
     * Supprime une annonce (Admin ou Propriétaire).
     */
    public function destroy(Listing $listing) 
    {
        // Supprimer l'image physiquement si elle existe
        if ($listing->cover_image) {
            Storage::disk('public')->delete($listing->cover_image);
        }
        
        $listing->delete();
        return back()->with('success', 'Annonce supprimée avec succès.');
    }

    /**
     * Formulaire d'édition (Optionnel).
     */
    public function edit(Listing $listing)
    {
        $categories = Category::all();
        return view('listings.edit', compact('listing', 'categories'));
    }

    /**
     * Mise à jour de l'annonce (Optionnel).
     */
    public function update(Request $request, Listing $listing)
    {
        // Logique de mise à jour ici...
    }
}
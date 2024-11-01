<?php

namespace App\Http\Controllers;
use App\Models\Offer;
use Illuminate\Http\Request;


class OfferController extends Controller
{
    public function index(Request $request)
    {
        // Initialiser une requête pour la table Offer
        $query = Offer::query();

        // Si la requête contient un paramètre 'search', on applique la recherche
        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Obtenir les résultats
        $offers = $query->get();

        // Vérifier si la requête est AJAX et retourner les données en JSON
        if ($request->ajax()) {
            return response()->json($offers);
        }

        // Retourner la vue avec les résultats si la requête n'est pas AJAX
        return view('offers.index', compact('offers'));
    }

    // Liste toutes les offres
//    public function index()
//    {
//        $offers = Offer::all();
//        return view('offers.index', compact('offers'));
//    }

    // Affiche le formulaire de création
    public function create()
    {
        return view('offers.create');
    }

    // Enregistre une nouvelle offre
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'details' => 'nullable|string',
        ]);

        Offer::create($request->all());

        return redirect()->route('offers.index')->with('success', 'Offre créée avec succès.');
    }

    // Affiche une offre spécifique
    public function show(Offer $offer)
    {
        return view('offers.show', compact('offer'));
    }

    // Affiche le formulaire d'édition
    public function edit(Offer $offer)
    {
        return view('offers.edit', compact('offer'));
    }

    // Met à jour une offre existante
    public function update(Request $request, Offer $offer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'details' => 'nullable|string',
        ]);

        $offer->update($request->all());

        return redirect()->route('offers.index')->with('success', 'Offre mise à jour avec succès.');
    }

    // Supprime une offre
    public function destroy(Offer $offer)
    {
        $offer->delete();
        return redirect()->route('offers.index')->with('success', 'Offre supprimée avec succès.');
    }

}

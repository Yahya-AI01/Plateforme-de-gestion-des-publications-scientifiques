<?php

/**
 * Contrôleur pour la gestion des recherches
 *
 * Ce contrôleur gère les fonctionnalités de recherche simple et avancée
 * dans les publications du système.
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;

/**
 * Classe SearchController
 *
 * Gère les fonctionnalités de recherche dans les publications
 */
class SearchController extends Controller
{
    /**
     * Effectue une recherche simple dans les publications
     *
     * Recherche dans le titre, résumé, domaine et auteurs des publications.
     *
     * @param Request $request La requête contenant le terme de recherche
     * @return \Illuminate\View\View Vue des résultats de recherche
     */
    public function searchPublications(Request $request)
    {
        // Récupère le terme de recherche
        $query = $request->get('q');

        // Recherche dans les publications avec pagination
        $publications = Publication::where('titre', 'LIKE', "%{$query}%")
            ->orWhere('resume', 'LIKE', "%{$query}%")
            ->orWhere('domaine', 'LIKE', "%{$query}%")
            ->orWhereHas('auteurPrincipal', function($q) use ($query) {
                $q->where('nom', 'LIKE', "%{$query}%")
                  ->orWhere('prenom', 'LIKE', "%{$query}%");
            })
            ->orWhereHas('coAuteurs', function($q) use ($query) {
                $q->where('nom', 'LIKE', "%{$query}%")
                  ->orWhere('prenom', 'LIKE', "%{$query}%");
            })
            ->with(['auteurPrincipal', 'coAuteurs'])
            ->paginate(15);

        // Retourne la vue de la bibliothèque avec les résultats
        return view('shared.bibliotheque', compact('publications', 'query'));
    }

    /**
     * Effectue une recherche avancée dans les publications
     *
     * Recherche avec des filtres spécifiques : titre, auteur, domaine, type, année.
     *
     * @param Request $request La requête contenant les critères de recherche
     * @return \Illuminate\View\View Vue des résultats de recherche avancée
     */
    public function advancedSearch(Request $request)
    {
        // Initialise la requête avec les relations
        $query = Publication::query()->with(['auteurPrincipal', 'coAuteurs']);

        // Filtre par titre si fourni
        if ($request->filled('titre')) {
            $query->where('titre', 'LIKE', '%' . $request->titre . '%');
        }

        // Filtre par auteur si fourni
        if ($request->filled('auteur')) {
            $query->whereHas('auteurPrincipal', function($q) use ($request) {
                $q->where('nom', 'LIKE', '%' . $request->auteur . '%')
                  ->orWhere('prenom', 'LIKE', '%' . $request->auteur . '%');
            })->orWhereHas('coAuteurs', function($q) use ($request) {
                $q->where('nom', 'LIKE', '%' . $request->auteur . '%')
                  ->orWhere('prenom', 'LIKE', '%' . $request->auteur . '%');
            });
        }

        // Filtre par domaine si fourni
        if ($request->filled('domaine')) {
            $query->where('domaine', 'LIKE', '%' . $request->domaine . '%');
        }

        // Filtre par type si fourni
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtre par année si fourni
        if ($request->filled('annee')) {
            $query->where('annee', $request->annee);
        }

        // Exécute la requête avec pagination
        $publications = $query->paginate(15);

        // Retourne la vue de la bibliothèque avec les résultats
        return view('shared.bibliotheque', compact('publications'));
    }
}

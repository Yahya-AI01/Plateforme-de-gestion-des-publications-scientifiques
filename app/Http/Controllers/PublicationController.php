<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use App\Models\Professor;
use App\Models\Equipe;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class PublicationController extends Controller
{
    /**
     * Liste des publications (professeur)
     */
    public function index()
    {
        $professeur = auth()->guard('professeur')->user();
        $publications = $professeur->publications()->latest()->paginate(10);
        
        return view('professor.publications', compact('publications'));
    }

    /**
     * Liste des publications (admin)
     */
    public function adminIndex(Request $request)
    {
        $query = Publication::with(['auteurPrincipal', 'auteurPrincipal.equipe', 'coAuteurs']);

        // Recherche
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('titre', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('domaine', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('resume', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhereHas('auteurPrincipal', function($authorQuery) use ($searchTerm) {
                      $authorQuery->where('nom', 'LIKE', '%' . $searchTerm . '%')
                                  ->orWhere('prenom', 'LIKE', '%' . $searchTerm . '%');
                  });
            });
        }

        // Filtres
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        if ($request->has('domaine') && !empty($request->domaine)) {
            $query->where('domaine', $request->domaine);
        }

        if ($request->has('annee') && !empty($request->annee)) {
            $query->where('annee', $request->annee);
        }

        if ($request->has('equipe') && !empty($request->equipe)) {
            $query->whereHas('auteurPrincipal', function($q) use ($request) {
                $q->where('equipe_id', $request->equipe);
            });
        }

        $publications = $query->latest()->paginate(15)->appends($request->query());

        return view('admin.publications', compact('publications'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $professeurs = Professor::all();
        $equipes = Equipe::all();
        
        if (auth()->guard('professeur')->check()) {
            return view('professor.ajout-publication', compact('professeurs'));
        } else {
            return view('admin.publications-create', compact('professeurs', 'equipes'));
        }
    }

    /**
     * Enregistrer une nouvelle publication
     */
    public function store(Request $request)
    {
        // Pour les professeurs, définir automatiquement l'auteur principal
        if (auth()->guard('professeur')->check() && !$request->has('auteur_principal_id')) {
            $request->merge(['auteur_principal_id' => auth()->guard('professeur')->id()]);
        }

        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'type' => 'required|in:Article,Conférence,Chapitre,Thèse',
            'annee' => 'required|integer|min:2000|max:' . date('Y'),
            'domaine' => 'required|string|max:100',
            'resume' => 'required|string',
            'auteur_principal_id' => 'required|exists:professeurs,id',
            'co_auteurs' => 'nullable|array',
            'co_auteurs.*' => 'exists:professeurs,id',
            'lien_pdf' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Créer la publication
        $publication = Publication::create([
            'titre' => $request->titre,
            'type' => $request->type,
            'annee' => $request->annee,
            'domaine' => $request->domaine,
            'resume' => $request->resume,
            'auteur_principal_id' => $request->auteur_principal_id,
        ]);

        \Log::info('Publication créée avec succès', ['id' => $publication->id]);

        // Ajouter les co-auteurs
        if ($request->has('co_auteurs')) {
            $publication->coAuteurs()->attach($request->co_auteurs);
        }

        // Gérer le fichier PDF
        if ($request->hasFile('lien_pdf')) {
            $path = $request->file('lien_pdf')->store('publications', 'public');
            $publication->update(['lien_pdf' => $path]);
        }

        // Redirection selon le rôle
        if (auth()->guard('professeur')->check()) {
            return redirect()->route('professor.publications.index')
                ->with('success', 'Publication créée avec succès !');
        } else {
            return redirect()->route('admin.publications.index')
                ->with('success', 'Publication créée avec succès !');
        }
    }

    /**
     * Afficher les détails d'une publication
     */
    public function show($id)
    {
        $publication = Publication::with(['auteurPrincipal', 'coAuteurs', 'auteurPrincipal.equipe'])
            ->findOrFail($id);
        
        return view('shared.publication-show', compact('publication'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $publication = Publication::with('coAuteurs')->findOrFail($id);
        $professeurs = Professor::all();
        
        if (auth()->guard('professeur')->check()) {
            return view('professor.publications-edit', compact('publication', 'professeurs'));
        } else {
            return view('admin.publications-edit', compact('publication', 'professeurs'));
        }
    }

    /**
     * Mettre à jour une publication
     */
    public function update(Request $request, $id)
    {
        $publication = Publication::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'type' => 'required|in:Article,Conférence,Chapitre,Thèse',
            'annee' => 'required|integer|min:2000|max:' . date('Y'),
            'domaine' => 'required|string|max:100',
            'resume' => 'required|string',
            'auteur_principal_id' => 'required|exists:professeurs,id',
            'co_auteurs' => 'nullable|array',
            'co_auteurs.*' => 'exists:professeurs,id',
            'lien_pdf' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $publication->update([
            'titre' => $request->titre,
            'type' => $request->type,
            'annee' => $request->annee,
            'domaine' => $request->domaine,
            'resume' => $request->resume,
            'auteur_principal_id' => $request->auteur_principal_id,
        ]);

        // Debug: Log successful update
        \Log::info('Publication updated successfully:', $publication->toArray());

        // Mettre à jour les co-auteurs
        if ($request->has('co_auteurs')) {
            $publication->coAuteurs()->sync($request->co_auteurs);
        } else {
            $publication->coAuteurs()->detach();
        }

        // Gérer le fichier PDF
        if ($request->hasFile('lien_pdf')) {
            // Supprimer l'ancien fichier
            if ($publication->lien_pdf && Storage::disk('public')->exists($publication->lien_pdf)) {
                Storage::disk('public')->delete($publication->lien_pdf);
            }
            
            $path = $request->file('lien_pdf')->store('publications', 'public');
            $publication->update(['lien_pdf' => $path]);
        }

        // Redirection selon le rôle
        if (auth()->guard('professeur')->check()) {
            return redirect()->route('professor.publications.index')
                ->with('success', 'Publication mise à jour avec succès !');
        } else {
            return redirect()->route('admin.publications.index')
                ->with('success', 'Publication mise à jour avec succès !');
        }
    }

    /**
     * Supprimer une publication
     */
    public function destroy($id)
    {
        $publication = Publication::findOrFail($id);
        
        // Supprimer le fichier PDF
        if ($publication->lien_pdf && Storage::disk('public')->exists($publication->lien_pdf)) {
            Storage::disk('public')->delete($publication->lien_pdf);
        }
        
        $publication->delete();

        // Redirection selon le rôle
        if (auth()->guard('professeur')->check()) {
            return redirect()->route('professor.publications.index')
                ->with('success', 'Publication supprimée avec succès !');
        } else {
            return redirect()->route('admin.publications.index')
                ->with('success', 'Publication supprimée avec succès !');
        }
    }

    /**
     * Bibliothèque (vue commune)
     */
    public function bibliotheque()
    {
        $publications = Cache::remember('bibliotheque_publications', 3600, function () {
            return Publication::with(['auteurPrincipal', 'coAuteurs'])
                ->latest()
                ->paginate(12);
        });

        return view('shared.bibliotheque', compact('publications'));
    }

    /**
     * Télécharger le PDF d'une publication
     */
    public function downloadPDF($id)
    {
        $publication = Publication::findOrFail($id);
        
        if (!$publication->lien_pdf) {
            return redirect()->back()->with('error', 'PDF non disponible.');
        }

        $path = storage_path('app/public/' . $publication->lien_pdf);
        
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'Fichier non trouvé.');
        }

        return response()->download($path);
    }


}
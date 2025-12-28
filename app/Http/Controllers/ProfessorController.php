<?php

/**
 * Contrôleur pour la gestion des professeurs
 *
 * Ce contrôleur gère les opérations CRUD pour les professeurs,
 * ainsi que les fonctionnalités de profil et de recherche.
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Professor;
use App\Models\Equipe;
use App\Models\Publication;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Classe ProfessorController
 *
 * Gère les fonctionnalités liées à la gestion des professeurs
 */
class ProfessorController extends Controller
{
    /**
     * Affiche la liste des professeurs
     *
     * @return \Illuminate\View\View Vue de la liste des professeurs
     */
    public function index()
    {
        // Récupère les professeurs avec leur équipe, triés par date décroissante
        $professeurs = Professor::with('equipe')->latest()->paginate(10);
        // Retourne la vue avec la liste des professeurs
        return view('admin.professeurs', compact('professeurs'));
    }

    /**
     * Affiche le formulaire de création d'un professeur
     *
     * @return \Illuminate\View\View Vue du formulaire de création
     */
    public function create()
    {
        // Récupère toutes les équipes pour le formulaire
        $equipes = Equipe::all();
        // Retourne la vue du formulaire de création
        return view('admin.professeurs-create', compact('equipes'));
    }

    /**
     * Enregistre un nouveau professeur
     *
     * @param Request $request Les données du formulaire
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function store(Request $request)
    {
        // Log des données reçues pour debug
        \Log::info('Tentative de création de professeur', $request->all());

        // Validation des données d'entrée
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:professeurs,email',
            'password' => 'required|min:8|confirmed',
            'grade' => 'required|in:Docteur,Doctorant',
            'domaine' => 'nullable|string|max:100',
            'equipe_id' => 'nullable|exists:equipes,id',
        ]);

        // Retour en cas d'erreurs de validation
        if ($validator->fails()) {
            \Log::error('Erreurs de validation lors de la création du professeur', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Création du nouveau professeur
        $professor = Professor::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'grade' => $request->grade,
            'domaine' => $request->domaine,
            'equipe_id' => $request->equipe_id,
        ]);

        \Log::info('Professeur créé avec succès', ['id' => $professor->id]);

        // Redirection vers la liste avec message de succès
        return redirect()->route('admin.professeurs.index')
            ->with('success', 'Professeur créé avec succès !');
    }

    /**
     * Affiche les détails d'un professeur
     *
     * @param int $id L'identifiant du professeur
     * @return \Illuminate\View\View Vue des détails du professeur
     */
    public function show($id)
    {
        // Récupère le professeur avec ses relations
        $professeur = Professor::with(['equipe', 'publications', 'coPublications'])->findOrFail($id);

        // Calcul des statistiques
        $stats = [
            'publications' => $professeur->publications->count(),
            'coPublications' => $professeur->coPublications->count(),
            'totalPublications' => $professeur->publications->count() + $professeur->coPublications->count(),
        ];

        // Retourne la vue avec les détails et statistiques
        return view('admin.professeurs-show', compact('professeur', 'stats'));
    }

    /**
     * Affiche le formulaire d'édition d'un professeur
     *
     * @param int $id L'identifiant du professeur
     * @return \Illuminate\View\View Vue du formulaire d'édition
     */
    public function edit($id)
    {
        // Récupère le professeur et toutes les équipes
        $professeur = Professor::findOrFail($id);
        $equipes = Equipe::all();
        // Retourne la vue du formulaire d'édition
        return view('admin.professeurs-edit', compact('professeur', 'equipes'));
    }

    /**
     * Met à jour les informations d'un professeur
     *
     * @param Request $request Les données du formulaire
     * @param int $id L'identifiant du professeur
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function update(Request $request, $id)
    {
        // Récupère le professeur
        $professeur = Professor::findOrFail($id);

        // Validation des données d'entrée
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:professeurs,email,' . $professeur->id,
            'grade' => 'required|in:Docteur,Doctorant',
            'domaine' => 'nullable|string|max:100',
            'equipe_id' => 'nullable|exists:equipes,id',
        ]);

        // Retour en cas d'erreurs de validation
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Préparation des données à mettre à jour
        $data = [
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'grade' => $request->grade,
            'domaine' => $request->domaine,
            'equipe_id' => $request->equipe_id,
        ];

        // Mise à jour du mot de passe si fourni
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Mise à jour du professeur
        $professeur->update($data);

        // Redirection vers la liste avec message de succès
        return redirect()->route('admin.professeurs.index')
            ->with('success', 'Professeur mis à jour avec succès !');
    }

    /**
     * Supprime un professeur
     *
     * @param int $id L'identifiant du professeur
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function destroy($id)
    {
        // Récupère et supprime le professeur
        $professeur = Professor::findOrFail($id);
        $professeur->delete();

        // Redirection vers la liste avec message de succès
        return redirect()->route('admin.professeurs.index')
            ->with('success', 'Professeur supprimé avec succès !');
    }

    /**
     * Recherche des professeurs
     *
     * @param Request $request La requête contenant le terme de recherche
     * @return \Illuminate\View\View Vue des résultats de recherche
     */
    public function search(Request $request)
    {
        // Récupère le terme de recherche
        $query = $request->get('q');

        // Recherche dans plusieurs champs
        $professeurs = Professor::where('nom', 'LIKE', "%{$query}%")
            ->orWhere('prenom', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('domaine', 'LIKE', "%{$query}%")
            ->with('equipe')
            ->paginate(10);

        // Retourne la vue avec les résultats et le terme de recherche
        return view('admin.professeurs', compact('professeurs', 'query'));
    }

    /**
     * Affiche le profil du professeur connecté
     *
     * @return \Illuminate\View\View Vue du profil professeur
     */
    public function showProfile()
    {
        // Récupère le professeur connecté
        $professor = auth()->guard('professeur')->user();
        // Récupère toutes les équipes
        $equipes = Equipe::all();

        // Calcul des statistiques du professeur
        $stats = [
            'publications' => $professor->publications->count(),
            'coPublications' => $professor->coPublications->count(),
            'totalPublications' => $professor->publications->count() + $professor->coPublications->count(),
        ];

        // Retourne la vue du profil
        return view('professor.profil', compact('professor', 'equipes', 'stats'));
    }

    /**
     * Met à jour le profil du professeur connecté
     *
     * @param Request $request Les données du formulaire
     * @return \Illuminate\Http\RedirectResponse Redirection avec message de succès
     */
    public function updateProfile(Request $request)
    {
        // Récupère le professeur connecté
        $professeur = auth()->guard('professeur')->user();

        // Validation des données d'entrée
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:professeurs,email,' . $professeur->id,
            'grade' => 'required|in:Docteur,Doctorant',
            'domaine' => 'required|string|max:100',
            'equipe_id' => 'nullable|exists:equipes,id',
        ]);

        // Retour en cas d'erreurs de validation
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Préparation des données à mettre à jour
        $data = [
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'grade' => $request->grade,
            'domaine' => $request->domaine,
            'equipe_id' => $request->equipe_id,
        ];

        // Gestion de l'upload de photo
        if ($request->hasFile('photo')) {
            // Suppression de l'ancienne photo si elle existe
            if ($professeur->photo && file_exists(storage_path('app/public/' . $professeur->photo))) {
                unlink(storage_path('app/public/' . $professeur->photo));
            }

            // Stockage de la nouvelle photo
            $path = $request->file('photo')->store('professeurs', 'public');
            $data['photo'] = $path;
        }

        // Mise à jour du profil
        $professeur->update($data);

        // Mise à jour du mot de passe si fourni
        if ($request->filled('password')) {
            $professeur->update([
                'password' => Hash::make($request->password)
            ]);
        }

        // Retour avec message de succès
        return back()->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Télécharge le CV d'un professeur
     *
     * @param int $id L'identifiant du professeur
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse Téléchargement ou redirection avec erreur
     */
    public function downloadCV($id)
    {
        // Récupère le professeur
        $professeur = Professor::findOrFail($id);

        // Vérifie si le CV existe
        if (!$professeur->cv_path) {
            return redirect()->back()->with('error', 'CV non disponible.');
        }

        // Chemin complet du fichier
        $path = storage_path('app/public/' . $professeur->cv_path);

        // Vérifie si le fichier existe physiquement
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'Fichier non trouvé.');
        }

        // Retourne le téléchargement du fichier
        return response()->download($path);
    }

    /**
     * Récupère les statistiques d'un professeur en JSON
     *
     * @param int $id L'identifiant du professeur
     * @return \Illuminate\Http\JsonResponse Réponse JSON avec les statistiques
     */
    public function getStats($id)
    {
        // Récupère le professeur avec comptage des publications
        $professeur = Professor::withCount(['publications', 'coPublications'])->findOrFail($id);

        // Retourne les statistiques en JSON
        return response()->json([
            'success' => true,
            'data' => [
                'publications_count' => $professeur->publications_count,
                'co_publications_count' => $professeur->co_publications_count,
                'total_publications' => $professeur->publications_count + $professeur->co_publications_count,
            ]
        ]);
    }
}

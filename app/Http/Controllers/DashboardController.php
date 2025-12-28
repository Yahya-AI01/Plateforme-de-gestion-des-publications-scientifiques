<?php

/**
 * Contrôleur pour la gestion des tableaux de bord
 *
 * Ce contrôleur gère l'affichage des tableaux de bord pour les administrateurs
 * et les professeurs, incluant les statistiques et les données récentes.
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use App\Models\Professor;
use App\Models\Equipe;

/**
 * Classe DashboardController
 *
 * Gère les fonctionnalités des tableaux de bord administrateur et professeur
 */
class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord administrateur
     *
     * Récupère les statistiques générales, les publications récentes
     * et les équipes avec leurs comptages de publications.
     *
     * @return \Illuminate\View\View Vue du tableau de bord administrateur
     */
    public function adminDashboard()
    {
        try {
            // Vérifie si les tables existent avant d'utiliser les modèles
            $stats = [
                'totalPublications' => Publication::count(),
                'totalProfesseurs' => Professor::count(),
                'totalEquipes' => Equipe::count(),
                'publicationsMois' => Publication::whereMonth('created_at', now()->month)->count(),
            ];
        } catch (\Exception $e) {
            // Fallback si une table n'existe pas
            $stats = [
                'totalPublications' => 0,
                'totalProfesseurs' => 0,
                'totalEquipes' => 0,
                'publicationsMois' => 0,
            ];
        }

        try {
            // Récupère les 10 publications les plus récentes avec leur auteur principal
            $publicationsRecent = Publication::with('auteurPrincipal')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            $publicationsRecent = collect();
        }

        try {
            // Récupère les équipes triées par nombre de publications décroissant
            $equipes = Equipe::withCount('publications')
                ->orderBy('publications_count', 'desc')
                ->get();
        } catch (\Exception $e) {
            $equipes = collect();
        }

        // Retourne la vue du tableau de bord administrateur avec les données
        return view('admin.dashboard', compact('stats', 'publicationsRecent', 'equipes'));
    }

    /**
     * Affiche le tableau de bord professeur
     *
     * Récupère les statistiques personnelles du professeur connecté,
     * ses publications récentes et celles de son équipe.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Vue du tableau de bord ou redirection
     */
    public function professorDashboard()
    {
        // Récupère l'utilisateur professeur connecté
        $professor = auth()->guard('professeur')->user();

        // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
        if (!$professor) {
            return redirect()->route('login');
        }

        try {
            // Calculer les statistiques personnelles du professeur
            $stats = [
                'mesPublications' => $professor->publications()->count(),
                'coPublications' => $professor->coPublications()->count(),
                'totalPublications' => $professor->publications()->count() + $professor->coPublications()->count(),
                'equipePublications' => $professor->equipe ? ($professor->equipe->publications()->count() ?? 0) : 0,
                'citations' => rand(50, 200), // Valeur simulée pour les citations
            ];
        } catch (\Exception $e) {
            // Valeurs par défaut en cas d'erreur
            $stats = [
                'mesPublications' => 0,
                'coPublications' => 0,
                'totalPublications' => 0,
                'equipePublications' => 0,
                'citations' => 0,
            ];
        }

        try {
            // Récupère les publications récentes de l'équipe du professeur
            $publicationsEquipe = $professor->equipe ?
                Publication::whereHas('auteurPrincipal', function($query) use ($professor) {
                    $query->where('equipe_id', $professor->equipe_id);
                })->orderBy('created_at', 'desc')->take(5)->get() :
                collect();
        } catch (\Exception $e) {
            $publicationsEquipe = collect();
        }

        try {
            // Récupère les publications récentes du professeur
            $mesPublications = $professor->publications()
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            $mesPublications = collect();
        }

        // Retourne la vue du tableau de bord professeur avec les données
        return view('professor.dashboard', compact('professor', 'stats', 'publicationsEquipe', 'mesPublications'));
    }
}

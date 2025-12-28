<?php

/**
 * Contrôleur pour la gestion de l'authentification
 *
 * Ce contrôleur gère l'authentification des professeurs et administrateurs,
 * incluant la connexion, l'inscription et la déconnexion.
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Professor;
use App\Models\Admin;

/**
 * Classe AuthController
 *
 * Gère les fonctionnalités d'authentification pour professeurs et administrateurs
 */
class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion
     *
     * @return \Illuminate\View\View Vue du formulaire de connexion
     */
    public function showLoginForm()
    {
        // Retourne la vue du formulaire de connexion
        return view('auth.login');
    }

    /**
     * Traite la tentative de connexion
     *
     * Valide les identifiants et connecte l'utilisateur selon son rôle
     * (professeur ou administrateur).
     *
     * @param Request $request Les données du formulaire de connexion
     * @return \Illuminate\Http\RedirectResponse Redirection vers le tableau de bord ou retour avec erreurs
     */
    public function login(Request $request)
    {
        // Validation des données d'entrée
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:professeur,admin'
        ]);

        // Tentative de connexion pour un professeur
        if ($request->role === 'professeur') {
            if (Auth::guard('professeur')->attempt($request->only('email', 'password'), $request->remember)) {
                // Régénération de la session pour sécurité
                $request->session()->regenerate();
                // Redirection vers le tableau de bord professeur
                return redirect()->route('professor.dashboard');
            }
        // Tentative de connexion pour un administrateur
        } elseif ($request->role === 'admin') {
            if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->remember)) {
                // Régénération de la session pour sécurité
                $request->session()->regenerate();
                // Redirection vers le tableau de bord administrateur
                return redirect()->route('admin.dashboard');
            }
        }

        // Retour avec message d'erreur si les identifiants sont incorrects
        return back()->withErrors([
            'email' => 'Les identifiants ne correspondent pas.',
        ])->withInput($request->only('email', 'role'));
    }

    /**
     * Affiche le formulaire d'inscription
     *
     * @return \Illuminate\View\View Vue du formulaire d'inscription
     */
    public function showRegisterForm()
    {
        // Retourne la vue du formulaire d'inscription
        return view('auth.register');
    }

    /**
     * Traite l'inscription d'un nouveau professeur
     *
     * Valide les données et crée un nouveau compte professeur avec un code d'invitation.
     *
     * @param Request $request Les données du formulaire d'inscription
     * @return \Illuminate\Http\RedirectResponse Redirection vers le tableau de bord avec message de succès
     */
    public function register(Request $request)
    {
        // Validation des données d'entrée avec code d'invitation
        $data = $request->validate([
            'code' => 'required|in:INVITATION2024',
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:professeurs,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Création du nouveau professeur
        $professor = Professor::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'grade' => 'Doctorant',
            'domaine' => 'À définir',
        ]);

        // Connexion automatique du nouveau professeur
        Auth::guard('professeur')->login($professor);

        // Redirection vers le tableau de bord avec message de bienvenue
        return redirect()->route('professor.dashboard')
            ->with('success', 'Compte créé avec succès ! Bienvenue !');
    }

    /**
     * Déconnecte l'utilisateur actuel
     *
     * Déconnecte l'utilisateur du garde approprié (professeur ou admin)
     * et invalide la session.
     *
     * @param Request $request La requête HTTP
     * @return \Illuminate\Http\RedirectResponse Redirection vers la page d'accueil
     */
    public function logout(Request $request)
    {
        // Déconnexion selon le garde actif
        if (Auth::guard('professeur')->check()) {
            Auth::guard('professeur')->logout();
        } elseif (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        // Invalidation et régénération de la session pour sécurité
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirection vers la page d'accueil
        return redirect('/');
    }
}

<?php

/**
 * Contrôleur pour l'authentification des administrateurs
 *
 * Ce contrôleur gère l'authentification des administrateurs,
 * incluant la connexion, la création et la déconnexion.
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

/**
 * Classe AdminAuthController
 *
 * Gère les fonctionnalités d'authentification pour les administrateurs
 */
class AdminAuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion administrateur
     *
     * @return \Illuminate\View\View Vue du formulaire de connexion admin
     */
    public function showLoginForm()
    {
        // Retourne la vue du formulaire de connexion administrateur
        return view('auth.admin-login');
    }

    /**
     * Traite la tentative de connexion administrateur
     *
     * Valide les identifiants et connecte l'administrateur si valide.
     *
     * @param Request $request Les données du formulaire de connexion
     * @return \Illuminate\Http\RedirectResponse Redirection vers le tableau de bord ou retour avec erreurs
     */
    public function login(Request $request)
    {
        // Validation des données d'entrée
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Préparation des identifiants
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // Tentative de connexion avec le garde admin
        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            // Régénération de la session pour sécurité
            $request->session()->regenerate();
            // Redirection vers le tableau de bord administrateur
            return redirect()->route('admin.dashboard');
        }

        // Retour avec message d'erreur si les identifiants sont incorrects
        return back()->withErrors([
            'email' => 'Les identifiants administrateur ne correspondent pas.',
        ])->withInput($request->only('email'));
    }

    /**
     * Crée un nouvel administrateur (super admin seulement)
     *
     * Permet aux super administrateurs de créer de nouveaux comptes admin.
     *
     * @param Request $request Les données du formulaire de création
     * @return \Illuminate\Http\RedirectResponse Redirection avec message de succès
     */
    public function store(Request $request)
    {
        // Vérifier si l'utilisateur actuel est super admin
        if (!auth()->guard('admin')->user()->is_super_admin) {
            abort(403);
        }

        // Validation des données d'entrée
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Création du nouvel administrateur
        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Retour avec message de succès
        return back()->with('success', 'Administrateur créé avec succès !');
    }

    /**
     * Déconnecte l'administrateur actuel
     *
     * Déconnecte l'administrateur et invalide la session.
     *
     * @param Request $request La requête HTTP
     * @return \Illuminate\Http\RedirectResponse Redirection vers la page de connexion admin
     */
    public function logout(Request $request)
    {
        // Déconnexion du garde admin
        Auth::guard('admin')->logout();
        // Invalidation et régénération de la session pour sécurité
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirection vers la page de connexion administrateur
        return redirect('/admin/login');
    }
}

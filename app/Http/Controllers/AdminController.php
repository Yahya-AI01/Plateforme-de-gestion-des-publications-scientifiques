<?php

/**
 * Contrôleur pour la gestion des administrateurs
 *
 * Ce contrôleur gère les opérations CRUD (Créer, Lire, Mettre à jour, Supprimer)
 * pour les comptes administrateurs du système.
 */
namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Classe AdminController
 *
 * Gère les fonctionnalités liées à la gestion des administrateurs
 */
class AdminController extends Controller
{
    /**
     * Affiche la liste de tous les administrateurs
     *
     * @return \Illuminate\View\View Vue de la liste des administrateurs
     */
    public function index()
    {
        // Récupère tous les administrateurs
        $admins = Admin::all();
        // Retourne la vue avec la liste des administrateurs
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Affiche le formulaire de création d'un nouvel administrateur
     *
     * @return \Illuminate\View\View Vue du formulaire de création
     */
    public function create()
    {
        // Retourne la vue du formulaire de création
        return view('admin.admins.create');
    }

    /**
     * Enregistre un nouvel administrateur dans la base de données
     *
     * @param Request $request Les données du formulaire
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function store(Request $request)
    {
        // Validation des données d'entrée
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Création du nouvel administrateur
        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Redirection vers la liste avec message de succès
        return redirect()->route('admin.admins.index')->with('success', 'Admin créé avec succès.');
    }

    /**
     * Affiche les détails d'un administrateur spécifique
     *
     * @param Admin $admin L'instance de l'administrateur
     * @return \Illuminate\View\View Vue des détails de l'administrateur
     */
    public function show(Admin $admin)
    {
        // Retourne la vue avec les détails de l'administrateur
        return view('admin.admins.show', compact('admin'));
    }

    /**
     * Affiche le formulaire d'édition d'un administrateur
     *
     * @param Admin $admin L'instance de l'administrateur à modifier
     * @return \Illuminate\View\View Vue du formulaire d'édition
     */
    public function edit(Admin $admin)
    {
        // Retourne la vue du formulaire d'édition avec l'administrateur
        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Met à jour les informations d'un administrateur
     *
     * @param Request $request Les données du formulaire
     * @param Admin $admin L'instance de l'administrateur à mettre à jour
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function update(Request $request, Admin $admin)
    {
        // Validation des données d'entrée
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Mise à jour de l'administrateur
        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $admin->password,
        ]);

        // Redirection vers la liste avec message de succès
        return redirect()->route('admin.admins.index')->with('success', 'Admin mis à jour avec succès.');
    }

    /**
     * Supprime un administrateur de la base de données
     *
     * @param Admin $admin L'instance de l'administrateur à supprimer
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste avec message de succès
     */
    public function destroy(Admin $admin)
    {
        // Suppression de l'administrateur
        $admin->delete();
        // Redirection vers la liste avec message de succès
        return redirect()->route('admin.admins.index')->with('success', 'Admin supprimé avec succès.');
    }
}

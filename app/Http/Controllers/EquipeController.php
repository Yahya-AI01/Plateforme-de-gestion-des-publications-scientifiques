<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipe;
use App\Models\Professor;
use Illuminate\Support\Facades\Validator;

class EquipeController extends Controller
{
    /**
     * Afficher la liste des équipes
     */
    public function index()
    {
        $equipes = Equipe::with(['chef', 'membres'])->get();
        return view('admin.equipes', compact('equipes'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $professeurs = Professor::all();
        return view('admin.equipes-create', compact('professeurs'));
    }

    /**
     * Enregistrer une nouvelle équipe
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_equipe' => 'required|string|max:100|unique:equipes,nom_equipe',
            'description' => 'nullable|string',
            'id_chef_equipe' => 'nullable|exists:professeurs,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Equipe::create([
            'nom_equipe' => $request->nom_equipe,
            'description' => $request->description,
            'id_chef_equipe' => $request->id_chef_equipe,
        ]);

        return redirect()->route('admin.equipes.index')
            ->with('success', 'Équipe créée avec succès !');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $equipe = Equipe::findOrFail($id);
        $professeurs = Professor::all();
        
        return view('admin.equipes-edit', compact('equipe', 'professeurs'));
    }

    /**
     * Mettre à jour une équipe
     */
    public function update(Request $request, $id)
    {
        $equipe = Equipe::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'nom_equipe' => 'required|string|max:100|unique:equipes,nom_equipe,' . $equipe->id,
            'description' => 'nullable|string',
            'id_chef_equipe' => 'nullable|exists:professeurs,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $equipe->update([
            'nom_equipe' => $request->nom_equipe,
            'description' => $request->description,
            'id_chef_equipe' => $request->id_chef_equipe,
        ]);

        return redirect()->route('admin.equipes.index')
            ->with('success', 'Équipe mise à jour avec succès !');
    }

    /**
     * Supprimer une équipe
     */
    public function destroy($id)
    {
        $equipe = Equipe::findOrFail($id);
        $equipe->delete();

        return redirect()->route('admin.equipes.index')
            ->with('success', 'Équipe supprimée avec succès !');
    }

    /**
     * Ajouter un membre à une équipe
     */
    public function addMember(Request $request, $id)
    {
        $request->validate([
            'professeur_id' => 'required|exists:professeurs,id',
        ]);

        $professeur = Professor::findOrFail($request->professeur_id);
        $professeur->update(['equipe_id' => $id]);

        return back()->with('success', 'Membre ajouté à l\'équipe avec succès !');
    }

    /**
     * Retirer un membre d'une équipe
     */
    public function removeMember($equipeId, $professeurId)
    {
        $professeur = Professor::findOrFail($professeurId);
        
        if ($professeur->equipe_id == $equipeId) {
            $professeur->update(['equipe_id' => null]);
        }

        return back()->with('success', 'Membre retiré de l\'équipe avec succès !');
    }

    /**
     * Afficher les détails d'une équipe
     */
    public function show($id)
    {
        $equipe = Equipe::with(['chef', 'membres', 'membres.publications'])->findOrFail($id);
        $professeurs = Professor::where('equipe_id', null)->get();
        
        return view('admin.equipes-show', compact('equipe', 'professeurs'));
    }
}
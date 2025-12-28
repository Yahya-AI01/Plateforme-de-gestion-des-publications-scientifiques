@extends('layouts.app')

@section('title', 'Modifier Professeur - Admin EMSI')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-3 col-lg-2">
            @include('layouts.partials.admin-sidebar')
        </div>
        
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-edit text-primary me-2"></i>
                    Modifier le Professeur
                </h2>
                <a href="{{ route('admin.professeurs.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
            
            <div class="card shadow">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.professeurs.update', $professeur) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nom *</label>
                                <input type="text" class="form-control" name="nom" 
                                       value="{{ old('nom', $professeur->nom) }}" required>
                                @error('nom') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prénom *</label>
                                <input type="text" class="form-control" name="prenom" 
                                       value="{{ old('prenom', $professeur->prenom) }}" required>
                                @error('prenom') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" 
                                   value="{{ old('email', $professeur->email) }}" required>
                            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                                <input type="password" class="form-control" name="password">
                                @error('password') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Grade *</label>
                                <select class="form-select" name="grade" required>
                                    <option value="Doctorant" {{ old('grade', $professeur->grade) == 'Doctorant' ? 'selected' : '' }}>Doctorant</option>
                                    <option value="Docteur" {{ old('grade', $professeur->grade) == 'Docteur' ? 'selected' : '' }}>Docteur</option>
                                </select>
                                @error('grade') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Équipe</label>
                                <select class="form-select" name="equipe_id">
                                    <option value="">Aucune équipe</option>
                                    @foreach($equipes as $equipe)
                                    <option value="{{ $equipe->id }}" 
                                        {{ old('equipe_id', $professeur->equipe_id) == $equipe->id ? 'selected' : '' }}>
                                        {{ $equipe->nom_equipe }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('equipe_id') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Domaine de recherche</label>
                            <input type="text" class="form-control" name="domaine" 
                                   value="{{ old('domaine', $professeur->domaine) }}" 
                                   placeholder="Ex: Intelligence Artificielle, Mathématiques...">
                            @error('domaine') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-2"></i>Réinitialiser
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
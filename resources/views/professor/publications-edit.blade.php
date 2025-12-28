@extends('layouts.app')

@section('title', 'Modifier Publication - EMSI')

@section('content')
<div class="container mt-4">
    <nav class="navbar navbar-dark bg-primary shadow mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('professor.publications.index') }}">
                <i class="fas fa-arrow-left me-2"></i>
                Modifier une Publication
            </a>
        </div>
    </nav>
    
    <div class="card shadow">
        <div class="card-header">
            <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Modifier la Publication</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('professor.publications.update', $publication) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Titre *</label>
                    <input type="text" class="form-control" name="titre" required 
                           value="{{ old('titre', $publication->titre) }}">
                    @error('titre') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Type *</label>
                        <select class="form-select" name="type" required>
                            <option value="Article" {{ old('type', $publication->type) == 'Article' ? 'selected' : '' }}>Article</option>
                            <option value="Conférence" {{ old('type', $publication->type) == 'Conférence' ? 'selected' : '' }}>Conférence</option>
                            <option value="Chapitre" {{ old('type', $publication->type) == 'Chapitre' ? 'selected' : '' }}>Chapitre de livre</option>
                            <option value="Thèse" {{ old('type', $publication->type) == 'Thèse' ? 'selected' : '' }}>Thèse</option>
                        </select>
                        @error('type') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Année *</label>
                        <input type="number" class="form-control" name="annee" required 
                               min="2000" max="{{ date('Y') }}" value="{{ old('annee', $publication->annee) }}">
                        @error('annee') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Domaine *</label>
                    <input type="text" class="form-control" name="domaine" required 
                           value="{{ old('domaine', $publication->domaine) }}">
                    @error('domaine') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Résumé *</label>
                    <textarea class="form-control" name="resume" rows="4" required>{{ old('resume', $publication->resume) }}</textarea>
                    @error('resume') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Fichier PDF</label>
                    @if($publication->lien_pdf)
                    <div class="mb-2">
                        <a href="{{ asset('storage/' . $publication->lien_pdf) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-file-pdf me-1"></i>Voir le PDF actuel
                        </a>
                    </div>
                    @endif
                    <input type="file" class="form-control" name="lien_pdf" accept=".pdf">
                    <small class="text-muted">Laisser vide pour conserver le fichier actuel. Format PDF uniquement, max 5MB</small>
                    @error('lien_pdf') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                    </button>
                    <a href="{{ route('professor.publications.index') }}" class="btn btn-outline-secondary">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
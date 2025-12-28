@extends('layouts.app')

@section('title', 'Nouvelle Publication - Admin EMSI')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-3 col-lg-2">
            @include('layouts.partials.admin-sidebar')
        </div>
        
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-plus-circle text-primary me-2"></i>
                    Ajouter une Nouvelle Publication
                </h2>
                <a href="{{ route('admin.publications.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
            
            <div class="card shadow">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.publications.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Titre *</label>
                            <input type="text" class="form-control" name="titre" required 
                                   placeholder="Titre complet de la publication">
                            @error('titre') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type *</label>
                                <select class="form-select" name="type" required>
                                    <option value="">Sélectionner un type</option>
                                    <option value="Article">Article</option>
                                    <option value="Conférence">Conférence</option>
                                    <option value="Chapitre">Chapitre de livre</option>
                                    <option value="Thèse">Thèse</option>
                                </select>
                                @error('type') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Année *</label>
                                <input type="number" class="form-control" name="annee" required 
                                       min="2000" max="{{ date('Y') }}" value="{{ date('Y') }}">
                                @error('annee') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Domaine *</label>
                                <input type="text" class="form-control" name="domaine" required 
                                       placeholder="Ex: Intelligence Artificielle, Mathématiques...">
                                @error('domaine') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Auteur Principal *</label>
                                <select class="form-select" name="auteur_principal_id" required>
                                    <option value="">Sélectionner un auteur</option>
                                    @foreach($professeurs as $professeur)
                                    <option value="{{ $professeur->id }}">
                                        {{ $professeur->prenom }} {{ $professeur->nom }} ({{ $professeur->grade }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('auteur_principal_id') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Co-auteurs</label>
                            <select class="form-select" name="co_auteurs[]" multiple>
                                @foreach($professeurs as $professeur)
                                <option value="{{ $professeur->id }}">
                                    {{ $professeur->prenom }} {{ $professeur->nom }} ({{ $professeur->grade }})
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs co-auteurs</small>
                            @error('co_auteurs') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Résumé *</label>
                            <textarea class="form-control" name="resume" rows="5" required 
                                      placeholder="Résumé de la publication..."></textarea>
                            @error('resume') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Fichier PDF</label>
                            <input type="file" class="form-control" name="lien_pdf" accept=".pdf">
                            <small class="text-muted">Format PDF uniquement, max 5MB</small>
                            @error('lien_pdf') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-2"></i>Réinitialiser
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer la publication
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialiser les sélecteurs multiples
        $('select[multiple]').select2({
            placeholder: "Sélectionner les co-auteurs",
            allowClear: true
        });
    });
</script>
@endpush
@endsection
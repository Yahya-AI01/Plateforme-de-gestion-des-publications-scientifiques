@extends('layouts.app')

@section('title', 'Modifier Publication - Admin EMSI')

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
                    Modifier la Publication
                </h2>
                <a href="{{ route('admin.publications.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
            
            <div class="card shadow">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.publications.update', $publication) }}" enctype="multipart/form-data">
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
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Domaine *</label>
                                <input type="text" class="form-control" name="domaine" required 
                                       value="{{ old('domaine', $publication->domaine) }}">
                                @error('domaine') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Auteur Principal *</label>
                                <select class="form-select" name="auteur_principal_id" required>
                                    @foreach($professeurs as $professeur)
                                    <option value="{{ $professeur->id }}" 
                                        {{ old('auteur_principal_id', $publication->auteur_principal_id) == $professeur->id ? 'selected' : '' }}>
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
                                <option value="{{ $professeur->id }}" 
                                    {{ in_array($professeur->id, old('co_auteurs', $publication->coAuteurs->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $professeur->prenom }} {{ $professeur->nom }} ({{ $professeur->grade }})
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs co-auteurs</small>
                            @error('co_auteurs') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Résumé *</label>
                            <textarea class="form-control" name="resume" rows="5" required>{{ old('resume', $publication->resume) }}</textarea>
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
@extends('layouts.app')

@section('title', 'Ajouter Publication - EMSI')

@section('content')
<div class="container mt-4">
    <nav class="navbar navbar-dark bg-primary shadow mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('professor.publications.index') }}">
                <i class="fas fa-arrow-left me-2"></i>
                Ajouter une Publication
            </a>
        </div>
    </nav>
    
    <div class="card shadow">
        <div class="card-header">
            <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Nouvelle Publication</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('professor.publications.store') }}" enctype="multipart/form-data" id="publicationForm">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Titre *</label>
                    <input type="text" class="form-control" name="titre" required id="titre">
                    @error('titre') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Type *</label>
                        <select class="form-select" name="type" id="type" required>
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
                        <input type="number" class="form-control" name="annee" id="annee" 
                               required min="2000" max="{{ date('Y') }}" value="{{ date('Y') }}">
                        @error('annee') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Domaine *</label>
                    <input type="text" class="form-control" name="domaine" required 
                           placeholder="Ex: Intelligence Artificielle, Mathématiques...">
                    @error('domaine') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Résumé *</label>
                    <textarea class="form-control" name="resume" rows="4" required id="resume"></textarea>
                    @error('resume') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Fichier PDF</label>
                    <input type="file" class="form-control" name="lien_pdf" id="fichier" accept=".pdf">
                    <small class="text-muted">Format PDF uniquement, max 5MB</small>
                    @error('lien_pdf') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg" onclick="publier()">
                        <i class="fas fa-paper-plane me-2"></i>Publier
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="annuler()">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function publier() {
        // Validation simple
        if (document.getElementById('titre').value === '') {
            alert('Veuillez saisir un titre');
            return false;
        }
        if (document.getElementById('type').value === '') {
            alert('Veuillez sélectionner un type');
            return false;
        }
        if (document.getElementById('resume').value === '') {
            alert('Veuillez saisir un résumé');
            return false;
        }
        
        // Soumettre le formulaire
        document.getElementById('publicationForm').submit();
    }
    
    function annuler() {
        if (confirm('Annuler la création de la publication ?')) {
            window.location.href = "{{ route('professor.publications.index') }}";
        }
    }
    
    // Vérification de la taille du fichier
    document.getElementById('fichier').addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            if (file.type !== 'application/pdf') {
                alert('Seuls les fichiers PDF sont autorisés');
                this.value = '';
                return;
            }
            if (file.size > 5 * 1024 * 1024) { // 5MB
                alert('Le fichier ne doit pas dépasser 5MB');
                this.value = '';
                return;
            }
        }
    });
</script>
@endpush
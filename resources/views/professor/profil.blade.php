@extends('layouts.app')

@section('title', 'Mon Profil - EMSI')

@section('content')
<div class="container mt-4">
    <nav class="navbar navbar-dark bg-primary shadow mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('professor.dashboard') }}">
                <i class="fas fa-arrow-left me-2"></i>
                Mon Profil
            </a>
        </div>
    </nav>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <img src="{{ $professor->photo ? asset('storage/' . $professor->photo) : 'https://via.placeholder.com/150' }}" 
                         class="rounded-circle mb-3" width="150" height="150" alt="Photo de profil">
                    <h4>{{ $professor->grade }}. {{ $professor->prenom }} {{ $professor->nom }}</h4>
                    <p class="text-muted">
                        @if($professor->equipe)
                        Équipe {{ $professor->equipe->nom_equipe }}
                        @else
                        Aucune équipe
                        @endif
                    </p>
                    
                    <form method="POST" action="{{ route('professor.profile.update') }}" enctype="multipart/form-data" id="photoForm">
                        @csrf
                        @method('PUT')
                        <div class="input-group mb-3">
                            <input type="file" class="form-control form-control-sm" name="photo" 
                                   accept="image/*" id="photoInput" style="display: none;">
                            <button class="btn btn-primary btn-sm w-100" type="button" onclick="document.getElementById('photoInput').click()">
                                <i class="fas fa-camera me-1"></i>Changer photo
                            </button>
                        </div>
                    </form>
                    
                    <!-- Stats du profil -->
                    <div class="mt-4">
                        <div class="row text-center">
                            <div class="col-6">
                                <h5 class="text-primary">{{ $stats['publications'] ?? 0 }}</h5>
                                <small class="text-muted">Publications</small>
                            </div>
                            <div class="col-6">
                                <h5 class="text-success">{{ $stats['coPublications'] ?? 0 }}</h5>
                                <small class="text-muted">Co-publications</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">Informations Personnelles</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('professor.profile.update') }}" id="profilForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nom *</label>
                                <input type="text" class="form-control" name="nom" 
                                       value="{{ old('nom', $professor->nom) }}" required id="nom">
                                @error('nom') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prénom *</label>
                                <input type="text" class="form-control" name="prenom" 
                                       value="{{ old('prenom', $professor->prenom) }}" required id="prenom">
                                @error('prenom') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" 
                                   value="{{ old('email', $professor->email) }}" required id="email">
                            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Grade *</label>
                                <select class="form-select" name="grade" required id="grade">
                                    <option value="Doctorant" {{ old('grade', $professor->grade) == 'Doctorant' ? 'selected' : '' }}>Doctorant</option>
                                    <option value="Docteur" {{ old('grade', $professor->grade) == 'Docteur' ? 'selected' : '' }}>Docteur</option>
                                </select>
                                @error('grade') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Équipe</label>
                                <select class="form-select" name="equipe_id" id="equipe_id">
                                    <option value="">Aucune équipe</option>
                                    @foreach($equipes as $equipe)
                                    <option value="{{ $equipe->id }}" 
                                        {{ old('equipe_id', $professor->equipe_id) == $equipe->id ? 'selected' : '' }}>
                                        {{ $equipe->nom_equipe }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('equipe_id') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Domaine de recherche *</label>
                            <input type="text" class="form-control" name="domaine" 
                                   value="{{ old('domaine', $professor->domaine) }}" required id="domaine">
                            @error('domaine') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        
                        <!-- Section mot de passe (optionnel) -->
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Changer le mot de passe</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nouveau mot de passe</label>
                                        <input type="password" class="form-control" name="password">
                                        @error('password') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Confirmer le mot de passe</label>
                                        <input type="password" class="form-control" name="password_confirmation">
                                    </div>
                                </div>
                                <small class="text-muted">Laisser vide pour ne pas changer le mot de passe</small>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary" onclick="sauvegarderProfil()">
                                <i class="fas fa-save me-1"></i>Enregistrer
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-1"></i>Réinitialiser
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Section CV/Biographie (optionnel) -->
            <div class="card shadow mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Informations complémentaires</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Biographie académique</label>
                        <textarea class="form-control" rows="3" placeholder="Votre parcours académique, vos recherches..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">CV (PDF)</label>
                        <input type="file" class="form-control" accept=".pdf">
                        <small class="text-muted">Télécharger votre CV au format PDF</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Liens externes</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fab fa-google-scholar"></i></span>
                            <input type="url" class="form-control" placeholder="Lien Google Scholar">
                        </div>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fab fa-researchgate"></i></span>
                            <input type="url" class="form-control" placeholder="Lien ResearchGate">
                        </div>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fab fa-linkedin"></i></span>
                            <input type="url" class="form-control" placeholder="Lien LinkedIn">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function sauvegarderProfil() {
        // Validation simple
        if (document.getElementById('nom').value === '' || 
            document.getElementById('prenom').value === '' || 
            document.getElementById('email').value === '' || 
            document.getElementById('domaine').value === '') {
            alert('Veuillez remplir tous les champs obligatoires (*)');
            return false;
        }
        
        // Soumettre le formulaire
        document.getElementById('profilForm').submit();
    }
    
    function changerPhoto() {
        document.getElementById('photoInput').click();
    }
    
    // Auto-soumettre le formulaire photo quand un fichier est sélectionné
    document.getElementById('photoInput').addEventListener('change', function() {
        if (this.files.length > 0) {
            document.getElementById('photoForm').submit();
        }
    });
    
    // Aperçu de la photo avant upload (optionnel)
    document.getElementById('photoInput').addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector('.rounded-circle').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
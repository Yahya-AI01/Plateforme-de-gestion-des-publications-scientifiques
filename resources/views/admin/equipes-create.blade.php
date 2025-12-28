@extends('layouts.app')

@section('title', 'Créer une Équipe - Admin EMSI')

@push('styles')
<style>
    .form-container {
        max-width: 600px;
        margin: 0 auto;
    }
    .form-group label {
        font-weight: 600;
        color: #495057;
    }
    .btn-create {
        background: linear-gradient(135deg, #00c70aff 0%, #764ba2 100%);
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
</style>
@endpush

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2">
            @include('layouts.partials.admin-sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="form-container">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1">
                            <i class="fas fa-plus-circle text-primary me-2"></i>
                            Créer une Nouvelle Équipe
                        </h2>
                        <p class="text-muted mb-0">Ajoutez une nouvelle équipe de recherche à la plateforme</p>
                    </div>
                    <a href="{{ route('admin.equipes.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                </div>

                <!-- Form Card -->
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>
                            Informations de l'Équipe
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.equipes.store') }}" method="POST">
                            @csrf

                            <!-- Nom de l'équipe -->
                            <div class="mb-3">
                                <label for="nom_equipe" class="form-label">
                                    <i class="fas fa-tag me-1"></i>Nom de l'Équipe <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('nom_equipe') is-invalid @enderror"
                                       id="nom_equipe" name="nom_equipe"
                                       value="{{ old('nom_equipe') }}" required>
                                @error('nom_equipe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Le nom doit être unique et descriptif de l'équipe.
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1"></i>Description
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="4"
                                          placeholder="Décrivez les objectifs et le domaine de recherche de l'équipe...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Chef d'équipe -->
                            <div class="mb-4">
                                <label for="id_chef_equipe" class="form-label">
                                    <i class="fas fa-user-tie me-1"></i>Chef d'Équipe
                                </label>
                                <select class="form-select @error('id_chef_equipe') is-invalid @enderror"
                                        id="id_chef_equipe" name="id_chef_equipe">
                                    <option value="">Aucun chef d'équipe</option>
                                    @foreach($professeurs as $professeur)
                                    <option value="{{ $professeur->id }}" {{ old('id_chef_equipe') == $professeur->id ? 'selected' : '' }}>
                                        {{ $professeur->prenom }} {{ $professeur->nom }} - {{ $professeur->grade }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_chef_equipe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Le chef d'équipe coordonne les activités de l'équipe. Vous pouvez l'ajouter plus tard.
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.equipes.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-create text-white">
                                    <i class="fas fa-save me-1"></i>Créer l'Équipe
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card mt-4 border-info">
                    <div class="card-body">
                        <h6 class="card-title text-info">
                            <i class="fas fa-info-circle me-2"></i>
                            À propos des équipes
                        </h6>
                        <p class="card-text mb-0">
                            Les équipes de recherche regroupent les professeurs travaillant sur des projets similaires.
                            Après la création, vous pourrez ajouter des membres et gérer leurs publications.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-generate slug or something if needed
        // For now, just basic form validation
        $('form').on('submit', function(e) {
            var nomEquipe = $('#nom_equipe').val().trim();
            if (!nomEquipe) {
                e.preventDefault();
                alert('Le nom de l\'équipe est obligatoire.');
                $('#nom_equipe').focus();
            }
        });
    });
</script>
@endpush

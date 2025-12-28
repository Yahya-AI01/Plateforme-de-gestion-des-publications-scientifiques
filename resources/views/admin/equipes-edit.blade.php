@extends('layouts.app')

@section('title', 'Modifier l\'Équipe - Admin EMSI')

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
    .btn-update {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-update:hover {
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
                            <i class="fas fa-edit text-warning me-2"></i>
                            Modifier l'Équipe
                        </h2>
                        <p class="text-muted mb-0">Mettez à jour les informations de l'équipe "{{ $equipe->nom_equipe }}"</p>
                    </div>
                    <a href="{{ route('admin.equipes.show', $equipe) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour aux détails
                    </a>
                </div>

                <!-- Form Card -->
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2"></i>
                            Modifier les Informations
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.equipes.update', $equipe) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Nom de l'équipe -->
                            <div class="mb-3">
                                <label for="nom_equipe" class="form-label">
                                    <i class="fas fa-tag me-1"></i>Nom de l'Équipe <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('nom_equipe') is-invalid @enderror"
                                       id="nom_equipe" name="nom_equipe"
                                       value="{{ old('nom_equipe', $equipe->nom_equipe) }}" required>
                                @error('nom_equipe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1"></i>Description
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="4"
                                          placeholder="Décrivez les objectifs et le domaine de recherche de l'équipe...">{{ old('description', $equipe->description) }}</textarea>
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
                                    <option value="{{ $professeur->id }}" {{ old('id_chef_equipe', $equipe->id_chef_equipe) == $professeur->id ? 'selected' : '' }}>
                                        {{ $professeur->prenom }} {{ $professeur->nom }} - {{ $professeur->grade }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_chef_equipe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Le chef d'équipe coordonne les activités de l'équipe.
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.equipes.show', $equipe) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-update text-white">
                                    <i class="fas fa-save me-1"></i>Mettre à Jour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Current Members Info -->
                @if($equipe->membres->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Membres Actuels ({{ $equipe->membres->count() }})
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($equipe->membres as $membre)
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-circle text-primary me-2"></i>
                                    <span>{{ $membre->prenom }} {{ $membre->nom }}</span>
                                    @if($equipe->chef && $equipe->chef->id == $membre->id)
                                    <span class="badge bg-success ms-2">Chef</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.equipes.show', $equipe) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-cog me-1"></i>Gérer les membres
                            </a>
                        </div>
                    </div>
                </div>
                @endif
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

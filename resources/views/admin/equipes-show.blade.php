@extends('layouts.app')

@section('title', 'Détails Équipe - Admin EMSI')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-3 col-lg-2">
            @include('layouts.partials.admin-sidebar')
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-users text-primary me-2"></i>
                    Détails de l'Équipe
                </h2>
                <a href="{{ route('admin.equipes.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>

            <div class="row">
                <!-- Informations de l'équipe -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations</h5>
                        </div>
                        <div class="card-body">
                            <h4 class="mb-3">{{ $equipe->nom_equipe }}</h4>

                            @if($equipe->description)
                            <p class="text-muted">{{ $equipe->description }}</p>
                            @endif

                            <hr>

                            <div class="mb-3">
                                <strong>Chef d'équipe:</strong><br>
                                @if($equipe->chef)
                                    <div class="d-flex align-items-center mt-2">
                                        <img src="{{ $equipe->chef->photo ? asset('storage/' . $equipe->chef->photo) : 'https://via.placeholder.com/40' }}"
                                             class="rounded-circle me-2" width="40" height="40" alt="Photo">
                                        <div>
                                            <strong>{{ $equipe->chef->prenom }} {{ $equipe->chef->nom }}</strong><br>
                                            <small class="text-muted">{{ $equipe->chef->email }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Non assigné</span>
                                @endif
                            </div>

                            <div class="mb-3">
                                <strong>Membres:</strong>
                                <span class="badge bg-primary ms-2">{{ $equipe->membres->count() }}</span>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.equipes.edit', $equipe) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit me-1"></i>Modifier
                                </a>
                                <form action="{{ route('admin.equipes.destroy', $equipe) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette équipe ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash me-1"></i>Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Membres de l'équipe -->
                <div class="col-md-8">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-user-friends me-2"></i>Membres de l'Équipe</h5>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                                <i class="fas fa-plus me-1"></i>Ajouter un membre
                            </button>
                        </div>
                        <div class="card-body">
                            @if($equipe->membres->isEmpty())
                            <div class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun membre dans cette équipe</p>
                            </div>
                            @else
                            <div class="row">
                                @foreach($equipe->membres as $membre)
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $membre->photo ? asset('storage/' . $membre->photo) : 'https://via.placeholder.com/50' }}"
                                                     class="rounded-circle me-3" width="50" height="50" alt="Photo">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $membre->prenom }} {{ $membre->nom }}</h6>
                                                    <p class="mb-1 text-muted">{{ $membre->email }}</p>
                                                    <small class="text-muted">{{ $membre->grade }} - {{ $membre->domaine }}</small>
                                                </div>
                                                <form action="{{ route('admin.equipes.remove-member', [$equipe, $membre]) }}" method="POST" class="ms-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                            onclick="return confirm('Retirer ce membre de l\'équipe ?')"
                                                            title="Retirer de l'équipe">
                                                        <i class="fas fa-user-minus"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Publications des membres -->
                    <div class="card shadow">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-book me-2"></i>Publications des Membres</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $publications = collect();
                                foreach($equipe->membres as $membre) {
                                    $publications = $publications->merge($membre->publications);
                                }
                                $publications = $publications->unique('id');
                            @endphp

                            @if($publications->isEmpty())
                            <div class="text-center py-4">
                                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucune publication pour les membres de cette équipe</p>
                            </div>
                            @else
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Titre</th>
                                            <th>Auteur</th>
                                            <th>Type</th>
                                            <th>Année</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($publications->take(10) as $publication)
                                        <tr>
                                            <td>{{ Str::limit($publication->titre, 40) }}</td>
                                            <td>{{ $publication->auteurPrincipal->prenom }} {{ $publication->auteurPrincipal->nom }}</td>
                                            <td><span class="badge bg-info">{{ $publication->type }}</span></td>
                                            <td>{{ $publication->annee }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if($publications->count() > 10)
                                <p class="text-muted text-center">Et {{ $publications->count() - 10 }} autres publications...</p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter un membre -->
<div class="modal fade" id="addMemberModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Membre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.equipes.add-member', $equipe) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Sélectionner un professeur</label>
                        <select name="professeur_id" class="form-select" required>
                            <option value="">Choisir un professeur...</option>
                            @foreach($professeurs as $professeur)
                            <option value="{{ $professeur->id }}">
                                {{ $professeur->prenom }} {{ $professeur->nom }} - {{ $professeur->grade }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

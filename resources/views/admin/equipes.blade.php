@extends('layouts.app')

@section('title', 'Gestion des Équipes - Admin EMSI')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-3 col-lg-2">
            @include('layouts.partials.admin-sidebar')
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-users-cog text-primary me-2"></i>
                    Gestion des Équipes
                </h2>
                <a href="{{ route('admin.equipes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nouvelle Équipe
                </a>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des Équipes</h5>
                </div>
                <div class="card-body">
                    @if($equipes->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucune équipe créée</h5>
                        <p class="text-muted">Commencez par créer votre première équipe.</p>
                        <a href="{{ route('admin.equipes.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Créer la première équipe
                        </a>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nom de l'Équipe</th>
                                    <th>Chef d'Équipe</th>
                                    <th>Membres</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($equipes as $equipe)
                                <tr>
                                    <td>
                                        <strong>{{ $equipe->nom_equipe }}</strong>
                                    </td>
                                    <td>
                                        @if($equipe->chef)
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $equipe->chef->photo ? asset('storage/' . $equipe->chef->photo) : 'https://via.placeholder.com/32' }}"
                                                     class="rounded-circle me-2" width="32" height="32" alt="Photo">
                                                {{ $equipe->chef->prenom }} {{ $equipe->chef->nom }}
                                            </div>
                                        @else
                                            <span class="text-muted">Non assigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $equipe->membres->count() }} membres</span>
                                    </td>
                                    <td>
                                        @if($equipe->description)
                                            <span title="{{ $equipe->description }}">
                                                {{ Str::limit($equipe->description, 50) }}
                                            </span>
                                        @else
                                            <span class="text-muted">Aucune description</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.equipes.show', $equipe) }}"
                                               class="btn btn-sm btn-outline-info"
                                               title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.equipes.edit', $equipe) }}"
                                               class="btn btn-sm btn-outline-warning"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.equipes.destroy', $equipe) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette équipe ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

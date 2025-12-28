@extends('layouts.app')

@section('title', 'Gestion des Professeurs - Admin EMSI')

@push('styles')
<style>
    .stats-card {
        transition: transform 0.3s;
        border-radius: 10px;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .profile-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
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
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-users text-primary me-2"></i>
                    Gestion des Professeurs
                </h2>
                <button class="btn btn-primary" id="addProfessorBtn" data-bs-toggle="modal" data-bs-target="#addProfessorModal">
                    <i class="fas fa-plus me-2"></i>Ajouter un professeur
                </button>
            </div>

            <!-- Search Bar -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.search.professeurs') }}" method="GET" id="searchForm">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" name="q" id="searchInput" 
                                   placeholder="Rechercher un professeur par nom, email ou domaine..."
                                   value="{{ request('q') }}">
                            <button class="btn btn-outline-secondary" type="submit" id="searchBtn">
                                Rechercher
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Professors Table -->
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Liste des Professeurs
                        <span class="badge bg-primary ms-2">{{ $professeurs->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($professeurs->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun professeur trouvé.</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProfessorModal">
                            Ajouter le premier professeur
                        </button>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Photo</th>
                                    <th>Nom & Prénom</th>
                                    <th>Email</th>
                                    <th>Grade</th>
                                    <th>Équipe</th>
                                    <th>Domaine</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($professeurs as $professeur)
                                <tr>
                                    <td>{{ $professeur->id }}</td>
                                    <td>
                                        <img src="{{ $professeur->photo ? asset('storage/' . $professeur->photo) : 'https://via.placeholder.com/40' }}" 
                                             alt="Photo" class="profile-img">
                                    </td>
                                    <td>
                                        <strong>{{ $professeur->prenom }} {{ $professeur->nom }}</strong>
                                    </td>
                                    <td>{{ $professeur->email }}</td>
                                    <td>
                                        <span class="badge {{ $professeur->grade == 'Docteur' ? 'bg-success' : 'bg-info' }}">
                                            {{ $professeur->grade }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($professeur->equipe)
                                            <span class="badge bg-primary">{{ $professeur->equipe->nom_equipe }}</span>
                                        @else
                                            <span class="text-muted">Aucune</span>
                                        @endif
                                    </td>
                                    <td>{{ $professeur->domaine ?? 'Non défini' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.professeurs.show', $professeur) }}" 
                                               class="btn btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.professeurs.edit', $professeur) }}" 
                                               class="btn btn-outline-success" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.professeurs.destroy', $professeur) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" 
                                                        onclick="return confirm('Supprimer ce professeur ?')" title="Supprimer">
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
                    
                    <!-- Pagination -->
                    @if($professeurs->hasPages())
                    <div class="mt-3">
                        {{ $professeurs->links() }}
                    </div>
                    @endif
                    @endif
                </div>
            </div>

            <!-- Stats -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white stats-card">
                        <div class="card-body text-center">
                            <h4 class="card-title" id="totalProfessors">{{ $professeurs->count() }}</h4>
                            <p class="card-text">Professeurs totaux</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white stats-card">
                        <div class="card-body text-center">
                            @php
                                $doctorsCount = $professeurs->where('grade', 'Docteur')->count();
                            @endphp
                            <h4 class="card-title" id="doctorsCount">{{ $doctorsCount }}</h4>
                            <p class="card-text">Docteurs</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white stats-card">
                        <div class="card-body text-center">
                            @php
                                $phdStudentsCount = $professeurs->where('grade', 'Doctorant')->count();
                            @endphp
                            <h4 class="card-title" id="phdStudentsCount">{{ $phdStudentsCount }}</h4>
                            <p class="card-text">Doctorants</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white stats-card">
                        <div class="card-body text-center">
                            @php
                                $teamsCount = $professeurs->pluck('equipe_id')->unique()->filter()->count();
                            @endphp
                            <h4 class="card-title" id="teamsCount">{{ $teamsCount }}</h4>
                            <p class="card-text">Équipes représentées</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter un professeur -->
<div class="modal fade" id="addProfessorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Ajouter un nouveau professeur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.professeurs.store') }}" id="addProfessorForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom *</label>
                            <input type="text" class="form-control" name="nom" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom *</label>
                            <input type="text" class="form-control" name="prenom" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mot de passe *</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirmer le mot de passe *</label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Grade *</label>
                            <select class="form-select" name="grade" required>
                                <option value="Doctorant">Doctorant</option>
                                <option value="Docteur">Docteur</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Équipe</label>
                            <select class="form-select" name="equipe_id">
                                <option value="">Aucune équipe</option>
                                @foreach(App\Models\Equipe::all() as $equipe)
                                <option value="{{ $equipe->id }}">{{ $equipe->nom_equipe }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Domaine de recherche</label>
                        <input type="text" class="form-control" name="domaine" 
                               placeholder="Ex: Intelligence Artificielle, Mathématiques...">
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Filtrage côté client (optionnel)
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('table tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Gestion du formulaire modal - laisser le submit normal
        // $('#addProfessorForm').on('submit', function(e) {
        //     e.preventDefault();
        //     $(this).submit();
        // });

        // Stats dynamiques
        function updateStats() {
            // Les stats sont déjà calculées côté serveur
            console.log('Stats mises à jour');
        }
    });
</script>
@endpush
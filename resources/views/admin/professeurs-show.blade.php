@extends('layouts.app')

@section('title', 'Détails Professeur - Admin EMSI')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-3 col-lg-2">
            @include('layouts.partials.admin-sidebar')
        </div>
        
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-user-graduate text-primary me-2"></i>
                    Détails du Professeur
                </h2>
                <a href="{{ route('admin.professeurs.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
            
            <div class="row">
                <!-- Carte de profil -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <img src="{{ $professeur->photo ? asset('storage/' . $professeur->photo) : 'https://via.placeholder.com/150' }}" 
                                 class="rounded-circle mb-3" width="150" height="150" alt="Photo">
                            <h4>{{ $professeur->prenom }} {{ $professeur->nom }}</h4>
                            <p class="text-muted">{{ $professeur->grade }}</p>
                            
                            <div class="mb-3">
                                <span class="badge bg-primary">{{ $professeur->domaine }}</span>
                                @if($professeur->equipe)
                                <span class="badge bg-success">{{ $professeur->equipe->nom_equipe }}</span>
                                @endif
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="mailto:{{ $professeur->email }}" class="btn btn-outline-primary">
                                    <i class="fas fa-envelope me-2"></i>{{ $professeur->email }}
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stats -->
                    <div class="card shadow mt-4">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h5 class="text-primary">{{ $stats['publications'] }}</h5>
                                    <small class="text-muted">Publications</small>
                                </div>
                                <div class="col-6">
                                    <h5 class="text-success">{{ $stats['coPublications'] }}</h5>
                                    <small class="text-muted">Co-publications</small>
                                </div>
                            </div>
                            <hr>
                            <div class="text-center">
                                <h4 class="text-info">{{ $stats['totalPublications'] }}</h4>
                                <small class="text-muted">Publications totales</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Détails et publications -->
                <div class="col-md-8">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Nom complet :</strong> {{ $professeur->prenom }} {{ $professeur->nom }}</p>
                                    <p><strong>Email :</strong> {{ $professeur->email }}</p>
                                    <p><strong>Grade :</strong> 
                                        <span class="badge {{ $professeur->grade == 'Docteur' ? 'bg-success' : 'bg-info' }}">
                                            {{ $professeur->grade }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Domaine :</strong> {{ $professeur->domaine }}</p>
                                    <p><strong>Équipe :</strong> 
                                        @if($professeur->equipe)
                                            {{ $professeur->equipe->nom_equipe }}
                                        @else
                                            <span class="text-muted">Aucune équipe</span>
                                        @endif
                                    </p>
                                    <p><strong>Date d'inscription :</strong> 
                                        {{ $professeur->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Publications -->
                    <div class="card shadow">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-book me-2"></i>Publications</h5>
                            <span class="badge bg-primary">{{ $stats['totalPublications'] }} publications</span>
                        </div>
                        <div class="card-body">
                            @if($professeur->publications->isEmpty() && $professeur->coPublications->isEmpty())
                            <div class="text-center py-4">
                                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucune publication pour le moment</p>
                            </div>
                            @else
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Titre</th>
                                            <th>Type</th>
                                            <th>Année</th>
                                            <th>Rôle</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($professeur->publications as $publication)
                                        <tr>
                                            <td>{{ Str::limit($publication->titre, 50) }}</td>
                                            <td><span class="badge bg-info">{{ $publication->type }}</span></td>
                                            <td>{{ $publication->annee }}</td>
                                            <td><span class="badge bg-success">Auteur principal</span></td>
                                        </tr>
                                        @endforeach
                                        
                                        @foreach($professeur->coPublications as $publication)
                                        <tr>
                                            <td>{{ Str::limit($publication->titre, 50) }}</td>
                                            <td><span class="badge bg-info">{{ $publication->type }}</span></td>
                                            <td>{{ $publication->annee }}</td>
                                            <td><span class="badge bg-warning">Co-auteur</span></td>
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
    </div>
</div>
@endsection
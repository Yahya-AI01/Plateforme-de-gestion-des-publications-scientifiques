@extends('layouts.app')

@section('title', 'Dashboard Professeur - EMSI')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-3 slide-in-left">
            <div class="list-group">
                <a href="{{ route('professor.dashboard') }}" class="list-group-item list-group-item-action active btn-modern" style="background-color: #00a859; color: white;">
                    <i class="fas fa-home me-2"></i>Accueil
                </a>
                <a href="{{ route('professor.profile') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-user me-2"></i>Mon Profil
                </a>
                <a href="{{ route('professor.publications.create') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-plus me-2"></i>Ajouter Publication
                </a>
                <a href="{{ route('professor.publications.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-book me-2"></i>Mes Publications
                </a>
                <a href="{{ route('bibliotheque') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-book-open me-2"></i>Bibliothèque
                </a>
            </div>

            <!-- Stats rapides -->
            <div class="card mt-4 card-modern fade-in-up">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-chart-pie me-2"></i>Statistiques rapides</h6>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Publications</span>
                            <span class="badge badge-modern">{{ $stats['totalPublications'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Co-publications</span>
                            <span class="badge badge-modern">{{ $stats['coPublications'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Total</span>
                            <span class="badge badge-modern">{{ $stats['totalPublications'] + $stats['coPublications'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9 slide-in-right">
            <h2 class="fade-in-up"><i class="fas fa-user-graduate me-2" style="color: #00a859;"></i>Bienvenue, {{ $professor->grade }}. {{ $professor->prenom }} {{ $professor->nom }}</h2>
            <p class="text-muted slide-in-left">{{ $professor->domaine }} |
                @if($professor->equipe)
                Équipe {{ $professor->equipe->nom_equipe }}
                @else
                Aucune équipe assignée
                @endif
            </p>

            <div class="row mt-4">
                <div class="col-md-4 fade-in-up">
                    <div class="card text-white card-modern" style="background: linear-gradient(135deg, #00a859 0%, #008f4d 100%);">
                        <div class="card-body text-center">
                            <h3 id="mesPublications">{{ $stats['mesPublications'] ?? 0 }}</h3>
                            <p>Mes Publications</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 slide-in-left">
                    <div class="card text-white card-modern" style="background: linear-gradient(135deg, #00a859 0%, #008f4d 100%);">
                        <div class="card-body text-center">
                            <h3 id="equipePublications">{{ $stats['equipePublications'] ?? 0 }}</h3>
                            <p>Publications Équipe</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 slide-in-right">
                    <div class="card text-white card-modern" style="background: linear-gradient(135deg, #00a859 0%, #008f4d 100%);">
                        <div class="card-body text-center">
                            <h3 id="citations">{{ $stats['citations'] ?? 0 }}</h3>
                            <p>Citations</p>
                        </div>
                    </div>
                </div>
            </div>
            

            <!-- Publications récentes -->
            <div class="card mt-4 card-modern fade-in-up">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Mes publications récentes</h5>
                    <a href="{{ route('professor.publications.index') }}" class="btn btn-sm btn-modern">
                        Voir toutes
                    </a>
                </div>
                <div class="card-body">
                    @if($mesPublications->isEmpty())
                    <div class="text-center py-3">
                        <i class="fas fa-book fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Vous n'avez pas encore de publications</p>
                        <a href="{{ route('professor.publications.create') }}" class="btn btn-sm btn-modern">
                            Créer ma première publication
                        </a>
                    </div>
                    @else
                    <div class="list-group">
                        @foreach($mesPublications as $publication)
                        <div class="list-group-item list-group-item-action slide-in-left">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $publication->titre }}</h6>
                                <small class="text-muted">{{ $publication->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1 small text-muted">{{ Str::limit($publication->resume, 150) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small>
                                    <span class="badge badge-modern">{{ $publication->type }}</span>
                                    <span class="badge badge-modern">{{ $publication->annee }}</span>
                                </small>
                                <div>
                                    <a href="{{ route('professor.publications.edit', $publication) }}"
                                       class="btn btn-sm btn-outline-modern">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Publications de l'équipe -->
            @if($professor->equipe && $publicationsEquipe->isNotEmpty())
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Publications récentes de mon équipe</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($publicationsEquipe as $publication)
                        @if($publication->auteur_principal_id != $professor->id)
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $publication->titre }}</h6>
                                <small class="text-muted">{{ $publication->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1 small">
                                <i class="fas fa-user me-1"></i>
                                {{ $publication->auteurPrincipal->prenom }} {{ $publication->auteurPrincipal->nom }}
                            </p>
                            <small>
                                <span class="badge bg-info">{{ $publication->type }}</span>
                                <span class="badge bg-primary">{{ $publication->domaine }}</span>
                            </small>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Notifications -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Notifications</h5>
                </div>
                <div class="card-body">
                    <div class="list-group" id="notifications">
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Nouvelle publication dans votre équipe</h6>
                                <small>Il y a 2 heures</small>
                            </div>
                            <p class="mb-1 small">"{{ $publicationsEquipe->first()->titre ?? 'Nouvelle étude sur...' }}"</p>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Mise à jour de profil recommandée</h6>
                                <small>Hier</small>
                            </div>
                            <p class="mb-1 small">Complétez vos informations pour améliorer votre visibilité</p>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Conférence à venir</h6>
                                <small>Demain</small>
                            </div>
                            <p class="mb-1 small">Conférence internationale sur l'IA à 14h00</p>
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
    $(document).ready(function() {
        // Marquer les notifications comme lues
        $('.list-group-item').click(function() {
            $(this).addClass('list-group-item-light');
        });
        
        // Actualiser les stats (simulation)
        setInterval(function() {
            // Ici, vous pourriez faire un appel AJAX pour actualiser les stats
            console.log('Dashboard actualisé');
        }, 30000); // Toutes les 30 secondes
    });
</script>
@endpush
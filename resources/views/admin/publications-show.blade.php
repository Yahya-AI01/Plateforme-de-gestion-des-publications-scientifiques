@extends('layouts.app')

@section('title', 'Détails Publication - Admin EMSI')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-3 col-lg-2">
            @include('layouts.partials.admin-sidebar')
        </div>
        
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-book text-primary me-2"></i>
                    Détails de la Publication
                </h2>
                <a href="{{ route('admin.publications.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
            
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h4 class="mb-0">{{ $publication->titre }}</h4>
                    <div class="mt-2">
                        <span class="badge bg-info me-2">{{ $publication->type }}</span>
                        <span class="badge bg-primary me-2">{{ $publication->domaine }}</span>
                        <span class="badge bg-secondary">{{ $publication->annee }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5><i class="fas fa-align-left me-2"></i>Résumé</h5>
                            <p class="text-justify">{{ $publication->resume }}</p>
                            
                            <div class="mt-4">
                                <h5><i class="fas fa-users me-2"></i>Auteurs</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    <!-- Auteur principal -->
                                    <div class="card border-primary" style="width: 200px;">
                                        <div class="card-body p-3">
                                            <h6 class="card-title mb-1">
                                                <i class="fas fa-crown text-warning me-1"></i>
                                                {{ $publication->auteurPrincipal->prenom }} {{ $publication->auteurPrincipal->nom }}
                                            </h6>
                                            <p class="card-text small mb-1">{{ $publication->auteurPrincipal->grade }}</p>
                                            <p class="card-text small text-muted">Auteur principal</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Co-auteurs -->
                                    @foreach($publication->coAuteurs as $coAuteur)
                                    <div class="card" style="width: 200px;">
                                        <div class="card-body p-3">
                                            <h6 class="card-title mb-1">{{ $coAuteur->prenom }} {{ $coAuteur->nom }}</h6>
                                            <p class="card-text small mb-1">{{ $coAuteur->grade }}</p>
                                            <p class="card-text small text-muted">Co-auteur</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5><i class="fas fa-info-circle me-2"></i>Informations</h5>
                                    
                                    <div class="mb-3">
                                        <strong>ID Publication :</strong> {{ $publication->id }}
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Date de création :</strong><br>
                                        {{ $publication->created_at->format('d/m/Y H:i') }}
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Dernière modification :</strong><br>
                                        {{ $publication->updated_at->format('d/m/Y H:i') }}
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Équipe :</strong><br>
                                        @if($publication->auteurPrincipal->equipe)
                                            <span class="badge bg-primary">{{ $publication->auteurPrincipal->equipe->nom_equipe }}</span>
                                        @else
                                            <span class="text-muted">Non assignée</span>
                                        @endif
                                    </div>
                                    
                                    @if($publication->lien_pdf)
                                    <div class="mt-4">
                                        <a href="{{ asset('storage/' . $publication->lien_pdf) }}" 
                                           target="_blank" class="btn btn-danger w-100">
                                            <i class="fas fa-file-pdf me-2"></i>Voir le PDF
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="card border-0 bg-light mt-3">
                                <div class="card-body">
                                    <h5><i class="fas fa-cogs me-2"></i>Actions</h5>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.publications.edit', $publication) }}" 
                                           class="btn btn-primary">
                                            <i class="fas fa-edit me-2"></i>Modifier
                                        </a>
                                        <form action="{{ route('admin.publications.destroy', $publication) }}" 
                                              method="POST" class="d-grid">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('Supprimer cette publication ?')">
                                                <i class="fas fa-trash me-2"></i>Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
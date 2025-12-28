@extends('layouts.app')

@section('title', $publication->titre . ' - EMSI')

@section('content')
<div class="container mt-4">
    <nav class="navbar navbar-dark bg-primary shadow mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url()->previous() }}">
                <i class="fas fa-arrow-left me-2"></i>
                Retour
            </a>
            <div>
                @if($publication->lien_pdf)
                <a href="{{ asset('storage/' . $publication->lien_pdf) }}" 
                   target="_blank" class="btn btn-light btn-sm">
                    <i class="fas fa-file-pdf me-1"></i>Télécharger PDF
                </a>
                @endif
            </div>
        </div>
    </nav>
    
    <div class="card shadow">
        <div class="card-body">
            <h1 class="card-title">{{ $publication->titre }}</h1>
            
            <div class="mb-4">
                <span class="badge bg-info me-2">{{ $publication->type }}</span>
                <span class="badge bg-primary me-2">{{ $publication->domaine }}</span>
                <span class="badge bg-secondary">{{ $publication->annee }}</span>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-8">
                    <h4><i class="fas fa-align-left me-2"></i>Résumé</h4>
                    <p class="text-justify">{{ $publication->resume }}</p>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h5><i class="fas fa-users me-2"></i>Auteurs</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-crown text-warning me-2"></i>
                                    <strong>{{ $publication->auteurPrincipal->prenom }} {{ $publication->auteurPrincipal->nom }}</strong>
                                    <br>
                                    <small class="text-muted">Auteur principal - {{ $publication->auteurPrincipal->grade }}</small>
                                </li>
                                @foreach($publication->coAuteurs as $coAuteur)
                                <li class="mb-2">
                                    <i class="fas fa-user me-2"></i>
                                    {{ $coAuteur->prenom }} {{ $coAuteur->nom }}
                                    <br>
                                    <small class="text-muted">Co-auteur - {{ $coAuteur->grade }}</small>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h5><i class="fas fa-info-circle me-2"></i>Informations complémentaires</h5>
                            <p><strong>Date de publication :</strong> {{ $publication->created_at->format('d/m/Y') }}</p>
                            <p><strong>Équipe :</strong> 
                                @if($publication->auteurPrincipal->equipe)
                                    {{ $publication->auteurPrincipal->equipe->nom_equipe }}
                                @else
                                    <span class="text-muted">Non spécifiée</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h5><i class="fas fa-share-alt me-2"></i>Partager</h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary" onclick="sharePublication()">
                                    <i class="fas fa-share me-2"></i>Partager
                                </button>
                                <button class="btn btn-outline-secondary" onclick="printPublication()">
                                    <i class="fas fa-print me-2"></i>Imprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function sharePublication() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $publication->titre }}',
                text: 'Découvrez cette publication scientifique',
                url: window.location.href,
            })
            .then(() => console.log('Partage réussi'))
            .catch((error) => console.log('Erreur de partage:', error));
        } else {
            // Fallback pour les navigateurs qui ne supportent pas l'API Share
            alert('Copiez le lien pour partager : ' + window.location.href);
        }
    }
    
    function printPublication() {
        window.print();
    }
</script>
@endpush
@endsection
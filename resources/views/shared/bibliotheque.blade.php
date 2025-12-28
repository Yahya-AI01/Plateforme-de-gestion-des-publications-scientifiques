@extends('layouts.app')

@section('title', 'Bibliothèque - EMSI')

@section('content')
<div class="container mt-4">
    <nav class="navbar navbar-dark bg-primary shadow mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url()->previous() }}">
                <i class="fas fa-arrow-left me-2"></i>
                Bibliothèque des Publications
            </a>
        </div>
    </nav>
    
    <div class="mb-4">
        <form method="GET" action="{{ route('search.publications') }}" class="input-group">
            <input type="text" class="form-control" placeholder="Rechercher..." 
                   name="q" id="recherche" value="{{ request('q') }}">
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
    
    @if(request()->has('q') && request('q') != '')
    <div class="alert alert-info mb-4">
        <i class="fas fa-search me-2"></i>
        Résultats de recherche pour : <strong>"{{ request('q') }}"</strong>
        <a href="{{ route('bibliotheque') }}" class="float-end text-decoration-none">
            <i class="fas fa-times me-1"></i>Effacer la recherche
        </a>
    </div>
    @endif
    
    <div class="row" id="publicationsList">
        @if($publications->isEmpty())
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Aucune publication trouvée</h4>
                    <p class="text-muted">
                        @if(request()->has('q'))
                        Aucun résultat pour votre recherche.
                        @else
                        La bibliothèque est vide pour le moment.
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @else
        @foreach($publications as $publication)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm publication-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-info">{{ $publication->type }}</span>
                        <span class="badge bg-secondary">{{ $publication->annee }}</span>
                    </div>
                    
                    <h5 class="card-title">{{ Str::limit($publication->titre, 70) }}</h5>
                    
                    <p class="card-text text-muted small">
                        {{ Str::limit($publication->resume, 120) }}
                    </p>
                    
                    <div class="mt-3">
                        <p class="mb-1 small">
                            <i class="fas fa-user me-1"></i>
                            <strong>{{ $publication->auteurPrincipal->prenom }} {{ $publication->auteurPrincipal->nom }}</strong>
                        </p>
                        <p class="mb-2 small text-muted">
                            <i class="fas fa-tag me-1"></i>
                            {{ $publication->domaine }}
                        </p>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('publications.show', $publication) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>Détails
                        </a>
                        @if($publication->lien_pdf)
                        <a href="{{ asset('storage/' . $publication->lien_pdf) }}" 
                           target="_blank" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-file-pdf me-1"></i>PDF
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>
    
    <!-- Pagination -->
    @if($publications->hasPages())
    <div class="mt-4">
        {{ $publications->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
    function rechercher() {
        var query = document.getElementById('recherche').value;
        if (query.trim() !== '') {
            window.location.href = "{{ route('search.publications') }}?q=" + encodeURIComponent(query);
        }
    }
    
    // Recherche en temps réel (optionnel)
    $('#recherche').on('keyup', function(e) {
        if (e.key === 'Enter') {
            rechercher();
        }
    });
</script>
@endpush
@endsection
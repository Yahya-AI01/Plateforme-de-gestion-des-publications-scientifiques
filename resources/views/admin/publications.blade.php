@extends('layouts.app')

@section('title', 'Gestion des Publications - Admin EMSI')

@push('styles')
<style>
    .publication-card {
        transition: all 0.3s ease;
        border-left: 4px solid #00a859;
    }
    .publication-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    .badge-type {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
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
            <!-- Header avec Actions -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-book text-primary me-2"></i>
                    Gestion des Publications
                </h2>
                <div>
                    <a href="{{ route('admin.publications.create') }}" class="btn btn-success me-2" id="addPublicationBtn">
                        <i class="fas fa-plus me-2"></i>Nouvelle Publication
                    </a>
                    <button class="btn btn-outline-primary" id="exportPublicationsBtn">
                        <i class="fas fa-download me-2"></i>Exporter
                    </button>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.publications.index') }}" id="searchForm">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" name="q" id="searchInput" 
                                   placeholder="Rechercher par titre, auteur, mots-clés..."
                                   value="{{ request('q') }}">
                            <button class="btn btn-primary" type="submit" id="searchBtn">
                                <i class="fas fa-search me-1"></i>Rechercher
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Publications Table -->
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Liste des Publications
                        <span class="badge bg-primary ms-2">{{ $publications->total() }}</span>
                    </h5>
                    <div class="d-flex align-items-center">
                        <span class="me-2">Tri :</span>
                        <select class="form-select form-select-sm w-auto" id="sortSelect">
                            <option value="date-desc">Date ↓</option>
                            <option value="date-asc">Date ↑</option>
                            <option value="title-asc">Titre A-Z</option>
                            <option value="title-desc">Titre Z-A</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    @if($publications->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucune publication trouvée.</p>
                        <a href="{{ route('admin.publications.create') }}" class="btn btn-primary">
                            Ajouter la première publication
                        </a>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Auteur Principal</th>
                                    <th>Type</th>
                                    <th>Domaine</th>
                                    <th>Année</th>
                                    <th>Équipe</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="publicationsTable">
                                @foreach($publications as $publication)
                                <tr class="publication-card">
                                    <td>{{ $publication->id }}</td>
                                    <td>
                                        <strong>{{ Str::limit($publication->titre, 60) }}</strong>
                                        @if($publication->lien_pdf)
                                        <br><small><a href="{{ asset('storage/' . $publication->lien_pdf) }}" target="_blank">
                                            <i class="fas fa-file-pdf text-danger me-1"></i>PDF
                                        </a></small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($publication->auteurPrincipal)
                                        {{ $publication->auteurPrincipal->prenom }} {{ $publication->auteurPrincipal->nom }}
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-type 
                                            @if($publication->type == 'Article') bg-success
                                            @elseif($publication->type == 'Conférence') bg-info
                                            @elseif($publication->type == 'Chapitre') bg-warning
                                            @else bg-secondary @endif">
                                            {{ $publication->type }}
                                        </span>
                                    </td>
                                    <td>{{ $publication->domaine }}</td>
                                    <td>{{ $publication->annee }}</td>
                                    <td>
                                        @if($publication->auteurPrincipal && $publication->auteurPrincipal->equipe)
                                            <span class="badge bg-primary">
                                                {{ $publication->auteurPrincipal->equipe->nom_equipe }}
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.publications.show', $publication) }}" 
                                               class="btn btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.publications.edit', $publication) }}" 
                                               class="btn btn-outline-success" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.publications.destroy', $publication) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" 
                                                        onclick="return confirm('Supprimer cette publication ?')" title="Supprimer">
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
                    @if($publications->hasPages())
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Affichage de <span id="displayStart">{{ $publications->firstItem() }}</span> à 
                                <span id="displayEnd">{{ $publications->lastItem() }}</span> sur 
                                <span id="totalPublications">{{ $publications->total() }}</span> publications
                            </div>
                            <div>
                                @if($publications->onFirstPage())
                                <button class="btn btn-sm btn-outline-secondary" disabled>
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                @else
                                <a href="{{ $publications->previousPageUrl() }}" class="btn btn-sm btn-outline-secondary" id="prevPage">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                                @endif
                                
                                <span class="mx-2">Page <span id="currentPage">{{ $publications->currentPage() }}</span></span>
                                
                                @if($publications->hasMorePages())
                                <a href="{{ $publications->nextPageUrl() }}" class="btn btn-sm btn-outline-secondary" id="nextPage">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                                @else
                                <button class="btn btn-sm btn-outline-secondary" disabled>
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>

            <!-- Stats Rapides -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="card-title" id="totalPubStats">{{ $publications->total() }}</h4>
                                    <p class="card-text mb-0">Publications totales</p>
                                </div>
                                <i class="fas fa-book fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @php
                                        $articlesCount = $publications->where('type', 'Article')->count();
                                    @endphp
                                    <h4 class="card-title" id="articlesCount">{{ $articlesCount }}</h4>
                                    <p class="card-text mb-0">Articles scientifiques</p>
                                </div>
                                <i class="fas fa-file-alt fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @php
                                        $conferencesCount = $publications->where('type', 'Conférence')->count();
                                    @endphp
                                    <h4 class="card-title" id="conferencesCount">{{ $conferencesCount }}</h4>
                                    <p class="card-text mb-0">Conférences</p>
                                </div>
                                <i class="fas fa-microphone fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filtres Avancés -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-filter me-2"></i>Filtres Avancés</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm">
                    <div class="mb-3">
                        <label class="form-label">Domaine :</label>
                        <select class="form-select" id="filterDomaine">
                            <option value="">Tous les domaines</option>
                            <option value="IA">Intelligence Artificielle</option>
                            <option value="Informatique">Informatique</option>
                            <option value="Mathématiques">Mathématiques</option>
                            <option value="Génie Civil">Génie Civil</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type :</label>
                        <select class="form-select" id="filterType">
                            <option value="">Tous les types</option>
                            <option value="Article">Article</option>
                            <option value="Conférence">Conférence</option>
                            <option value="Chapitre">Chapitre de livre</option>
                            <option value="Thèse">Thèse</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Année :</label>
                        <select class="form-select" id="filterAnnee">
                            <option value="">Toutes les années</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Équipe :</label>
                        <select class="form-select" id="filterEquipe">
                            <option value="">Toutes les équipes</option>
                            @foreach(App\Models\Equipe::all() as $equipe)
                            <option value="{{ $equipe->id }}">{{ $equipe->nom_equipe }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" id="applyFilters">
                            <i class="fas fa-check me-1"></i>Appliquer les filtres
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="resetFilters">
                            <i class="fas fa-redo me-1"></i>Réinitialiser
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
        // Filtrage côté client
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('table tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
            
            // Mettre à jour le compteur
            updateCount();
        });

        // Tri
        $('#sortSelect').on('change', function() {
            var value = $(this).val();
            // Implémenter la logique de tri ici (ou recharger la page avec paramètres)
            console.log('Tri sélectionné:', value);
        });

        // Filtres avancés
        $('#applyFilters').on('click', function() {
            var domaine = $('#filterDomaine').val();
            var type = $('#filterType').val();
            var annee = $('#filterAnnee').val();
            var equipe = $('#filterEquipe').val();
            
            // Construire l'URL avec les filtres
            var url = new URL(window.location.href);
            if (domaine) url.searchParams.set('domaine', domaine);
            if (type) url.searchParams.set('type', type);
            if (annee) url.searchParams.set('annee', annee);
            if (equipe) url.searchParams.set('equipe', equipe);
            
            window.location.href = url.toString();
        });

        // Réinitialiser les filtres
        $('#resetFilters').on('click', function() {
            $('#filterDomaine, #filterType, #filterAnnee, #filterEquipe').val('');
            window.location.href = "{{ route('admin.publications.index') }}";
        });

        // Export
        $('#exportPublicationsBtn').on('click', function() {
            if(confirm('Exporter toutes les publications en CSV ?')) {
                window.location.href = "{{ route('admin.publications.index') }}?export=csv";
            }
        });

        function updateCount() {
            var visible = $('table tbody tr:visible').length;
            $('#totalPublications').text(visible);
        }
        
        // Initialiser le compteur
        updateCount();
    });
</script>
@endpush
@extends('layouts.app')

@section('title', 'Mes Publications - EMSI')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="fas fa-book text-primary me-2"></i>
            Mes Publications
        </h2>
        <a href="{{ route('professor.publications.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouvelle Publication
        </a>
    </div>
    
    @if($publications->isEmpty())
    <div class="card shadow">
        <div class="card-body text-center py-5">
            <i class="fas fa-book fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">Aucune publication</h4>
            <p class="text-muted">Vous n'avez pas encore publié d'article.</p>
            <a href="{{ route('professor.publications.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Créer ma première publication
            </a>
        </div>
    </div>
    @else
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Année</th>
                            <th>Domaine</th>
                            <th>PDF</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($publications as $publication)
                        <tr>
                            <td>
                                <strong>{{ Str::limit($publication->titre, 60) }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ Str::limit($publication->resume, 100) }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $publication->type }}</span>
                            </td>
                            <td>{{ $publication->annee }}</td>
                            <td>{{ $publication->domaine }}</td>
                            <td>
                                @if($publication->lien_pdf)
                                <a href="{{ asset('storage/' . $publication->lien_pdf) }}" 
                                   target="_blank" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('professor.publications.edit', $publication) }}" 
                                       class="btn btn-outline-primary" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('professor.publications.destroy', $publication) }}" 
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
            <div class="mt-3">
                {{ $publications->links() }}
            </div>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Stats -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body text-center">
                    <h4 class="card-title">{{ $publications->total() }}</h4>
                    <p class="card-text">Publications totales</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    @php
                        $articlesCount = $publications->where('type', 'Article')->count();
                    @endphp
                    <h4 class="card-title">{{ $articlesCount }}</h4>
                    <p class="card-text">Articles</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body text-center">
                    @php
                        $conferencesCount = $publications->where('type', 'Conférence')->count();
                    @endphp
                    <h4 class="card-title">{{ $conferencesCount }}</h4>
                    <p class="card-text">Conférences</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body text-center">
                    @php
                        $pdfCount = $publications->whereNotNull('lien_pdf')->count();
                    @endphp
                    <h4 class="card-title">{{ $pdfCount }}</h4>
                    <p class="card-text">Avec PDF</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Détails de l\'Équipe')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails de l'Équipe</h3>
                    <a href="{{ route('admin.equipes.index') }}" class="btn btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informations générales</h5>
                            <p><strong>ID:</strong> {{ $equipe->id }}</p>
                            <p><strong>Nom:</strong> {{ $equipe->nom }}</p>
                            <p><strong>Description:</strong> {{ $equipe->description }}</p>
                            <p><strong>Chef d'équipe:</strong> {{ $equipe->chefEquipe ? $equipe->chefEquipe->full_name : 'Non assigné' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Membres de l'équipe</h5>
                            @if($equipe->membres->count() > 0)
                                <ul class="list-group">
                                    @foreach($equipe->membres as $membre)
                                        <li class="list-group-item">{{ $membre->full_name }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>Aucun membre dans cette équipe.</p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.equipes.edit', $equipe) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <form action="{{ route('admin.equipes.destroy', $equipe) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette équipe ?')">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

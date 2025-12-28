@extends('layouts.app')

@section('title', 'Créer une Équipe')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Créer une Équipe</h3>
                    <a href="{{ route('admin.equipes.index') }}" class="btn btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.equipes.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nom">Nom de l'équipe</label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom') }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="id_chef_equipe">Chef d'équipe</label>
                            <select class="form-control @error('id_chef_equipe') is-invalid @enderror" id="id_chef_equipe" name="id_chef_equipe">
                                <option value="">Sélectionner un chef d'équipe</option>
                                @foreach($professeurs as $professeur)
                                    <option value="{{ $professeur->id }}" {{ old('id_chef_equipe') == $professeur->id ? 'selected' : '' }}>
                                        {{ $professeur->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_chef_equipe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Paramètres - Admin EMSI')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-3 col-lg-2">
            @include('layouts.partials.admin-sidebar')
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-cogs text-primary me-2"></i>
                    Paramètres du Système
                </h2>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-globe me-2"></i>Configuration Générale</h5>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Nom de l'Institution</label>
                                    <input type="text" class="form-control" value="EMSI" readonly>
                                    <small class="text-muted">Ce paramètre est configuré dans le fichier de configuration</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email de Contact</label>
                                    <input type="email" class="form-control" value="contact@emsi.ma" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Langue par Défaut</label>
                                    <select class="form-select" disabled>
                                        <option value="fr" selected>Français</option>
                                        <option value="en">English</option>
                                        <option value="ar">العربية</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Fuseau Horaire</label>
                                    <select class="form-select" disabled>
                                        <option value="Africa/Casablanca" selected>Africa/Casablanca</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary" disabled>
                                    <i class="fas fa-save me-2"></i>Enregistrer les Modifications
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow mt-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Sécurité</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Les paramètres de sécurité sont configurés dans les fichiers de configuration Laravel.
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Authentification</h6>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>Double authentification disponible</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Protection CSRF activée</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Sessions sécurisées</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6>Validation</h6>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>Validation des entrées</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Protection XSS</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Chiffrement des mots de passe</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations Système</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Version Laravel:</strong><br>
                                <span class="badge bg-primary">{{ app()->version() }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Version PHP:</strong><br>
                                <span class="badge bg-success">{{ PHP_VERSION }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Base de données:</strong><br>
                                <span class="badge bg-info">{{ config('database.default') }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Cache:</strong><br>
                                <span class="badge bg-warning">{{ config('cache.default') }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Environnement:</strong><br>
                                <span class="badge bg-secondary">{{ app()->environment() }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mt-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-database me-2"></i>Maintenance</h5>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-outline-primary btn-sm w-100 mb-2" disabled>
                                <i class="fas fa-broom me-2"></i>Nettoyer le Cache
                            </button>
                            <button class="btn btn-outline-warning btn-sm w-100 mb-2" disabled>
                                <i class="fas fa-sync me-2"></i>Recharger les Routes
                            </button>
                            <button class="btn btn-outline-danger btn-sm w-100" disabled>
                                <i class="fas fa-exclamation-triangle me-2"></i>Mode Maintenance
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

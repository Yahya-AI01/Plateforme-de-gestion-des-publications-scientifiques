@extends('layouts.app')

@section('title', 'Statistiques - Admin EMSI')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-3 col-lg-2">
            @include('layouts.partials.admin-sidebar')
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-chart-bar text-primary me-2"></i>
                    Statistiques du Système
                </h2>
                <small class="text-muted">Dernière mise à jour: {{ now()->format('d/m/Y H:i') }}</small>
            </div>

            <div class="row">
                <!-- Statistiques générales -->
                <div class="col-md-3 mb-4">
                    <div class="card shadow border-primary">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x text-primary mb-2"></i>
                            <h4 class="text-primary">{{ \App\Models\Professor::count() }}</h4>
                            <small class="text-muted">Professeurs</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card shadow border-success">
                        <div class="card-body text-center">
                            <i class="fas fa-book fa-2x text-success mb-2"></i>
                            <h4 class="text-success">{{ \App\Models\Publication::count() }}</h4>
                            <small class="text-muted">Publications</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card shadow border-info">
                        <div class="card-body text-center">
                            <i class="fas fa-users-cog fa-2x text-info mb-2"></i>
                            <h4 class="text-info">{{ \App\Models\Equipe::count() }}</h4>
                            <small class="text-muted">Équipes</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card shadow border-warning">
                        <div class="card-body text-center">
                            <i class="fas fa-user-shield fa-2x text-warning mb-2"></i>
                            <h4 class="text-warning">{{ \App\Models\Admin::count() }}</h4>
                            <small class="text-muted">Administrateurs</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Graphiques et détails -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Publications par Type</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="publicationsChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Professeurs par Grade</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="gradesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Publications par Année</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="yearlyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour les graphiques (à remplacer par des données réelles depuis le backend)
    const publicationsData = {
        labels: ['Article', 'Conférence', 'Chapitre', 'Thèse'],
        datasets: [{
            data: [12, 8, 5, 3],
            backgroundColor: ['#007bff', '#28a745', '#17a2b8', '#ffc107']
        }]
    };

    const gradesData = {
        labels: ['Doctorant', 'Docteur'],
        datasets: [{
            data: [15, 10],
            backgroundColor: ['#dc3545', '#6f42c1']
        }]
    };

    const yearlyData = {
        labels: ['2020', '2021', '2022', '2023', '2024', '2025'],
        datasets: [{
            label: 'Publications',
            data: [2, 5, 8, 10, 12, 6],
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            tension: 0.4
        }]
    };

    // Création des graphiques
    new Chart(document.getElementById('publicationsChart'), {
        type: 'pie',
        data: publicationsData
    });

    new Chart(document.getElementById('gradesChart'), {
        type: 'doughnut',
        data: gradesData
    });

    new Chart(document.getElementById('yearlyChart'), {
        type: 'line',
        data: yearlyData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection

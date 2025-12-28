@extends('layouts.app')

@section('title', 'Tableau de Bord Admin - EMSI')

@push('styles')
<style>
    .stats-card {
        transition: transform 0.3s;
        border-radius: 10px;
        background-color: white;
        color: black;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    .chart-container {
        height: 250px;
        position: relative;
    }
    .timeline-item {
        position: relative;
        padding-left: 30px;
        margin-bottom: 20px;
    }
    .timeline-item:before {
        content: '';
        position: absolute;
        left: 10px;
        top: 5px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #00a859;
    }
    .text-primary { color: #00a859 !important; }
    .text-success { color: #00a859 !important; }
    .text-info { color: #00a859 !important; }
    .text-warning { color: #00a859 !important; }
    .text-gray-800 { color: black !important; }
    .text-gray-300 { color: #666 !important; }
    .text-muted { color: #666 !important; }
    .bg-primary { background-color: #00a859 !important; color: white !important; }
    .bg-success { background-color: #00a859 !important; color: white !important; }
    .bg-info { background-color: #00a859 !important; color: white !important; }
    .bg-warning { background-color: #00a859 !important; color: white !important; }
    .bg-danger { background-color: #00a859 !important; color: white !important; }
    .border-left-primary { border-left: 4px solid #00a859 !important; }
    .border-left-success { border-left: 4px solid #00a859 !important; }
    .border-left-info { border-left: 4px solid #00a859 !important; }
    .border-left-warning { border-left: 4px solid #00a859 !important; }
    .btn-primary { background-color: #00a859 !important; border-color: #00a859 !important; }
    .btn-primary:hover { background-color: #008f4d !important; border-color: #008f4d !important; }
    .btn-outline-secondary { border-color: #00a859 !important; color: #00a859 !important; }
    .btn-outline-secondary:hover { background-color: #00a859 !important; border-color: #00a859 !important; }
    .card { background-color: white !important; color: black !important; }
    .card-header { background-color: white !important; color: black !important; border-bottom: 1px solid #ddd !important; }
    .table { color: black !important; }
    .table thead th { background-color: #f8f9fa !important; color: black !important; border-color: #ddd !important; }
    .table tbody td { border-color: #ddd !important; }
    .list-group-item { background-color: white !important; color: black !important; border-color: #ddd !important; }
    .badge { color: white !important; }
    .bg-light { background-color: #f8f9fa !important; }
    .sidebar { background-color: #f8f9fa !important; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            @include('layouts.partials.admin-sidebar')
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <!-- Welcome Header -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom fade-in-up">
                <h1 class="h2 fade-in-up">
                    <i class="fas fa-tachometer-alt text-primary me-2"></i>
                    Tableau de Bord Administratif
                </h1>
                <div class="btn-toolbar slide-in-right">
                    <button class="btn btn-sm btn-outline-secondary me-2 btn-modern" id="exportDashboard">
                        <i class="fas fa-download me-1"></i> Exporter PDF
                    </button>
                    <button class="btn btn-sm btn-primary btn-modern" id="refreshDashboard">
                        <i class="fas fa-sync-alt me-1"></i> Actualiser
                    </button>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4 fade-in-up">
                    <div class="card border-left-primary shadow h-100 py-2 stats-card card-modern">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                        Publications totales
                                    </div>
                                    <div class="h5 mb-0 fw-bold text-gray-800" id="totalPublications">{{ $stats['totalPublications'] ?? 0 }}</div>
                                    <div class="mt-2">
                                        <span class="badge badge-modern">
                                            <i class="fas fa-arrow-up me-1"></i> 12% ce mois
                                        </span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-book fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4 slide-in-left">
                    <div class="card border-left-success shadow h-100 py-2 stats-card card-modern">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                        Professeurs actifs
                                    </div>
                                    <div class="h5 mb-0 fw-bold text-gray-800" id="totalProfesseurs">{{ $stats['totalProfesseurs'] ?? 0 }}</div>
                                    <div class="mt-2">
                                        @php
                                            // Calcul sécurisé des grades
                                            $docteurs = 0;
                                            $doctorants = 0;
                                            try {
                                                $docteurs = \App\Models\Professor::where('grade', 'Docteur')->count();
                                                $doctorants = \App\Models\Professor::where('grade', 'Doctorant')->count();
                                            } catch (\Exception $e) {
                                                // Si erreur, utiliser des valeurs par défaut
                                            }
                                        @endphp
                                        <span class="badge badge-modern">
                                            {{ $docteurs }} Docteurs, {{ $doctorants }} Doctorants
                                        </span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4 slide-in-right">
                    <div class="card border-left-info shadow h-100 py-2 stats-card card-modern">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                        Équipes de recherche
                                    </div>
                                    <div class="h5 mb-0 fw-bold text-gray-800" id="totalEquipes">{{ $stats['totalEquipes'] ?? 0 }}</div>
                                    <div class="mt-2">
                                        @if($equipes->isNotEmpty() && isset($equipes->first()->nom_equipe))
                                        <span class="badge bg-warning badge-modern">{{ $equipes->first()->nom_equipe }} : {{ $equipes->first()->publications_count ?? 0 }} publications</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4 fade-in-up">
                    <div class="card border-left-warning shadow h-100 py-2 stats-card card-modern">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                        Publications ce mois
                                    </div>
                                    <div class="h5 mb-0 fw-bold text-gray-800" id="publicationsMois">{{ $stats['publicationsMois'] ?? 0 }}</div>
                                    <div class="mt-2">
                                        <span class="badge badge-modern">+3 vs mois dernier</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <!-- Chart 1: Publications par équipe -->
                <div class="col-xl-6 col-md-12 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-chart-pie me-2"></i>
                                Publications par équipe
                            </h6>
                            <select class="form-select form-select-sm w-auto" id="chartYearFilter">
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                                <option value="2022">2022</option>
                            </select>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="teamPublicationsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart 2: Évolution mensuelle -->
                <div class="col-xl-6 col-md-12 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-chart-line me-2"></i>
                                Évolution mensuelle des publications
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="monthlyTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Publications & Activity -->
            <div class="row">
                <!-- Recent Publications -->
                <div class="col-lg-8 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-file-alt me-2"></i>
                                Publications récentes
                            </h6>
                            <a href="{{ route('admin.publications.index') }}" class="btn btn-sm btn-primary">
                                Voir toutes
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Titre</th>
                                            <th>Auteur</th>
                                            <th>Équipe</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recentPublicationsBody">
                                        @forelse($publicationsRecent as $publication)
                                        <tr>
                                            <td>{{ Str::limit($publication->titre, 50) }}</td>
                                            <td>
                                                @if($publication->auteurPrincipal)
                                                    {{ $publication->auteurPrincipal->prenom }} {{ $publication->auteurPrincipal->nom }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($publication->auteurPrincipal && $publication->auteurPrincipal->equipe)
                                                <span class="badge" style="background-color: #00a859; color: white;">{{ $publication->auteurPrincipal->equipe->nom_equipe }}</span>
                                                @else
                                                <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>{{ $publication->created_at->format('d/m/Y') }}</td>
                                            <td><span class="badge bg-info">{{ $publication->type ?? 'N/A' }}</span></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Aucune publication récente</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats & Activity -->
                <div class="col-lg-4 mb-4">
                    <!-- Team Ranking -->
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-trophy me-2"></i>
                                Classement des équipes
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush" id="teamRankingList">
                                @forelse($equipes as $index => $equipe)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge {{ $index < 3 ? 'bg-warning' : 'bg-secondary' }} me-2">
                                            #{{ $index + 1 }}
                                        </span>
                                        {{ $equipe->nom_equipe ?? 'Équipe #' . ($index + 1) }}
                                    </div>
                                    <span class="badge bg-success">{{ $equipe->publications_count ?? 0 }} publications</span>
                                </div>
                                @empty
                                <div class="list-group-item text-center">Aucune équipe</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="card shadow mt-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-bell me-2"></i>
                                Activité récente
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline" id="recentActivity">
                                <div class="timeline-item">
                                    <h6 class="mb-1">Nouvelle publication</h6>
                                    <p class="mb-0 small text-muted">"IA et santé" par Dr. Dupont</p>
                                    <small class="text-muted">Il y a 2 heures</small>
                                </div>
                                <div class="timeline-item">
                                    <h6 class="mb-1">Professeur inscrit</h6>
                                    <p class="mb-0 small text-muted">Dr. Martin s'est inscrit</p>
                                    <small class="text-muted">Il y a 5 heures</small>
                                </div>
                                <div class="timeline-item">
                                    <h6 class="mb-1">Mise à jour de profil</h6>
                                    <p class="mb-0 small text-muted">Pr. Dubois a mis à jour son CV</p>
                                    <small class="text-muted">Hier, 14:30</small>
                                </div>
                                <div class="timeline-item">
                                    <h6 class="mb-1">Nouvelle équipe</h6>
                                    <p class="mb-0 small text-muted">Équipe "Cybersécurité" créée</p>
                                    <small class="text-muted">23/12/2024</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Scripts pour le dashboard
        console.log('Dashboard admin chargé');
        
        // Mettre à jour l'heure en direct
        function updateDateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            $('#currentDateTime').text(now.toLocaleDateString('fr-FR', options));
        }
        
        updateDateTime();
        setInterval(updateDateTime, 60000);
        
        // Exporter le dashboard
        $('#exportDashboard').click(function() {
            if(confirm('Exporter le tableau de bord en PDF ?')) {
                alert('Export PDF démarré...');
                // Ici, intégrer une librairie PDF comme jsPDF
            }
        });
        
        // Actualiser le dashboard
        $('#refreshDashboard').click(function() {
            $(this).html('<i class="fas fa-spinner fa-spin me-1"></i> Actualisation...');
            setTimeout(() => {
                location.reload();
            }, 1000);
        });
        
        // Initialiser les graphiques (exemple)
        if($('#teamPublicationsChart').length) {
            const ctx = document.getElementById('teamPublicationsChart').getContext('2d');
            const teamChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['IA', 'Informatique', 'Mathématiques', 'Génie Civil'],
                    datasets: [{
                        data: [12, 19, 8, 5],
                        backgroundColor: [
                            'var(--primary-green)',
                            'var(--primary-green)',
                            'var(--primary-green)',
                            'var(--primary-green)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
        
        if($('#monthlyTrendChart').length) {
            const ctx2 = document.getElementById('monthlyTrendChart').getContext('2d');
            const trendChart = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
                    datasets: [{
                        label: 'Publications 2024',
                        data: [3, 5, 7, 8, 6, 9, 10, 8, 12, 15, 18, 20],
                        borderColor: '#00a859',
                        backgroundColor: 'rgba(0, 168, 89, 0.1)',
                        borderWidth: 2,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 5
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
<!-- resources/views/layouts/partials/admin-sidebar.blade.php -->
<div class="position-sticky pt-3">
    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
        <span>Navigation</span>
    </h6>

    <ul class="nav flex-column mb-2">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               href="{{ route('admin.dashboard') }}"
               style="{{ request()->routeIs('admin.dashboard') ? 'color: #00a859 !important; background-color: rgba(0, 168, 89, 0.1) !important;' : '' }}">
                <i class="fas fa-tachometer-alt me-2"></i>
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.professeurs.*') ? 'active' : '' }}"
               href="{{ route('admin.professeurs.index') }}"
               style="{{ request()->routeIs('admin.professeurs.*') ? 'color: #00a859 !important; background-color: rgba(0, 168, 89, 0.1) !important;' : '' }}">
                <i class="fas fa-user-graduate me-2"></i>
                Gestion Professeurs
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.publications.*') ? 'active' : '' }}"
               href="{{ route('admin.publications.index') }}"
               style="{{ request()->routeIs('admin.publications.*') ? 'color: #00a859 !important; background-color: rgba(0, 168, 89, 0.1) !important;' : '' }}">
                <i class="fas fa-book me-2"></i>
                Gestion Publications
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.equipes.*') ? 'active' : '' }}"
               href="{{ route('admin.equipes.index') }}"
               style="{{ request()->routeIs('admin.equipes.*') ? 'color: #00a859 !important; background-color: rgba(0, 168, 89, 0.1) !important;' : '' }}">
                <i class="fas fa-users me-2"></i>
                Gestion Équipes
            </a>
        </li>
    </ul>

    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
        <span>Rapports</span>
    </h6>

    <ul class="nav flex-column mb-2">
        <li class="nav-item">
            <a class="nav-link" href="#" style="color: #00a859 !important;">
                <i class="fas fa-chart-bar me-2"></i>
                Statistiques
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" style="color: #00a859 !important;">
                <i class="fas fa-file-export me-2"></i>
                Exports
            </a>
        </li>
    </ul>

    <div class="px-3 mt-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm w-100" style="border-color: #00a859; color: #00a859;">
                <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
            </button>
        </form>
    </div>
</div>

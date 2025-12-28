<!-- resources/views/layouts/partials/admin-navbar.blade.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <span class="d-none d-md-inline">Admin EMSI</span>
        </a>

        <!-- Mobile toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar content -->
        <div class="collapse navbar-collapse" id="adminNavbar">
            <!-- Left items -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.professeurs.*') ? 'active' : '' }}" 
                       href="{{ route('admin.professeurs.index') }}">
                        <i class="fas fa-user-graduate me-1"></i>Professeurs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.publications.*') ? 'active' : '' }}" 
                       href="{{ route('admin.publications.index') }}">
                        <i class="fas fa-book me-1"></i>Publications
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.equipes.*') ? 'active' : '' }}" 
                       href="{{ route('admin.equipes.index') }}">
                        <i class="fas fa-users me-1"></i>Équipes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.statistiques') ? 'active' : '' }}" 
                       href="{{ route('admin.statistiques') }}">
                        <i class="fas fa-chart-bar me-1"></i>Statistiques
                    </a>
                </li>
            </ul>

            <!-- Right items -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" 
                       href="#" 
                       id="adminDropdown" 
                       role="button" 
                       data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle fa-lg me-2"></i>
                        <span class="d-none d-md-inline">{{ auth()->guard('admin')->user()->name ?? 'Admin' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog me-2"></i>Paramètres
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
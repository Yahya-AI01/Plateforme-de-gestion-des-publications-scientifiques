<!-- resources/views/layouts/partials/professor-navbar.blade.php -->
<nav class="navbar navbar-expand-lg navbar-dark shadow" style="background-color: #00a859;">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand" href="{{ route('professor.dashboard') }}">
            <span class="d-none d-md-inline">Espace Professeur</span>
        </a>

        <!-- Mobile toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#professorNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar content -->
        <div class="collapse navbar-collapse" id="professorNavbar">
            <!-- Left items -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('professor.dashboard') ? 'active' : '' }}" 
                       href="{{ route('professor.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('professor.publications.*') ? 'active' : '' }}" 
                       href="{{ route('professor.publications.index') }}">
                        <i class="fas fa-book me-1"></i>Mes Publications
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('professor.profile') ? 'active' : '' }}" 
                       href="{{ route('professor.profile') }}">
                        <i class="fas fa-user me-1"></i>Mon Profil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('bibliotheque') ? 'active' : '' }}" 
                       href="{{ route('bibliotheque') }}">
                        <i class="fas fa-university me-1"></i>Bibliothèque
                    </a>
                </li>
            </ul>

            <!-- Right items -->
            <ul class="navbar-nav">
                @php
                    $user = auth()->guard('professeur')->user();
                    $fullName = $user ? $user->prenom . ' ' . $user->nom : 'Professeur';
                @endphp
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" 
                       href="#" 
                       id="professorDropdown" 
                       role="button" 
                       data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle fa-lg me-2"></i>
                        <span class="d-none d-md-inline">{{ $fullName }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('professor.profile') }}">
                                <i class="fas fa-user me-2"></i>Mon Profil
                            </a>
                        </li>
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
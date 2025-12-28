@extends('layouts.app')

@section('title', 'Connexion - EMSI Publications')

@push('styles')
<style>
    :root {
        --primary-color: #00a859;
        --primary-dark: #008542;
        --secondary-color: #ffffff;
        --light-bg: #ffffff;
        --shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        --border-radius: 20px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .login-container {
        min-height: 100vh;
        background: white;
        position: relative;
        overflow: hidden;
    }

    .login-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.08)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.1;
    }

    .login-card {
        border-radius: var(--border-radius);
        border: none;
        box-shadow: var(--shadow);
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.98);
        transform: translateY(0);
        transition: var(--transition);
    }

    .login-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }

    .logo-emsi {
        width: 120px;
        height: auto;
        margin-bottom: 30px;
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
        transition: var(--transition);
    }

    .logo-emsi:hover {
        transform: scale(1.05);
    }

    .form-title {
        color: var(--primary-dark);
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }

    .form-subtitle {
        color: var(--secondary-color);
        font-size: 1.1rem;
        margin-bottom: 40px;
    }

    .role-selector {
        margin-bottom: 35px;
    }

    .role-selector .btn {
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        border: 2px solid var(--primary-color);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .role-selector .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .role-selector .btn:hover::before {
        left: 100%;
    }

    .role-selector .btn.active {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        box-shadow: 0 4px 15px rgba(0, 168, 89, 0.3);
        transform: translateY(-2px);
    }

    .form-floating {
        margin-bottom: 25px;
    }

    .form-floating > .form-control {
        border-radius: 12px;
        border: 2px solid var(--primary-color);
        padding: 1rem 1rem;
        font-size: 1rem;
        transition: var(--transition);
        background: var(--light-bg);
    }

    .form-floating > .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(0, 168, 89, 0.15);
        background: white;
    }

    .form-floating > label {
        padding: 1rem 1rem;
        color: var(--secondary-color);
        font-weight: 500;
    }

    .input-group .form-control {
        border-radius: 0 12px 12px 0;
        border-left: none;
    }

    .input-group .input-group-text {
        border-radius: 12px 0 0 12px;
        border-right: none;
        background: var(--light-bg);
        border-color: var(--primary-color);
        color: var(--secondary-color);
    }

    .password-toggle {
        border-radius: 0 12px 12px 0;
        border-left: none;
        background: var(--light-bg);
        border-color: var(--primary-color);
        color: var(--secondary-color);
        transition: var(--transition);
    }

    .password-toggle:hover {
        background: var(--primary-color);
        color: white;
    }

    .form-check {
        margin-bottom: 30px;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-login {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border: none;
        border-radius: 12px;
        padding: 16px 32px;
        font-size: 1.1rem;
        font-weight: 600;
        color: white;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 168, 89, 0.3);
    }

    .btn-login::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn-login:hover::before {
        left: 100%;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 168, 89, 0.4);
    }

    .register-link {
        color: var(--secondary-color);
        text-decoration: none;
        font-weight: 500;
        transition: var(--transition);
    }

    .register-link:hover {
        color: var(--primary-color);
        text-decoration: underline;
    }

    .footer-text {
        color: var(--secondary-color);
        font-size: 0.9rem;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 1px solid var(--primary-color);
    }

    .alert {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .login-card {
            margin: 20px;
            padding: 30px 25px;
        }

        .form-title {
            font-size: 1.5rem;
        }

        .logo-emsi {
            width: 100px;
        }
    }

    /* Animation d'entrée */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-card {
        animation: fadeInUp 0.6s ease-out;
    }
</style>
@endpush

@section('content')
<div class="login-container">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="login-card bg-white p-4 p-md-5">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d3/EMSI_logo.svg/1200px-EMSI_logo.svg.png"
                             alt="EMSI" class="logo-emsi">
                        <h1 class="form-title">Plateforme Publications Scientifiques</h1>
                        <p class="form-subtitle">Connexion à votre espace</p>
                    </div>

                    <!-- Messages d'erreur -->
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        @foreach($errors->all() as $error)
                        <p class="mb-0">{{ $error }}</p>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <!-- Formulaire de connexion -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Sélection du rôle -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Je suis :</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="role" id="professeur" value="professeur" checked>
                                <label class="btn btn-outline-primary" for="professeur">
                                    <i class="fas fa-user-graduate me-2"></i>Professeur
                                </label>
                                
                                <input type="radio" class="btn-check" name="role" id="admin" value="admin">
                                <label class="btn btn-outline-primary" for="admin">
                                    <i class="fas fa-user-shield me-2"></i>Administrateur
                                </label>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Email institutionnel
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-at text-muted"></i>
                                </span>
                                <input type="email" class="form-control" name="email" 
                                       placeholder="ex: nom.prenom@emsi.ma" 
                                       value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <!-- Mot de passe -->
                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>Mot de passe
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-key text-muted"></i>
                                </span>
                                <input type="password" class="form-control" name="password" 
                                       placeholder="Votre mot de passe" required>
                                <button class="btn password-toggle" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">
                                    Se souvenir de moi
                                </label>
                            </div>
                            <a href="#" class="text-decoration-none text-primary">
                                Mot de passe oublié ?
                            </a>
                        </div>

                        <!-- Bouton de connexion -->
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg py-3">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Se connecter
                            </button>
                        </div>

                        <!-- Lien d'inscription -->
                        <div class="text-center">
                            <p class="text-muted">
                                Nouveau professeur ?
                                <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">
                                    Inscrivez-vous avec un code d'invitation
                                </a>
                            </p>
                        </div>
                    </form>

                    <!-- Footer -->
                    <hr class="my-4">
                    <div class="text-center">
                        <p class="text-muted small">
                            <i class="fas fa-shield-alt me-1"></i>
                            Plateforme sécurisée - Laboratoire de Recherche EMSI
                            <br>
                            <span class="text-muted">© {{ date('Y') }} - Tous droits réservés</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Afficher/Masquer le mot de passe
        $('#togglePassword').click(function() {
            const passwordInput = $('input[name="password"]');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
            passwordInput.attr('type', type);
            
            const icon = $(this).find('i');
            icon.toggleClass('fa-eye fa-eye-slash');
        });

        // Animation des boutons de rôle
        $('.btn-check').change(function() {
            $('.btn-outline-primary').removeClass('active');
            $(this).next().addClass('active');
        });

        // Initialiser le bouton actif
        $('.btn-check:checked').next().addClass('active');
    });
</script>
@endpush
@extends('layouts.app')

@section('title', 'Inscription - EMSI Publications')

@push('styles')
<style>
    :root {
        --primary-color: #00a859;
        --primary-dark: #008542;
        --secondary-color: #000000;
        --light-bg: #ffffff;
        --shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        --border-radius: 20px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .login-container {
        min-height: 100vh;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
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

    .text-primary { color: var(--primary-color) !important; }
    .text-muted { color: var(--secondary-color) !important; }

    .form-label {
        color: var(--secondary-color);
        font-weight: 600;
    }

    .form-control {
        border-radius: 12px;
        border: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: var(--transition);
        background: var(--light-bg);
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(0, 168, 89, 0.15);
        background: white;
    }

    .input-group-text {
        border-radius: 12px 0 0 12px;
        border-right: none;
        background: var(--light-bg);
        border-color: #e9ecef;
        color: var(--secondary-color);
    }

    .btn-outline-secondary {
        border-radius: 0 12px 12px 0;
        border-left: none;
        background: var(--light-bg);
        border-color: #e9ecef;
        color: var(--secondary-color);
        transition: var(--transition);
    }

    .btn-outline-secondary:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border: none;
        border-radius: 12px;
        padding: 16px 32px;
        font-size: 1.1rem;
        font-weight: 600;
        color: white;
        transition: var(--transition);
        box-shadow: 0 4px 15px rgba(0, 168, 89, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 168, 89, 0.4);
    }

    .alert {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .text-decoration-none {
        text-decoration: none;
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

    @media (max-width: 768px) {
        .login-card {
            margin: 20px;
            padding: 30px 25px;
        }

        .logo-emsi {
            width: 100px;
        }
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
                        <h2 class="text-primary fw-bold">Inscription Professeur</h2>
                        <p class="text-muted">Créez votre compte avec un code d'invitation</p>
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

                    <!-- Formulaire d'inscription -->
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Code d'invitation -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-key me-2"></i>Code d'invitation *
                            </label>
                            <input type="text" class="form-control" name="code" 
                                   placeholder="Code fourni par l'administration" 
                                   value="{{ old('code') }}" required>
                            <small class="text-muted">Code temporaire : INVITATION2024</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nom *</label>
                                <input type="text" class="form-control" name="nom" 
                                       value="{{ old('nom') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prénom *</label>
                                <input type="text" class="form-control" name="prenom" 
                                       value="{{ old('prenom') }}" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email institutionnel *</label>
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
                        <div class="mb-3">
                            <label class="form-label">Mot de passe *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-key text-muted"></i>
                                </span>
                                <input type="password" class="form-control" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordRegister">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Minimum 8 caractères</small>
                        </div>

                        <!-- Confirmation mot de passe -->
                        <div class="mb-4">
                            <label class="form-label">Confirmer le mot de passe *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-key text-muted"></i>
                                </span>
                                <input type="password" class="form-control" name="password_confirmation" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Bouton d'inscription -->
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg py-3">
                                <i class="fas fa-user-plus me-2"></i>
                                S'inscrire
                            </button>
                        </div>

                        <!-- Lien de connexion -->
                        <div class="text-center">
                            <p class="text-muted">
                                Déjà inscrit ?
                                <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">
                                    Connectez-vous ici
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Afficher/Masquer les mots de passe
        $('#togglePasswordRegister').click(function() {
            const passwordInput = $('input[name="password"]');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
            passwordInput.attr('type', type);
            
            const icon = $(this).find('i');
            icon.toggleClass('fa-eye fa-eye-slash');
        });

        $('#togglePasswordConfirm').click(function() {
            const passwordInput = $('input[name="password_confirmation"]');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
            passwordInput.attr('type', type);
            
            const icon = $(this).find('i');
            icon.toggleClass('fa-eye fa-eye-slash');
        });
    });
</script>
@endpush
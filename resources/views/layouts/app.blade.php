<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EMSI Publications')</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS personnalisés -->
    @if(file_exists(public_path('css/style.css')))
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @endif
    
    @if(file_exists(public_path('css/admin.css')))
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @endif
    
    @stack('styles')
    
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d3/EMSI_logo.svg/1200px-EMSI_logo.svg.png">
</head>
<body>
    <!-- Navigation -->
    @if(auth()->guard('admin')->check())
        @include('layouts.partials.admin-navbar')
    @elseif(auth()->guard('professeur')->check())
        @include('layouts.partials.professor-navbar')
    @else
        @include('layouts.partials.guest-navbar')
    @endif

    <!-- Messages Flash -->
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Contenu principal -->
    <main>
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\EquipeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ==================== ROUTES PUBLIQUES ====================

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentification (pour utilisateurs non connectés)
Route::middleware('guest')->group(function () {
    
    // Connexion avec sélection de rôle
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Inscription professeur
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Bibliothèque publique
Route::get('/bibliotheque', [PublicationController::class, 'bibliotheque'])->name('bibliotheque');

// Afficher les détails d'une publication (publique)
Route::get('/publications/{id}', [PublicationController::class, 'show'])->name('publications.show');

// Recherche publique
Route::get('/search', [SearchController::class, 'searchPublications'])->name('search.publications');
Route::get('/advanced-search', [SearchController::class, 'advancedSearch'])->name('search.advanced');

// ==================== DÉCONNEXION ====================

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================== ROUTES PROFESSEUR ====================

Route::middleware(['auth:professeur'])->prefix('professor')->name('professor.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'professorDashboard'])->name('dashboard');
    
    // Profil
    Route::get('/profil', [ProfessorController::class, 'showProfile'])->name('profile');
    Route::put('/profil', [ProfessorController::class, 'updateProfile'])->name('profile.update');
    
    // Publications
    Route::get('/publications', [PublicationController::class, 'index'])->name('publications.index');
    Route::get('/publications/create', [PublicationController::class, 'create'])->name('publications.create');
    Route::post('/publications', [PublicationController::class, 'store'])->name('publications.store');
    Route::get('/publications/{publication}/edit', [PublicationController::class, 'edit'])->name('publications.edit');
    Route::put('/publications/{publication}', [PublicationController::class, 'update'])->name('publications.update');
    Route::delete('/publications/{publication}', [PublicationController::class, 'destroy'])->name('publications.destroy');
    
    // Bibliothèque professeur
    Route::get('/bibliotheque', function () {
        return redirect()->route('bibliotheque');
    })->name('bibliotheque');
});

// ==================== ROUTES ADMINISTRATEUR ====================

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // ========== DASHBOARD ==========
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    
    // ========== GESTION DES ÉQUIPES ==========
    Route::get('/equipes', [EquipeController::class, 'index'])->name('equipes.index');
    Route::get('/equipes/create', [EquipeController::class, 'create'])->name('equipes.create');
    Route::post('/equipes', [EquipeController::class, 'store'])->name('equipes.store');
    Route::get('/equipes/{equipe}', [EquipeController::class, 'show'])->name('equipes.show');
    Route::get('/equipes/{equipe}/edit', [EquipeController::class, 'edit'])->name('equipes.edit');
    Route::put('/equipes/{equipe}', [EquipeController::class, 'update'])->name('equipes.update');
    Route::delete('/equipes/{equipe}', [EquipeController::class, 'destroy'])->name('equipes.destroy');
    
    // Membres d'équipe
    Route::post('/equipes/{equipe}/add-member', [EquipeController::class, 'addMember'])->name('equipes.add-member');
    Route::delete('/equipes/{equipe}/remove-member/{professeur}', [EquipeController::class, 'removeMember'])->name('equipes.remove-member');
    
    // ========== GESTION DES PROFESSEURS ==========
    Route::get('/professeurs', [ProfessorController::class, 'index'])->name('professeurs.index');
    Route::get('/professeurs/create', [ProfessorController::class, 'create'])->name('professeurs.create');
    Route::post('/professeurs', [ProfessorController::class, 'store'])->name('professeurs.store');
    Route::get('/professeurs/{professeur}', [ProfessorController::class, 'show'])->name('professeurs.show');
    Route::get('/professeurs/{professeur}/edit', [ProfessorController::class, 'edit'])->name('professeurs.edit');
    Route::put('/professeurs/{professeur}', [ProfessorController::class, 'update'])->name('professeurs.update');
    Route::delete('/professeurs/{professeur}', [ProfessorController::class, 'destroy'])->name('professeurs.destroy');
    
    // Recherche de professeurs
    Route::get('/search/professeurs', [ProfessorController::class, 'search'])->name('search.professeurs');
    
    // ========== GESTION DES PUBLICATIONS ==========
    Route::get('/publications', [PublicationController::class, 'adminIndex'])->name('publications.index');
    Route::get('/publications/create', [PublicationController::class, 'create'])->name('publications.create');
    Route::post('/publications', [PublicationController::class, 'store'])->name('publications.store');
    Route::get('/publications/{publication}', [PublicationController::class, 'show'])->name('publications.show');
    Route::get('/publications/{publication}/edit', [PublicationController::class, 'edit'])->name('publications.edit');
    Route::put('/publications/{publication}', [PublicationController::class, 'update'])->name('publications.update');
    Route::delete('/publications/{publication}', [PublicationController::class, 'destroy'])->name('publications.destroy');
    

    
    // ========== STATISTIQUES ==========
    Route::get('/statistiques', function () {
        return view('admin.statistiques');
    })->name('statistiques');
    
    // ========== PARAMÈTRES ==========
    Route::get('/parametres', function () {
        return view('admin.parametres');
    })->name('parametres');
});

// ==================== ROUTES DE FALLBACK ====================

// Redirection pour les URLs non trouvées
Route::fallback(function () {
    return redirect()->route('home')->with('error', 'Page non trouvée.');
});

// Téléchargement PDF (accessible publiquement)
Route::get('/publications/{id}/download', [PublicationController::class, 'downloadPDF'])->name('publications.download');

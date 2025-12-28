<?php

/**
 * Fournisseur de services principal de l'application
 *
 * Ce fournisseur permet d'enregistrer et de configurer les services
 * de l'application Laravel.
 */
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Classe AppServiceProvider
 *
 * Fournisseur de services pour l'application
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Enregistrer les services de l'application
     *
     * Cette méthode est appelée lors de l'enregistrement des services
     * dans le conteneur de services.
     */
    public function register(): void
    {
        // Enregistrement des services personnalisés si nécessaire
    }

    /**
     * Démarrer les services de l'application
     *
     * Cette méthode est appelée après l'enregistrement de tous les
     * fournisseurs de services.
     */
    public function boot(): void
    {
        // Configuration des services au démarrage de l'application
    }
}

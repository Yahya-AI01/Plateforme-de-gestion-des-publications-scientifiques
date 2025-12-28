<?php

/**
 * Modèle pour les administrateurs
 *
 * Ce modèle représente un administrateur dans le système,
 * avec des fonctionnalités d'authentification et de gestion des rôles.
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Classe Admin
 *
 * Modèle Eloquent pour les administrateurs avec authentification
 */
class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être assignés en masse
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_super_admin',
    ];

    /**
     * Les attributs qui doivent être cachés lors de la sérialisation
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Obtenir les attributs qui doivent être castés
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
        ];
    }

    /**
     * Vérifie si l'administrateur est un super administrateur
     *
     * @return bool Vrai si l'administrateur est super admin, faux sinon
     */
    public function isSuperAdmin(): bool
    {
        // Retourne la valeur de is_super_admin ou faux par défaut
        return $this->is_super_admin ?? false;
    }
}

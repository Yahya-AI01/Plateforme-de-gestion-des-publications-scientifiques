<?php

/**
 * Modèle pour les utilisateurs du système
 *
 * Ce modèle représente un utilisateur authentifiable avec ses informations
 * de connexion et de profil.
 */
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Classe User
 *
 * Modèle Eloquent pour l'authentification des utilisateurs
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être assignés en masse
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Les attributs qui doivent être cachés lors de la sérialisation
     *
     * @var list<string>
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
        // Définit les types de données pour certains attributs
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

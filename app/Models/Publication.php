<?php

/**
 * Modèle pour les publications scientifiques
 *
 * Ce modèle représente une publication scientifique avec ses auteurs,
 * métadonnées et relations avec les professeurs.
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe Publication
 *
 * Modèle Eloquent pour les publications scientifiques
 */
class Publication extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titre',
        'type',
        'annee',
        'lien_pdf',
        'domaine',
        'resume',
        'auteur_principal_id'
    ];

    /**
     * Obtenir l'auteur principal de la publication
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relation avec le modèle Professor
     */
    public function auteurPrincipal()
    {
        // Retourne le professeur qui est l'auteur principal de cette publication
        return $this->belongsTo(Professor::class, 'auteur_principal_id');
    }

    /**
     * Obtenir les co-auteurs de la publication
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany Relation avec le modèle Professor
     */
    public function coAuteurs()
    {
        // Retourne tous les professeurs qui sont co-auteurs de cette publication
        return $this->belongsToMany(Professor::class, 'co_auteurs', 'publication_id', 'professeur_id')
                    ->withTimestamps();
    }

    /**
     * Obtenir tous les auteurs de la publication (principal + co-auteurs)
     *
     * @return \Illuminate\Support\Collection Collection des auteurs
     */
    public function getAllAuteursAttribute()
    {
        // Combine l'auteur principal et les co-auteurs dans une collection
        $auteurs = collect([$this->auteurPrincipal]);
        return $auteurs->merge($this->coAuteurs)->filter();
    }

    /**
     * Obtenir les noms complets de tous les auteurs séparés par des virgules
     *
     * @return string Chaîne des noms d'auteurs
     */
    public function getAuteursNomsAttribute()
    {
        // Mappe les noms complets des auteurs et les joint par des virgules
        return $this->getAllAuteursAttribute()->map(function ($prof) {
            return $prof->full_name;
        })->implode(', ');
    }
}

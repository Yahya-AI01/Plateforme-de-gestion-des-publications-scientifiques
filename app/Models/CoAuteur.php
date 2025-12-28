<?php

/**
 * Modèle pour les co-auteurs de publications
 *
 * Ce modèle représente la relation entre les publications
 * et leurs co-auteurs (professeurs).
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe CoAuteur
 *
 * Modèle Eloquent pour la table pivot co_auteurs
 */
class CoAuteur extends Model
{
    use HasFactory;

    /**
     * Le nom de la table associée au modèle
     *
     * @var string
     */
    protected $table = 'co_auteurs';

    /**
     * Les attributs qui peuvent être assignés en masse
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'publication_id',
        'professeur_id'
    ];

    /**
     * Obtenir la publication associée
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relation avec le modèle Publication
     */
    public function publication()
    {
        // Retourne la publication à laquelle ce co-auteur est associé
        return $this->belongsTo(Publication::class);
    }

    /**
     * Obtenir le professeur co-auteur
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relation avec le modèle Professor
     */
    public function professeur()
    {
        // Retourne le professeur qui est co-auteur de cette publication
        return $this->belongsTo(Professor::class, 'professeur_id');
    }
}

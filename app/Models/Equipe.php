<?php

/**
 * Modèle pour les équipes de recherche
 *
 * Ce modèle représente une équipe de recherche avec ses membres,
 * son chef d'équipe et ses publications associées.
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe Equipe
 *
 * Modèle Eloquent pour les équipes de recherche
 */
class Equipe extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom_equipe',
        'description',
        'id_chef_equipe'
    ];

    /**
     * Obtenir le chef de l'équipe
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relation avec le modèle Professor
     */
    public function chef()
    {
        // Retourne le professeur qui est le chef de cette équipe
        return $this->belongsTo(Professor::class, 'id_chef_equipe');
    }

    /**
     * Obtenir les membres de l'équipe
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany Relation avec le modèle Professor
     */
    public function membres()
    {
        // Retourne tous les professeurs membres de cette équipe
        return $this->hasMany(Professor::class, 'equipe_id');
    }

    /**
     * Obtenir toutes les publications de l'équipe
     *
     * Inclut les publications où un membre est auteur principal ou co-auteur
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection de publications
     */
    public function publications()
    {
        // Récupère les IDs des membres de l'équipe
        $memberIds = $this->membres()->pluck('id');

        // Retourne les publications où un membre est auteur principal ou co-auteur
        return Publication::whereIn('auteur_principal_id', $memberIds)
            ->orWhereHas('coAuteurs', function ($query) use ($memberIds) {
                $query->whereIn('professeur_id', $memberIds);
            })
            ->get();
    }

    /**
     * Obtenir le nombre de membres de l'équipe
     *
     * @return int Nombre de membres
     */
    public function getNombreMembresAttribute()
    {
        // Compte le nombre de membres dans l'équipe
        return $this->membres()->count();
    }

    /**
     * Vérifier si un professeur est membre de l'équipe
     *
     * @param int $professorId ID du professeur à vérifier
     * @return bool Vrai si le professeur est membre, faux sinon
     */
    public function hasMember($professorId)
    {
        // Vérifie si le professeur avec l'ID donné est membre de l'équipe
        return $this->membres()->where('id', $professorId)->exists();
    }
}

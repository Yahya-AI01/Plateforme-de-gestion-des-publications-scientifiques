<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Professor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'professeurs';  // IMPORTANT : table en français

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'grade',
        'domaine',
        'photo',
        'equipe_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function equipe()
    {
        return $this->belongsTo(Equipe::class);
    }

    public function publications()
    {
        return $this->hasMany(Publication::class, 'auteur_principal_id');
    }

    public function coPublications()
    {
        return $this->belongsToMany(Publication::class, 'co_auteurs', 'professeur_id', 'publication_id')
                    ->withTimestamps();
    }

    public function allPublications()
    {
        return Publication::where('auteur_principal_id', $this->id)
            ->orWhereHas('coAuteurs', function ($query) {
                $query->where('professeur_id', $this->id);
            })
            ->with(['auteurPrincipal', 'coAuteurs'])
            ->get();
    }

    public function isTeamLeader(): bool
    {
        return $this->equipe && $this->equipe->id_chef_equipe === $this->id;
    }

    public function teamMembers()
    {
        if ($this->isTeamLeader() && $this->equipe) {
            return $this->equipe->membres()->where('id', '!=', $this->id)->get();
        }
        
        return collect();
    }
}
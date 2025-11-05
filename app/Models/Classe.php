<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    protected $fillable = ['nom', 'filiere_id', 'niveau_id'];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function etudiants()
    {
        return $this->hasMany(User::class)->where('role', 'etudiant');
    }

    public function cours()
    {
        return $this->belongsToMany(Cours::class, 'classe_cours')
            ->withPivot('semestre')
            ->withTimestamps();
    }

    public function ressources()
    {
        return $this->hasMany(Ressource::class);
    }

    public function calendrierCours()
    {
        return $this->hasMany(CalendrierCours::class)->orderByRaw("
            CASE jour_semaine
                WHEN 'Lundi' THEN 1
                WHEN 'Mardi' THEN 2
                WHEN 'Mercredi' THEN 3
                WHEN 'Jeudi' THEN 4
                WHEN 'Vendredi' THEN 5
                WHEN 'Samedi' THEN 6
                WHEN 'Dimanche' THEN 7
            END
        ")->orderBy('heure_debut');
    }
}

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
        return $this->belongsToMany(Cours::class, 'classe_cours')->withTimestamps();
    }

    public function ressources()
    {
        return $this->hasMany(Ressource::class);
    }
}

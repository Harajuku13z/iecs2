<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    protected $fillable = ['nom', 'code', 'coefficient', 'description'];

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'classe_cours')->withTimestamps();
    }

    public function enseignants()
    {
        return $this->belongsToMany(User::class, 'cours_user')
            ->withPivot('classe_id')
            ->withTimestamps();
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function ressources()
    {
        return $this->hasMany(Ressource::class);
    }
}

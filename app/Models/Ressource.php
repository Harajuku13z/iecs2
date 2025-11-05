<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    protected $fillable = ['titre', 'type', 'contenu', 'lien', 'description', 'cours_id', 'classe_id', 'user_id'];

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'ressource_classe')->withTimestamps();
    }

    public function enseignant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Helper pour obtenir toutes les classes (via pivot ou classe_id legacy)
    public function toutesLesClasses()
    {
        $classes = $this->classes;
        if ($this->classe_id && !$classes->contains('id', $this->classe_id)) {
            $classes->push($this->classe);
        }
        return $classes;
    }
}

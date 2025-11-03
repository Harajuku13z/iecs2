<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    protected $fillable = ['titre', 'type', 'contenu', 'cours_id', 'classe_id', 'user_id'];

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function enseignant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

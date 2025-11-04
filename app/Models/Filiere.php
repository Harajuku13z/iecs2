<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    protected $fillable = ['nom', 'description', 'image'];

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    public function specialites()
    {
        return $this->hasMany(Specialite::class);
    }
}

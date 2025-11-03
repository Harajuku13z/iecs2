<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Niveau extends Model
{
    protected $fillable = ['nom', 'ordre'];

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }
}

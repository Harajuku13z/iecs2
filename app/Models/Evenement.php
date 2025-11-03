<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'lieu',
        'date_debut',
        'date_fin',
        'image',
        'type',
        'publie'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'publie' => 'boolean',
    ];

    public function scopePublie($query)
    {
        return $query->where('publie', true);
    }

    public function scopeAVenir($query)
    {
        return $query->where('date_debut', '>=', now())->orderBy('date_debut');
    }

    public function scopeEnCours($query)
    {
        return $query->where('date_debut', '<=', now())
                    ->where(function($q) {
                        $q->where('date_fin', '>=', now())
                          ->orWhereNull('date_fin');
                    });
    }
}

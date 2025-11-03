<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actualite extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'contenu',
        'image',
        'categorie',
        'publie',
        'date_publication'
    ];

    protected $casts = [
        'date_publication' => 'date',
        'publie' => 'boolean',
    ];

    public function scopePublie($query)
    {
        return $query->where('publie', true);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('date_publication', 'desc');
    }
}

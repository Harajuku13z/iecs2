<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    protected $fillable = ['user_id', 'statut', 'documents', 'commentaire_admin'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

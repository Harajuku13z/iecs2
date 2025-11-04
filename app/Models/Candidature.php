<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    protected $fillable = ['user_id', 'statut', 'documents', 'commentaire_admin', 'evaluation_date', 'verified_by', 'evaluated_by', 'decided_by', 'filiere_id', 'specialite_id', 'classe_id', 'inscription_paid', 'inscription_paid_by', 'inscription_paid_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

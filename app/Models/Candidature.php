<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    protected $fillable = ['user_id', 'statut', 'documents', 'document_statuses', 'commentaire_admin', 'evaluation_date', 'verified_by', 'evaluated_by', 'decided_by', 'filiere_id', 'specialite_id', 'classe_id', 'inscription_paid', 'inscription_paid_by', 'inscription_paid_at'];

    protected $casts = [
        'documents' => 'array',
        'document_statuses' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }
}

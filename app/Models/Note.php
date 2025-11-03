<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['user_id', 'cours_id', 'note', 'type_evaluation', 'semestre'];

    public function etudiant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'titre',
        'contenu',
        'type',
        'destinataire_type',
        'classe_id',
        'user_id',
        'sender_id',
        'role',
        'envoye_email',
        'lu',
        'lu_at',
    ];

    protected $casts = [
        'envoye_email' => 'boolean',
        'lu' => 'boolean',
        'lu_at' => 'datetime',
    ];

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function marquerCommeLu()
    {
        if (!$this->lu) {
            $this->update([
                'lu' => true,
                'lu_at' => now(),
            ]);
        }
    }
}

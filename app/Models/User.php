<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'classe_id',
        'phone',
        'contact_name',
        'contact_phone',
        'profile_photo',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relations
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function candidature()
    {
        return $this->hasOne(Candidature::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function cours()
    {
        return $this->belongsToMany(Cours::class, 'cours_user')
            ->withPivot('classe_id')
            ->withTimestamps();
    }

    public function ressources()
    {
        return $this->hasMany(Ressource::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function notificationsNonLues()
    {
        return $this->notifications()->where('lu', false);
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isEnseignant()
    {
        return $this->role === 'enseignant';
    }

    public function isEtudiant()
    {
        return $this->role === 'etudiant';
    }

    public function isCandidat()
    {
        return $this->role === 'candidat';
    }
}

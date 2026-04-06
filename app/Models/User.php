<?php

namespace App\Models;

use App\Models\Candidature;
use App\Models\Entreprise;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'email',
        'motpasse',
        'role',
    ];

    protected $hidden = [
        'motpasse',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'motpasse' => 'hashed',
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->motpasse;
    }

    /**
     * Get the entreprise profile associated with the user.
     */
    public function entreprise(): HasOne
    {
        return $this->hasOne(Entreprise::class);
    }

    /**
     * Get the candidatures (applications) for the user (candidat).
     */
    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class, 'candidat_id');
    }

    public function isEntreprise(): bool
    {
        return $this->role === 'entreprise';
    }

    public function isCandidat(): bool
    {
        return $this->role === 'candidat';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}

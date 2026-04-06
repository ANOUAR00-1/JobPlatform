<?php

namespace App\Models;

use App\Models\Offre;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entreprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'raison_social',
        'adresse',
        'telephone',
    ];

    /**
     * Get the user that owns the entreprise profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job offers for the entreprise.
     */
    public function offres(): HasMany
    {
        return $this->hasMany(Offre::class);
    }
}

<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entreprise extends Model
{
    use Notifiable, HasFactory;
    
    protected $fillable = [
        'user_id',
        'raison_social',
        'adresse',
        'telephone',
        'logo_url',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function offres(): HasMany
    {
        return $this->hasMany(Offre::class);
    }
}

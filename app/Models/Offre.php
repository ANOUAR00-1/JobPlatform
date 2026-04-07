<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offre extends Model
{
    protected $fillable = [
        'entreprise_id',
        'titre',
        'description',
        'competences_requises',
        'localisation',
        'salaire',
        'type_contrat',
        'date_expiration',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'competences_requises' => 'array',
            'date_expiration' => 'date',
        ];
    }

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }
}

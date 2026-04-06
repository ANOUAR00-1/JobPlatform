<?php

namespace App\Models;

use App\Models\Candidature;
use App\Models\Entreprise;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offre extends Model
{
    use HasFactory;

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

    /**
     * Get the entreprise that owns the job offer.
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    /**
     * Get the candidatures (applications) for the job offer.
     */
    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }
}

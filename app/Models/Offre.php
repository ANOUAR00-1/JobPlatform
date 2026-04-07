<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
=======
>>>>>>> origin/feature/dev

class Offre extends Model
{
    protected $fillable = [
        'entreprise_id',
        'titre',
        'description',
        'competences_requises',
<<<<<<< HEAD
        'localisation',
=======
        'ville_id',
>>>>>>> origin/feature/dev
        'salaire',
        'type_contrat',
        'date_expiration',
        'statut',
    ];

<<<<<<< HEAD
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
=======

    public function ville()
    {
        return $this->belongsTo(Ville::class);
>>>>>>> origin/feature/dev
    }
}

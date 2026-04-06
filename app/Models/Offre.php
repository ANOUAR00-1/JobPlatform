<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    protected $fillable = [
        'entreprise_id',
        'titre',
        'description',
        'competences_requises',
        'ville_id',
        'salaire',
        'type_contrat',
        'date_expiration',
        'statut',
    ];


    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }
}

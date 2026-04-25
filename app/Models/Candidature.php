<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Candidature extends Model
{
  protected $fillable = [
    'offre_id',
    'candidat_id',
    'cv_path',
    'lettre_motivation',
    'statut',
    'note_evaluation', 
    'commentaire_recruteur',
];
    

    public function offre(): BelongsTo
    {
        return $this->belongsTo(Offre::class);
    }

    public function candidat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }

   public function entretien()
    {
        return $this->hasOne(Entretien::class);
    }
}

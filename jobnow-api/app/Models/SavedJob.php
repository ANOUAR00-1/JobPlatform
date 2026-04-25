<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedJob extends Model
{
    protected $fillable = [
        'candidat_id',
        'offre_id',
    ];

    /**
     * Get the candidat that saved the job
     */
    public function candidat(): BelongsTo
    {
        return $this->belongsTo(Candidat::class);
    }

    /**
     * Get the saved job offer
     */
    public function offre(): BelongsTo
    {
        return $this->belongsTo(Offre::class);
    }
}

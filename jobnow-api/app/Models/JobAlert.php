<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobAlert extends Model
{
    protected $fillable = [
        'candidat_id',
        'keywords',
        'contract_types',
        'locations',
        'frequency',
        'is_active',
        'last_sent_at',
    ];

    protected $casts = [
        'contract_types' => 'array',
        'locations' => 'array',
        'is_active' => 'boolean',
        'last_sent_at' => 'datetime',
    ];

    /**
     * Get the candidat that owns the alert
     */
    public function candidat(): BelongsTo
    {
        return $this->belongsTo(Candidat::class);
    }

    /**
     * Check if alert matches a job offer
     */
    public function matchesJob(Offre $offre): bool
    {
        // Check keywords
        if ($this->keywords) {
            $keywords = strtolower($this->keywords);
            $title = strtolower($offre->titre);
            $description = strtolower($offre->description);
            
            if (!str_contains($title, $keywords) && !str_contains($description, $keywords)) {
                return false;
            }
        }

        // Check contract types
        if ($this->contract_types && !in_array($offre->type_contrat, $this->contract_types)) {
            return false;
        }

        // Check locations
        if ($this->locations && !in_array($offre->ville_id, $this->locations)) {
            return false;
        }

        return true;
    }
}

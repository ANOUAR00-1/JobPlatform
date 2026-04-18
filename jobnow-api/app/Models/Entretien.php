<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entretien extends Model
{
    protected $fillable = [
    'date', 
    'horaire', 
    'lieu', 
    'lien', 
    'langue', 
    'confirmerPresence', 
    'candidature_id'
];

public function candidature()
{
    return $this->belongsTo(Candidature::class);
}
    
    
}

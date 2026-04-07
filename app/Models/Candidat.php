<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidat extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

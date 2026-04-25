<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Candidat extends Model
{
    use Notifiable;
    use HasFactory;
    protected $fillable = [
       'nom',
        'prenom',
        'dateNaissance',
        'telephone',
        'email',
        'cv',           
        'photo',
        'experience',  
        'user_id',
        'ville_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }






}

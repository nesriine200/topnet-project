<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'client',
        'etat',
        'produits',
        'matricule_client',
        'description',
        'date',
        'date_validation',
        'commissions',
        'offer_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($opportunity) {
            // Définir 'en cours' comme valeur par défaut pour 'etat' si aucune valeur n'est fournie
            if (is_null($opportunity->etat)) {
                $opportunity->etat = 'en cours';
            }
        });
    }
}

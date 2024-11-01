<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'price', 'details',
    ];

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class);
    }
}

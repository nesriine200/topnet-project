<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * Nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'name', // Nom du rôle
        'show_as',
        'guard_name', // Nom du guard (pour Spatie Laravel Permission, par exemple)
    ];

    /**
     * Désactive les timestamps si non utilisés dans la table.
     *
     * @var bool
     */
    public $timestamps = false;
}

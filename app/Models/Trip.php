<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    /**
     * Les attributs assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'starting_point',
        'ending_point',
        'starting_at',
        'available_places',
        'price',
        'user_id',
    ];

    /**
     * Obtenir l'utilisateur qui possède le trajet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

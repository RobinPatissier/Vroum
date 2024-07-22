<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * Les attributs assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'lastname',
        'firstname',
        'email',
        'password',
        'role',
        'avatar',
        'trip_id',
    ];

    /**
     * Les attributs qui doivent être cachés pour les tableaux.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être castés à des types natifs.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Obtenir les trajets de l'utilisateur.
     */
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * Obtenir le trajet associé à l'utilisateur.
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Obtenir l'identifiant de la clé JWT.
     *
     * @return string
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Obtenir les clés de payload personnalisées pour JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

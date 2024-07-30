<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Models\Contracts\HasName;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="Représentation d'un utilisateur",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Identifiant unique de l'utilisateur",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="lastname",
 *         type="string",
 *         description="Nom de famille de l'utilisateur",
 *         example="Doe"
 *     ),
 *     @OA\Property(
 *         property="firstname",
 *         type="string",
 *         description="Prénom de l'utilisateur",
 *         example="John"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="Adresse email de l'utilisateur",
 *         example="john.doe@example.com"
 *     ),
 *     @OA\Property(
 *         property="role",
 *         type="string",
 *         description="Rôle de l'utilisateur dans le système",
 *         example="user"
 *     ),
 *     @OA\Property(
 *         property="avatar",
 *         type="string",
 *         description="Chemin vers l'avatar de l'utilisateur",
 *         example="avatars/johndoe.jpg"
 *     ),
 *     @OA\Property(
 *         property="trip_id",
 *         type="integer",
 *         description="Identifiant du trajet associé à l'utilisateur",
 *         example=10
 *     ),
 *     @OA\Property(
 *         property="email_verified_at",
 *         type="string",
 *         format="date-time",
 *         description="Date et heure de la vérification de l'email",
 *         example="2023-07-01T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Date et heure de la création de l'utilisateur",
 *         example="2023-07-01T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Date et heure de la dernière mise à jour de l'utilisateur",
 *         example="2023-07-01T12:00:00Z"
 *     )
 * )
 */

class User extends Authenticatable implements HasName
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'lastname',
        'firstname',
        'email',
        'password',
        'role',
        'avatar',
        'trip_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function getFilamentName(): string
    {
        return $this->getAttributeValue('firstname');
    }
}

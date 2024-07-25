<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Trip",
 *     type="object",
 *     title="Trip",
 *     description="Représentation d'un trajet",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Identifiant unique du trajet",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="starting_point",
 *         type="string",
 *         description="Point de départ du trajet",
 *         example="Paris"
 *     ),
 *     @OA\Property(
 *         property="ending_point",
 *         type="string",
 *         description="Point d'arrivée du trajet",
 *         example="London"
 *     ),
 *     @OA\Property(
 *         property="starting_at",
 *         type="string",
 *         format="date-time",
 *         description="Date et heure de départ du trajet",
 *         example="2024-08-01T14:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="available_places",
 *         type="integer",
 *         description="Nombre de places disponibles pour le trajet",
 *         example=3
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="integer",
 *         description="Prix du trajet",
 *         example=50
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         description="Identifiant de l'utilisateur qui a créé le trajet",
 *         example=2
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Date de création du trajet",
 *         example="2024-07-24T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Date de la dernière mise à jour du trajet",
 *         example="2024-07-24T12:00:00Z"
 *     )
 * )
 */


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

<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class TripController extends Controller
{
    private function isValidDate($date)
    {
        // Vérifier si la date est au format 'Y-m-d'
        return \DateTime::createFromFormat('Y-m-d', $date) !== false;
    }

    public function index(Request $request)
    {
        // Validation des champs, les champs sont optionnels
        $request->validate([
            'starting_point' => 'nullable|string|max:255',
            'ending_point' => 'nullable|string|max:255',
            'starting_at' => 'nullable|date',
        ]);

        // Récupération des valeurs des inputs
        $startingPoint = $request->input('starting_point');
        $endingPoint = $request->input('ending_point');
        $startingAt = $request->input('starting_at');

        // Création de la requête de base
        $query = Trip::query();

        // Appliquer les filtres si les valeurs sont fournies
        if ($startingPoint) {
            $query->where('starting_point', 'like', "%$startingPoint%");
        }

        if ($endingPoint) {
            $query->where('ending_point', 'like', "%$endingPoint%");
        }

        if ($startingAt) {
            if ($this->isValidDate($startingAt)) {
                $query->whereDate('starting_at', $startingAt);
            } else {
                return response()->json(['error' => 'Format de date invalide'], 400);
            }
        }

        // Exécution de la requête et obtention des résultats
        $trips = $query->get();

        return response()->json($trips);
    }

    /**
     * Mettre à jour un trajet spécifique dans la base de données.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Trip $trip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trip $trip)
    {
        $validator = Validator::make($request->all(), [
            'starting_point' => 'required|string|max:255',
            'ending_point' => 'required|string|max:255',
            'starting_at' => 'required|date',
            'available_places' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $trip->update($request->all());
        return response()->json($trip);
    }

    /**
     * Supprimer un trajet spécifique de la base de données.
     *
     * @param \App\Models\Trip $trip
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trip $trip)
    {
        $trip->delete();
        return response()->json(null, 204);
    }

}

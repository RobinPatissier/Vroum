<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class TripController extends Controller
{
    /**
     * Afficher une liste de trajets.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trips = Trip::all();
        return response()->json($trips);
    }

    /**
     * Enregistrer un nouveau trajet dans la base de données.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

        // Obtenir l'utilisateur actuellement authentifié
        $user = JWTAuth::parseToken()->authenticate();

        // Créer le trajet
        $trip = Trip::create([
            'starting_point' => $request->starting_point,
            'ending_point' => $request->ending_point,
            'starting_at' => $request->starting_at,
            'available_places' => $request->available_places,
            'price' => $request->price,
            'user_id' => $user->id,
        ]);

        return response()->json($trip, 201);
    }

    /**
     * Afficher un trajet spécifique.
     *
     * @param \App\Models\Trip $trip
     * @return \Illuminate\Http\Response
     */
    public function show(Trip $trip)
    {
        return response()->json($trip);
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

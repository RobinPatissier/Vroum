<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * Afficher une liste de trajets.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Récupérer tous les trajets
        $trips = Trip::all();
        return response()->json($trips);
    }

    /**
     * Afficher le formulaire pour créer un nouveau trajet.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Cette méthode n'est pas nécessaire pour une API RESTful
    }

    /**
     * Enregistrer un nouveau trajet dans la base de données.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'starting_point' => 'required|string|max:255',
            'ending_point' => 'required|string|max:255',
            'starting_at' => 'required|date',
            'available_places' => 'required|integer',
            'price' => 'required|integer',
            'user_id' => 'required|exists:users,id',
        ]);

        $trip = Trip::create($request->all());
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
     * Afficher le formulaire pour modifier un trajet spécifique.
     *
     * @param \App\Models\Trip $trip
     * @return \Illuminate\Http\Response
     */
    public function edit(Trip $trip)
    {
        // Cette méthode n'est pas nécessaire pour une API RESTful
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
        $request->validate([
            'starting_point' => 'required|string|max:255',
            'ending_point' => 'required|string|max:255',
            'starting_at' => 'required|date',
            'available_places' => 'required|integer',
            'price' => 'required|integer',
        ]);

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

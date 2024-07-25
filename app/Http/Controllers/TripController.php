<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;


class TripController extends Controller
{
    /**
     * @OA\Get(
     *     path="/trips",
     *     summary="Get a list of trips",
     *     tags={"Trips"},
     *     @OA\Response(
     *         response=200,
     *         description="List of trips",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Trip")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $trips = Trip::all();
        return response()->json($trips);
    }

    /**
     * @OA\Post(
     *     path="/trips",
     *     summary="Create a new trip",
     *     tags={"Trips"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="starting_point", type="string", example="Paris"),
     *             @OA\Property(property="ending_point", type="string", example="London"),
     *             @OA\Property(property="starting_at", type="string", format="date-time", example="2024-08-01T14:00:00Z"),
     *             @OA\Property(property="available_places", type="integer", example=3),
     *             @OA\Property(property="price", type="integer", example=50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Trip created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Trip")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Validation failed")
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/trips/{id}",
     *     summary="Get a specific trip",
     *     tags={"Trips"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the trip",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Details of a specific trip",
     *         @OA\JsonContent(ref="#/components/schemas/Trip")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trip not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Trip not found")
     *         )
     *     )
     * )
     */
    public function show(Trip $trip)
    {
        return response()->json($trip);
    }

    /**
     * @OA\Put(
     *     path="/trips/{id}",
     *     summary="Update a specific trip",
     *     tags={"Trips"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the trip",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="starting_point", type="string", example="Paris"),
     *             @OA\Property(property="ending_point", type="string", example="Berlin"),
     *             @OA\Property(property="starting_at", type="string", format="date-time", example="2024-08-02T14:00:00Z"),
     *             @OA\Property(property="available_places", type="integer", example=2),
     *             @OA\Property(property="price", type="integer", example=45)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Trip updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Trip")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Validation failed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trip not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Trip not found")
     *         )
     *     )
     * )
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
     * @OA\Delete(
     *     path="/trips/{id}",
     *     summary="Delete a specific trip",
     *     tags={"Trips"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the trip",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Trip deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trip not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Trip not found")
     *         )
     *     )
     * )
     */
    public function destroy(Trip $trip)
    {
        $trip->delete();
        return response()->json(null, 204);
    }
}

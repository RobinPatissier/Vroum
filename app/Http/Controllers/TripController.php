<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use DateTime;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/trips",
     *     summary="Get all trips",
     *     tags={"Trips"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Trip"))
     *     )
     * )
     */

     public function index(Request $request)
     {
         $query = Trip::query();
 
         if ($request->has('start')) {
             $query->where('starting_point', 'like', '%' . $request->start . '%');
         }
 
         if ($request->has('end')) {
             $query->where('ending_point', 'like', '%' . $request->end . '%');
         }
 
         if ($request->has('date')) {
             $query->whereDate('starting_at', $request->date);
         }
 
         return $query->get();
     }
    
    /**
     * @OA\Get(
     *     path="/api/trips/{id}",
     *     summary="Get a trip by ID",
     *     tags={"Trips"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Trip")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trip not found"
     *     )
     * )
     */

    public function show($id)
    {
        return Trip::findOrFail($id);
    }
    /**
     * @OA\Post(
     *     path="/api/trips",
     *     summary="Create a new trip",
     *     tags={"Trips"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Trip")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Trip created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Trip")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'starting_point' => 'required|string|max:255',
            'ending_point' => 'required|string|max:255',
            'starting_at' => 'required|date',
            'available_places' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
        ]);

        // dd($request->user);

        $trip = $request->user()->trips()->create($validated);

        // $trip = Auth::user()->trips()->create($validated);

        // $trip = Trip::create([
        //     'starting_point' => $request->input('starting_point'),
        //     'ending_point' => $request->input('ending_point'),
        //     'starting_at' => $request->input('starting_at'),
        //     'available_places' => $request->input('available_places'),
        //     'price' => $request->input('price'),
        //     'user_id' => auth()->user()->id, // Associe le trajet à l'utilisateur connecté
        // ]);

        return response()->json($trip, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/trips/{id}",
     *     summary="Update a trip",
     *     tags={"Trips"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Trip")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Trip updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Trip")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trip not found"
     *     )
     * )
     */

    public function update(Request $request, $id)
    {
        $trip = Trip::findOrFail($id);

        if ($trip->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'starting_point' => 'sometimes|required|string|max:255',
            'ending_point' => 'sometimes|required|string|max:255',
            'starting_at' => 'sometimes|required|date',
            'available_places' => 'sometimes|required|integer|min:1',
            'price' => 'sometimes|required|integer|min:0',
        ]);

        $trip->update($validated);

        return response()->json($trip);
    }
    /**
     * @OA\Delete(
     *     path="/api/trips/{id}",
     *     summary="Delete a trip",
     *     tags={"Trips"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Trip deleted successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trip not found"
     *     )
     * )
     */

    public function destroy($id)
    {
        $trip = Trip::findOrFail($id);

        if ($trip->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $trip->delete();

        return response()->json(['message' => 'Trip deleted']);
    }
    /**
     * @OA\Get(
     *     path="/api/trips/search",
     *     summary="Search trips",
     *     tags={"Trips"},
     *     @OA\Parameter(
     *         name="starting_point",
     *         in="query",
     *         description="Starting point",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="ending_point",
     *         in="query",
     *         description="Ending point",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="starting_date",
     *         in="query",
     *         description="Starting date",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Trip"))
     *     )
     * )
     */

}
<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Parking::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'total_spots' => 'required|integer|min:1',
        ]);

        $parking = Parking::create([
            'name' => $request->name,
            'location' => $request->location,
            'total_spots' => $request->total_spots,
            'available_spots' => $request->total_spots, // Toutes les places sont libres au début
        ]);

        return response()->json($parking, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $parking = Parking::findOrFail($id);

        return response()->json($parking, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $parking = Parking::findOrFail($id);

        $request->validate([
            'name' => 'string|max:255',
            'location' => 'string',
            'total_spots' => 'integer|min:1',
        ]);

        $parking->update($request->all());

        return response()->json($parking, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $parking = Parking::find($id);

        if (!$parking) {
            return response()->json(['message' => 'Parking non trouvé'], 404);
        }

        $parking->delete();
        return response()->json(['message' => 'Parking supprimé avec succès'], 200);
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $query = $request->query('query');

        $parkings = Parking::where('name', 'ILIKE', "%$query%")
            ->orWhere('location', 'ILIKE', "%$query%")
            ->get();

        return response()->json($parkings, 200);
    }
}

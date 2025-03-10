<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Parking;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $reservations = Reservation::where('user_id', $user->id)->get();

        return response()->json($reservations, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'parking_id' => 'required|exists:parkings,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        $parking = Parking::findOrFail($request->parking_id);

        if ($parking->available_spots <= 0) {
            return response()->json(['message' => "Aucune place disponible dans ce parking."], 400);
        }

        // creer la reservation
        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'parking_id' => $request->parking_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'confirmed',
        ]);

        // diminuer le nombre de places disponibles
        $parking->decrement('available_spots');

        return response()->json($reservation, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $reservation = Reservation::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($reservation, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $reservation = Reservation::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'start_time' => 'date|after:now',
            'end_date' => 'date|after:start_time',
        ]);

        $reservation->update($request->only(['start_time', 'end_time']));

        return response()->json($reservation, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $reservation = Reservation::where('user_id', Auth::id())->findOrFail($id);

        // liberer la place de parking
        $parking = Parking::findOrFail($reservation->parking_id);
        $parking->increment('available_spots');

        $reservation->delete();

        return response()->json(['message' => 'Réservation annulée avec succès'], 200);
    }
}

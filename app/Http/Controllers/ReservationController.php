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
    /**
     * @OA\Get(
     *     path="/api/reservations",
     *     summary="Liste des réservations d'un utilisateur",
     *     tags={"Réservations"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des réservations retournée avec succès",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Reservation"))
     *     )
     * )
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
    /**
     * @OA\Post(
     *     path="/api/reservations",
     *     summary="Créer une réservation",
     *     tags={"Réservations"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"parking_id", "start_time", "end_time"},
     *             @OA\Property(property="parking_id", type="integer", example=1),
     *             @OA\Property(property="start_time", type="string", format="date-time", example="2025-03-15T08:00:00Z"),
     *             @OA\Property(property="end_time", type="string", format="date-time", example="2025-03-15T18:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Réservation créée avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Reservation")
     *     ),
     *     @OA\Response(response=400, description="Aucune place disponible ou données invalides")
     * )
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
    /**
     * @OA\Get(
     *     path="/api/reservations/{id}",
     *     summary="Afficher une réservation spécifique",
     *     tags={"Réservations"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la réservation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la réservation retournés avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Reservation")
     *     ),
     *     @OA\Response(response=404, description="Réservation non trouvée")
     * )
     */
    public function show($id)
    {
        $reservation = Reservation::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($reservation, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/api/reservations/{id}",
     *     summary="Modifier une réservation",
     *     tags={"Réservations"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la réservation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="start_time", type="string", format="date-time", example="2025-03-15T09:00:00Z"),
     *             @OA\Property(property="end_time", type="string", format="date-time", example="2025-03-15T19:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Réservation mise à jour avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Reservation")
     *     ),
     *     @OA\Response(response=404, description="Réservation non trouvée")
     * )
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
    /**
     * @OA\Delete(
     *     path="/api/reservations/{id}",
     *     summary="Supprimer une réservation",
     *     tags={"Réservations"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la réservation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Réservation annulée avec succès",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Réservation annulée avec succès"))
     *     ),
     *     @OA\Response(response=404, description="Réservation non trouvée")
     * )
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

    /**
     * @OA\Get(
     *     path="/api/reservations/history",
     *     summary="Afficher l'historique des réservations",
     *     tags={"Réservations"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Historique des réservations retourné avec succès",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Reservation"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Aucune réservation trouvée"
     *     )
     * )
     */
    public function history()
    {
        $user = Auth::user();

        $reservations = Reservation::where('user_id', $user->id)
            ->orderBy('start_time', 'desc')
            ->get();

            if ($reservations->isEmpty()) {
                return response()->json(['message' => 'Aucune réservation trouvée.'], 404);
            }

        return response()->json($reservations, 200);
    }
}

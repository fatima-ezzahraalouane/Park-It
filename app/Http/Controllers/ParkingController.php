<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
 * @OA\Get(
 *     path="/api/parkings",
 *     summary="Liste des parkings",
 *     tags={"Parkings"},
 *     @OA\Response(
 *         response=200,
 *         description="Liste des parkings retournée avec succès",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Parking"))
 *     )
 * )
 */
    public function index()
    {
        return response()->json(Parking::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
 * @OA\Post(
 *     path="/api/parkings",
 *     summary="Créer un parking",
 *     tags={"Parkings"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "location", "total_spots"},
 *             @OA\Property(property="name", type="string", example="Parking Hassan II"),
 *             @OA\Property(property="location", type="string", example="Rue Hassan II, Marrakech"),
 *             @OA\Property(property="total_spots", type="integer", example=50)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Parking créé avec succès",
 *         @OA\JsonContent(ref="#/components/schemas/Parking")
 *     ),
 *     @OA\Response(response=400, description="Données invalides")
 * )
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
    /**
 * @OA\Get(
 *     path="/api/parkings/{id}",
 *     summary="Afficher un parking spécifique",
 *     tags={"Parkings"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID du parking",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Détails du parking retournés avec succès",
 *         @OA\JsonContent(ref="#/components/schemas/Parking")
 *     ),
 *     @OA\Response(response=404, description="Parking non trouvé")
 * )
 */
    public function show($id)
    {
        $parking = Parking::findOrFail($id);

        return response()->json($parking, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
 * @OA\Put(
 *     path="/api/parkings/{id}",
 *     summary="Mettre à jour un parking",
 *     tags={"Parkings"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID du parking",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Parking Centre"),
 *             @OA\Property(property="location", type="string", example="Boulevard Mohamed V, Casablanca"),
 *             @OA\Property(property="total_spots", type="integer", example=100)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Parking mis à jour avec succès",
 *         @OA\JsonContent(ref="#/components/schemas/Parking")
 *     ),
 *     @OA\Response(response=404, description="Parking non trouvé")
 * )
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
    /**
 * @OA\Delete(
 *     path="/api/parkings/{id}",
 *     summary="Supprimer un parking",
 *     tags={"Parkings"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID du parking",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Parking supprimé avec succès",
 *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Parking supprimé avec succès"))
 *     ),
 *     @OA\Response(response=404, description="Parking non trouvé")
 * )
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

    /**
 * @OA\Get(
 *     path="/api/parkings/search",
 *     summary="Rechercher un parking par nom ou localisation",
 *     tags={"Parkings"},
 *     @OA\Parameter(
 *         name="query",
 *         in="query",
 *         required=true,
 *         description="Nom ou localisation du parking",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Liste des parkings correspondant à la recherche",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Parking"))
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Aucun parking trouvé"
 *     )
 * )
 */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $query = $request->query('query');

        $parkings = Parking::where('name', 'ILIKE', "%$query%")
            ->orWhere('location', 'ILIKE', "%$query%")
            ->get();

            if ($parkings->isEmpty()) {
                return response()->json(['message' => 'Aucun parking trouvé pour cette recherche.'], 404);
            }

        return response()->json($parkings, 200);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Parking",
 *     description="Modèle de Parking",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Parking Hassan II"),
 *     @OA\Property(property="location", type="string", example="Rue Hassan II, Marrakech"),
 *     @OA\Property(property="total_spots", type="integer", example=50),
 *     @OA\Property(property="available_spots", type="integer", example=45)
 * )
 */
class Parking extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location', 'total_spots', 'available_spots'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

}

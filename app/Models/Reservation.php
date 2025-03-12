<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Reservation",
 *     description="Modèle de Réservation",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=2),
 *     @OA\Property(property="parking_id", type="integer", example=1),
 *     @OA\Property(property="start_time", type="string", format="date-time", example="2025-03-15T08:00:00Z"),
 *     @OA\Property(property="end_time", type="string", format="date-time", example="2025-03-15T18:00:00Z"),
 *     @OA\Property(property="status", type="string", example="confirmed")
 * )
 */
class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'parking_id', 'start_time', 'end_time', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parking()
    {
        return $this->belongsTo(Parking::class);
    }
}

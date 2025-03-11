<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parking;
use App\Models\Reservation;

class AdminStatsController extends Controller
{
    public function stats()
    {
        $totalParkings = Parking::count();
        $totalReservations = Reservation::count();
        $totalAvailableSpots = Parking::sum('available_spots');

        $cancelledReservations = Reservation::where('status', 'cancelled')->count();
        $expiredReservations = Reservation::where('status', 'expired')->count();

        // calcul du pourcentage d'occupation des parkings
        $totalSpots = Parking::sum('total_spots');
        $occupiedSpots = $totalSpots - $totalAvailableSpots;
        $occupancyRate = $totalSpots > 0 ? round(($occupiedSpots / $totalSpots) * 100, 2) : 0;

        return response()->json([
            'total_parkings' => $totalParkings,
            'total_reservations' => $totalReservations,
            'total_available_spots' => $totalAvailableSpots,
            'cancelled_reservations' => $cancelledReservations,
            'expired_reservations' => $expiredReservations,
            'occupancy_rate' => $occupancyRate . '%',
        ], 200);
    }
}

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
    }
}

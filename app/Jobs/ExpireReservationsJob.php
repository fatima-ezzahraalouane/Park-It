<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;
use App\Models\Parking;
use Carbon\Carbon;

class ExpireReservationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now = Carbon::now();

        // trouver toutes les reservations expirees
        $reservations = Reservation::where('status', 'confirmed')
            ->where('end_time', '<', $now)
            ->get();

        foreach ($reservations as $reservation) {
            // mettre le statut de la réservation à "expired"
            $reservation->update(['status' => 'expired']);

            // libérer la place de parking
            $parking = Parking::find($reservation->parking_id);
            if ($parking) {
                $parking->increment('available_spots');
            }
        }
    }
}

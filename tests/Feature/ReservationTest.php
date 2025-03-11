<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Parking;
use App\Models\Reservation;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    // test pour creer une reservation
    public function test_user_can_make_a_reservation()
{
    $user = User::factory()->create();
    $parking = Parking::factory()->create(['available_spots' => 5]);

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/reservations', [
        'parking_id' => $parking->id,
        'start_time' => now()->addHours(1),
        'end_time' => now()->addHours(3)
    ]);

    $response->assertStatus(201)
            ->assertJsonStructure(['id', 'status']);
}
}

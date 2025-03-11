<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Parking;

class ParkingTest extends TestCase
{
    use RefreshDatabase;

    // test pour recuperer la liste des parkings
    public function test_can_fetch_all_parkings()
    {
        Parking::factory()->count(3)->create();

        $response = $this->getJson('/api/parkings');

        $response->assertStatus(200)
                ->assertJsonCount(3);
    }
}

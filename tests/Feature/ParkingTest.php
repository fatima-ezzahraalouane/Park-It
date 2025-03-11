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

    // test pour afficher un parking specifique
    public function test_can_view_specific_parking()
    {
        $parking = Parking::factory()->create([
            'name' => 'Parking Test',
            'location' => 'Rue Hassan II, Marrakech'
        ]);

        $response = $this->getJson("/api/parkings/{$parking->id}");

        $response->assertStatus(200)
                ->assertJson(['id' => $parking->id, 'name' => 'Parking Test']);
    }

    // test pour ajouter un nouveau parking
    public function test_admin_can_create_parking()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/parkings', [
            'name' => 'Parking Test',
            'location' => 'Rue Hassan II, Marrakech',
            'total_spots' => 50,
            'available_spots' => 50
        ]);

        $response->assertStatus(201)
                ->assertJson(['name' => 'Parking Test']);
    }

    // test pour modifier un parking
    public function test_admin_can_update_parking()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $parking = Parking::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')->putJson("/api/parkings/{$parking->id}", [
            'name' => 'Updated Parking Name',
            'location' => 'Updated Location',
            'total_spots' => 100,
            'available_spots' => 80
        ]);

        $response->assertStatus(200)
                ->assertJson(['name' => 'Updated Parking Name']);
    }

    // test pour supprimer un parking
    public function test_admin_can_delete_parking()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $parking = Parking::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')->deleteJson("/api/parkings/{$parking->id}");

        $response->assertStatus(200)
                ->assertJson(['message' => 'Parking supprimé avec succès']);
    }
}

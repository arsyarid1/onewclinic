<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $clinic = Clinic::create([
            'name' => 'Onew Clinic',
            'clinic_code' => 'ONEW',
            'primary_color' => '#0284C7',
            'secondary_color' => '#0EA5E9',
        ]);

        $user = User::create([
            'clinic_id' => $clinic->id,
            'name' => 'Dr. Admin',
            'email' => 'admin@onewclinic.com',
            'password' => 'password',
            'role' => 'superadmin',
        ]);

        // Unauthenticated guests visiting dashboard get redirected to login
        $guestResponse = $this->get(route('dashboard'));
        $guestResponse->assertRedirect('/login');

        // Authenticated user can access dashboard
        $dashboardResponse = $this->actingAs($user)->get(route('dashboard'));
        $dashboardResponse->assertStatus(200);
    }
}

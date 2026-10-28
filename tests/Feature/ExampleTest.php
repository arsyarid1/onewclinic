<?php

namespace Tests\Feature;

use App\Models\Clinic;
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
        Clinic::create([
            'name' => 'Onew Clinic',
            'clinic_code' => 'ONEW',
            'primary_color' => '#0284C7',
            'secondary_color' => '#0EA5E9',
        ]);

        $response = $this->get('/');

        $response->assertRedirect(route('dashboard'));

        $dashboardResponse = $this->get(route('dashboard'));
        $dashboardResponse->assertStatus(200);
    }
}

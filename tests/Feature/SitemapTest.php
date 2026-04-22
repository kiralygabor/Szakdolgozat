<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test all public routes to ensure they load and are fast.
     */
    public function test_public_routes_load(): void
    {
        $routes = [
            'index',
            'howitworks',
            'category',
            'tasks',
            'login',
            'register',
            'password.request',
        ];

        foreach ($routes as $routeName) {
            $start = microtime(true);
            $response = $this->get(route($routeName));
            $duration = (microtime(true) - $start) * 1000;

            $response->assertStatus(200);
            
            // Log the result to console during test execution
            echo "\nRoute: " . str_pad($routeName, 20) . " | Status: 200 | Time: " . number_format($duration, 2) . "ms";
        }
    }

    /**
     * Test authenticated routes.
     */
    public function test_authenticated_routes_load(): void
    {
        $user = User::first() ?? User::factory()->create();

        $routes = [
            'my-tasks',
            'messages',
            'notifications',
            'profile',
        ];

        foreach ($routes as $routeName) {
            $start = microtime(true);
            $response = $this->actingAs($user)->get(route($routeName));
            $duration = (microtime(true) - $start) * 1000;

            $response->assertStatus(200);
            echo "\nAuth Route: " . str_pad($routeName, 15) . " | Status: 200 | Time: " . number_format($duration, 2) . "ms";
        }
    }
}

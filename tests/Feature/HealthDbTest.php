<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class HealthDbTest extends TestCase
{
    public function test_health_db_requires_secret()
    {
        $response = $this->get('/health/db');
        $response->assertStatus(401);
    }

    public function test_health_db_with_valid_secret_returns_ok()
    {
        $response = $this->get('/health/db?secret=test-secret');
        $response->assertStatus(200);
        $response->assertJson(['status' => 'ok', 'database' => true]);
    }

    public function test_health_db_handles_db_exception()
    {
        // Force DB connection to throw
        DB::shouldReceive('connection')->andThrow(new \Exception('boom'));

        $response = $this->get('/health/db?secret=test-secret');
        $response->assertStatus(500);
        $response->assertJson(['status' => 'error', 'database' => false]);
    }
}

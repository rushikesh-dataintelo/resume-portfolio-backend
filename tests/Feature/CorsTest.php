<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CorsTest extends TestCase
{
    /** @test */
    public function preflight_from_vercel_preview_returns_request_origin()
    {
        $origin = 'https://some-preview-abc.vercel.app';

        $response = $this->call('OPTIONS', '/api/register', [], [], [], [
            'HTTP_Origin' => $origin,
            'HTTP_Access-Control-Request-Method' => 'POST'
        ]);

        $response->assertStatus(204);

        $this->assertTrue($response->headers->has('Access-Control-Allow-Origin'));
        $this->assertEquals($origin, $response->headers->get('Access-Control-Allow-Origin'));
    }
}

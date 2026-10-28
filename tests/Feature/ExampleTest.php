<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase; // <-- IMPORTANTE: aggiungi questa riga!
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase; // <-- IMPORTANTE: aggiungi questa riga dentro la classe!

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}

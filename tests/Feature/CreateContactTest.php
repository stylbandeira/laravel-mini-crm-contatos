<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateContactTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->postJson('/api/contacts', [
            'name' => 'Maria de Lourdes',
            'email' => 'maria@email.com',
            'phone' => '87996236447'
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('contacts', [
            'email' => 'maria@email.com',
        ]);
    }
}

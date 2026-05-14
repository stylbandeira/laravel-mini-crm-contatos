<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateContactTest extends TestCase
{
    /**
     * Assert that a contact can be created.
     */
    public function test_create_contact_is_ok(): void
    {
        $response = $this->postJson('/api/contacts', [
            'name' => 'Maria de Lourdes',
            'email' => 'maria@email.com',
            'phone' => '87996236447',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('contacts', [
            'email' => 'maria@email.com',
        ]);
    }

    /**
     * Assert that a created contact has its default fields.
     */
    public function test_created_contact_has_default_fields(): void
    {
        $response = $this->postJson('/api/contacts', [
            'name' => 'Maria de Lourdes',
            'email' => 'maria@email.com',
            'phone' => '87996236447',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('contacts', [
            'email' => 'maria@email.com',
            'status' => 'pending',
            'score' => '0'
        ]);
    }

    /**
     * Assert that a required fields are required.
     */
    public function test_validation_required_fields(): void
    {
        $response = $this->postJson('/api/contacts', [
            'email' => 'maria@email.com',
            'phone' => '87996236447',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');

        $response = $this->postJson('/api/contacts', [
            'name' => 'Maria de Lourdes',
            'phone' => '87996236447',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');

        $response = $this->postJson('/api/contacts', [
            'name' => 'Maria de Lourdes',
            'email' => 'maria@email.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('phone');
    }

    /**
     * Assert that a email is an email.
     */
    public function test_validation_email_type(): void
    {
        $response = $this->postJson('/api/contacts', [
            'name' => 'Maria de Lourdes',
            'email' => 'mariaemail.com',
            'phone' => '87996236447',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }
}

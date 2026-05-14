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
     * Assert that a created contact with no name fails.
     */
    public function test_created_contact_with_no_name_dont_pass(): void
    {
        $response = $this->postJson('/api/contacts', [
            'email' => 'maria@email.com',
            'phone' => '87996236447',
        ]);

        $response->assertJsonValidationErrorFor('name');
    }

    /**
     * Assert that a created contact with no email fails.
     */
    public function test_created_contact_with_no_email_dont_pass(): void
    {
        $response = $this->postJson('/api/contacts', [
            'name' => 'Maria de Lourdes',
            'phone' => '87996236447',
        ]);

        $response->assertJsonValidationErrorFor('email');
    }

    /**
     * Assert that a created contact with no phone fails.
     */
    public function test_created_contact_with_no_phone_dont_pass(): void
    {
        $response = $this->postJson('/api/contacts', [
            'name' => 'Maria de Lourdes',
            'email' => 'maria@email.com',
        ]);

        $response->assertJsonValidationErrorFor('phone');
    }
}

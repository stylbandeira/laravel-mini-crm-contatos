<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateContactTest extends TestCase
{
    use RefreshDatabase;
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

        $this->assertDatabaseHas('contact', [
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

        $this->assertDatabaseHas('contact', [
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
     * Assert that only a contact with an valid email can be registered.
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

        Contact::factory()->create([
            'email' => 'maria@gmail.com'
        ]);

        $response = $this->postJson('/api/contacts', [
            'name' => 'Maria de Lourdes',
            'email' => 'maria@gmail.com',
            'phone' => '87996236447',
        ]);

        $this->assertDatabaseCount('contact', 1);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }
}

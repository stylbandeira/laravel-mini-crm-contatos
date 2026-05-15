<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ContactTestData;
use Tests\TestCase;

class CreateContactTest extends TestCase
{
    use RefreshDatabase, ContactTestData;
    protected $route = '/api/contacts';

    /**
     * Assert that a contact can be created.
     */
    public function test_create_contact_is_ok(): void
    {
        $response = $this->postJson($this->route, $this->validContactPayload());

        $response->assertCreated();

        $this->assertDatabaseHas('contact', [
            'email' => 'maria@gmail.com',
        ]);
    }

    /**
     * Tests that email is unique
     *
     * @return void
     */
    public function test_unique_email_validation(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->postJson($this->route, $this->validContactPayload([
            'email' => $contact->email
        ]));

        $response->assertJsonValidationErrors('email');
    }

    /**
     * Assert that a created contact has its default fields.
     */
    public function test_created_contact_has_default_fields(): void
    {
        $response = $this->postJson($this->route, $this->validContactPayload());

        $response->assertCreated();

        $this->assertDatabaseHas('contact', [
            'email' => 'maria@gmail.com',
            'status' => 'pending',
            'score' => '0'
        ]);
    }

    /**
     * Assert that a required fields are required.
     */
    public function test_validation_required_fields(): void
    {
        $response = $this->postJson($this->route, [
            'email' => 'maria@gmail.com',
            'phone' => '87996236447',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');

        $response = $this->postJson($this->route, [
            'name' => 'Maria de Lourdes',
            'phone' => '87996236447',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');

        $response = $this->postJson($this->route, [
            'name' => 'Maria de Lourdes',
            'email' => 'maria@gmail.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('phone');
    }

    /**
     * Assert that only a contact with an valid email can be registered.
     */
    public function test_validation_email_type(): void
    {
        $response = $this->postJson($this->route, $this->validContactPayload([
            'email' => 'mariaemail.com',
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');

        $response = $this->postJson($this->route, $this->validContactPayload());

        $this->assertDatabaseCount('contact', 1);

        $response->assertStatus(201);
    }
}

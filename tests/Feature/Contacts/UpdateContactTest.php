<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ContactTestData;
use Tests\TestCase;

class UpdateContactTest extends TestCase
{
    use ContactTestData, RefreshDatabase;

    protected $route = "/api/contacts/";

    /**
     * Assert that only a contact with an valid email can be registered.
     */
    public function test_validation_email_type(): void
    {
        $contact = Contact::factory()->create([
            'email' => 'pedro@gmail.com'
        ]);

        $response = $this->putJson($this->route . $contact->id, [
            'email' => 'mariaemail.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');

        $response = $this->putJson($this->route . $contact->id, [
            'email' => 'maria@gmail.com',
        ]);

        $this->assertDatabaseCount('contact', 1);

        $response->assertStatus(200);
    }

    /**
     * Tests that email is unique
     *
     * @return void
     */
    public function test_unique_email_validation(): void
    {
        $contact_one = Contact::factory()->create([
            'email' => 'email1@gmail.com'
        ]);
        $contact_two = Contact::factory()->create([
            'email' => 'email2@gmail.com'
        ]);

        $response = $this->postJson($this->route . $contact_two->id, [
            'email' => $contact_one->email
        ]);

        $response->assertJsonValidationErrors('email');
    }

    /**
     * Tests if a user can edit editable fields
     *
     * @return void
     */
    public function test_user_can_edit_fields(): void
    {
        $contact = Contact::factory()->create();

        $fields = [
            'name' => 'Styl Bandeira',
            'email' => 'stylbandeira@gmail.com',
            'phone' => '87996236447',
        ];

        foreach ($fields as $field => $value) {
            $response = $this->postJson($this->route . $contact->id, [
                $field => $value
            ]);

            $this->assertDatabaseCount('contact', 1);

            $response->assertStatus(200);
        }

        $this->assertDatabaseCount('contact', 1);
    }

    /**
     * Tests if a user cant edit not editable fields
     *
     * @return void
     */
    public function test_user_cant_edit_fields(): void
    {
        $contact = Contact::factory()->create();

        $fields = [
            'score' => 936,
            'status' => 'active',
        ];

        foreach ($fields as $field => $value) {
            $response = $this->postJson($this->route . $contact->id, [
                $field => $value
            ]);

            $this->assertDatabaseCount('contact', 1);

            $response->assertStatus(422);
        }

        $this->assertDatabaseCount('contact', 1);
    }
}

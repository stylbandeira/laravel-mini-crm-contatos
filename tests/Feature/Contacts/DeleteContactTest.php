<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Override;
use Tests\TestCase;

class DeleteContactTest extends TestCase
{
    use RefreshDatabase;

    protected $route = '/api/contacts/';

    /**
     * Tests that a contact can be deleted and its not available for other routes (show/index).
     */
    public function test_contact_is_deleted(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->delete($this->route . $contact->id);

        $response->assertStatus(200);

        $response = $this->get($this->route . $contact->id);

        $response->assertStatus(404);

        $response = $this->get($this->route);

        $response->assertJsonCount(0, 'data');
    }

    /**
     * Tests that a deleted contact is softDeleted, appearing on database even after deletion
     *
     * @return void
     */
    public function test_deleted_contact_is_soft_deleted(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->delete($this->route . $contact->id);

        $response->assertStatus(200);

        $this->assertDatabaseCount('contact', 1);
    }
}

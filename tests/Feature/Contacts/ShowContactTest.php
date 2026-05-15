<?php

namespace Tests\Feature;

use App\Http\Resources\BaseContactResource;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowContactTest extends TestCase
{
    use RefreshDatabase;

    protected $route = '/api/contacts/';

    /**
     * Tests the resource return fields.
     */
    public function test_resource_fields(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->get($this->route . $contact->id);

        $response
            ->assertJsonFragment(
                (new BaseContactResource($contact))->response()->getData(true)
            )
            ->assertStatus(200);
    }

    /**
     * Tests that a not existing contact returns an error
     *
     * @return void
     */
    public function test_404_not_found(): void
    {
        Contact::factory()->create([
            'id' => 21
        ]);

        $response = $this->get($this->route . 2);

        $response->assertStatus(404);

        $this->assertDatabaseCount('contact', 1);
    }
}

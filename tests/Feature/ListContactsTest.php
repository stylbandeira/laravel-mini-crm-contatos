<?php

namespace Tests\Feature;

use App\Http\Resources\BaseContactResource;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ListContactsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Return a list of contacts.
     */
    public function test_contacts_list_total_count(): void
    {
        Contact::factory()->count(10)->create();

        $response = $this->getJson('/api/contacts');

        $this->assertDatabaseCount('contact', 10);

        $response->assertJsonFragment([
            "total" => 10
        ])
            ->assertStatus(200);
    }

    /**
     * Return if a list of contacts returns paginated.
     */
    public function test_pagination_list(): void
    {
        Contact::factory()->count(2)->create();

        $response = $this->getJson('/api/contacts');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                ]
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next'
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links' => [
                    '*' => [
                        'url',
                        'label',
                        'page',
                        'active'
                    ]
                ],

                'path',
                'per_page',
                'to',
                'total',
            ]
        ])
            ->assertStatus(200);
    }

    /**
     * Return if a list of contacts returns paginated.
     */
    public function test_returned_data_fields(): void
    {
        $contact = Contact::factory()->count(2)->create();

        $response = $this->getJson('/api/contacts');

        $response->assertJsonFragment(
            (BaseContactResource::collection($contact))->response()->getData(true)
        )
            ->assertStatus(200);
    }
}

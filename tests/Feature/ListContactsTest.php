<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
            'current_page',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ]
            ],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ])
            ->assertStatus(200);
    }
}

<?php

namespace Tests\Feature;

use App\Http\Resources\BaseContactResource;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowContactTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_pagination_fields(): void
    {
        Contact::factory()->create();

        $response = $this->get('/api/contacts');

        $response
            ->assertJsonStructure([
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
     * A basic feature test example.
     */
    public function test_resource_fields(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->get('/api/contacts');

        $response->assertJsonFragment(
            (new BaseContactResource($contact))->response()->getData(true)
        )
            ->assertStatus(200);
    }
}

<?php

namespace Tests\Feature;

use App\Jobs\ProcessContactScoreJob;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Override;
use Tests\TestCase;

class ProcessScoreTest extends TestCase
{
    use RefreshDatabase;

    protected Contact $contact;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->contact = Contact::factory()->create();
    }
    /**
     * Tests that trying to process a not existing contact is going to throw an error.
     */
    public function test_404_not_found(): void
    {
        $response = $this->post($this->getRoute(20));

        $response->assertStatus(404);
    }

    public function test_a_job_is_created(): void
    {
        Queue::fake();

        $response = $this->post($this->getRoute($this->contact->id));

        $response->assertStatus(202);

        Queue::assertPushed(ProcessContactScoreJob::class);
    }

    private function getRoute($id)
    {
        return '/api/contacts/' . $id . '/process-score';
    }
}

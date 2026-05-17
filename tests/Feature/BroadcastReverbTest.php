<?php

namespace Tests\Feature;

use App\Events\ContactScoreProcessedEvent;
use App\Models\Contact;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BroadcastReverbTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_broadcasts_on_contact_channel(): void
    {
        $contact = Contact::factory()->create();

        $event = new ContactScoreProcessedEvent($contact);

        $channels = $event->broadcastOn();

        $this->assertInstanceOf(Channel::class, $channels);

        $this->assertEquals(
            'contacts.' . $contact->id,
            $channels->name
        );
    }

    public function test_event_broadcast_payload_contains_contact_data(): void
    {
        $contact = Contact::factory()->create([
            'score' => 60,
            'status' => 'active',
        ]);

        $event = new ContactScoreProcessedEvent($contact);

        $this->assertEquals([
            'id' => $contact->id,
            'email' => $contact->email,
            'score' => 60,
            'status' => 'active',
            'processed_at' => $contact->processed_at,
        ], $event->broadcastWith());
    }
}

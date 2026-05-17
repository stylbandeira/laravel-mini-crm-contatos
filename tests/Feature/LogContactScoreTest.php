<?php

namespace Tests\Feature;

use App\Events\ContactScoreProcessedEvent;
use App\Listeners\LogContactScoreProcessed;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class LogContactScoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_listener_writes_contact_log(): void
    {
        Log::spy();

        Log::shouldReceive('channel')
            ->with('contact')
            ->andReturnSelf();

        $contact = Contact::factory()->create([
            'score' => 60,
            'status' => 'active',
        ]);

        $event = new ContactScoreProcessedEvent($contact);

        $listener = new LogContactScoreProcessed();

        $listener->handle($event);

        Log::assertLogged('info', function ($message, $context) use ($contact) {

            return $message === 'Contact score processed'
                && $context['id'] === $contact->id
                && $context['email'] === $contact->email
                && $context['score'] === $contact->score
                && $context['status'] === $contact->status;
        });

        $this->assertTrue(true);
    }
}

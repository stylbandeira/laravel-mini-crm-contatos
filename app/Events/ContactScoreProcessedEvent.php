<?php

namespace App\Events;

use App\Models\Contact;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContactScoreProcessedEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Contact $contact
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('contacts.' . $this->contact->id);
    }

    public function broadcastAs(): string
    {
        return 'contact.score.processed';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->contact->id,
            'email' => $this->contact->email,
            'score' => $this->contact->score,
            'status' => $this->contact->status,
            'processed_at' => $this->contact->processed_at,
        ];
    }
}

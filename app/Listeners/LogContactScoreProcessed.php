<?php

namespace App\Listeners;

use App\Events\ContactScoreProcessedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogContactScoreProcessed
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ContactScoreProcessedEvent $event): void
    {
        Log::channel('contact')->info(
            'Contact score processed',
            [
                'id' => $event->contact->id,
                'email' => $event->contact->email,
                'score' => $event->contact->score,
                'status' => $event->contact->status,
            ]
        );
    }
}

<?php

namespace App\Jobs;

use App\Domain\Services\ContactScoreCalculatorService;
use App\Repositories\ContactRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class ProcessContactScoreJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $contactId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(
        ContactRepository $contactRepo,
        ContactScoreCalculatorService $calculator
    ): void {
        $contact = $contactRepo->find($this->contactId);
        $contact = $contactRepo->find($this->contactId);

        try {
            $contact->update([
                'status' => 'processing',
            ]);

            $score = $calculator->calculate($contact);

            $contact->update([
                'score' => $score,
                'status' => 'active',
                'processed_at' => now(),
            ]);
        } catch (Throwable $e) {
            $contact->update([
                'status' => 'failed',
            ]);

            throw $e;
        }
    }
}

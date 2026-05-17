<?php

namespace App\Application\Contact\UseCases;

use App\Domain\Contact\Repositories\ContactRepositoryInterface;
use App\Jobs\ProcessContactScoreJob;
use App\Models\Contact;

class ProcessContactUseCase
{
    public function __construct(
        private ContactRepositoryInterface $contactRepo
    ) {}

    public function execute(string $id): Contact
    {

        $contact = $this->contactRepo->find($id);

        ProcessContactScoreJob::dispatch($contact->id);

        return $contact;
    }
}

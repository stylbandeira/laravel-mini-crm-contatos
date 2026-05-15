<?php

namespace App\Application\Contact\UseCases;

use App\Jobs\ProcessContactScoreJob;
use App\Models\Contact;
use App\Repositories\ContactRepository;

class ProcessContactUseCase
{
    public function __construct(
        private ContactRepository $contactRepo
    ) {}

    public function execute(string $id): Contact
    {

        $contact = $this->contactRepo->find($id);

        ProcessContactScoreJob::dispatch($contact->id);

        return $contact;
    }
}

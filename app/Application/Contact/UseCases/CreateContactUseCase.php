<?php

namespace App\Application\Contact\UseCases;

use App\Models\Contact;
use App\Repositories\ContactRepository;

class CreateContactUseCase
{
    public function __construct(
        private ContactRepository $contactRepo
    ) {}

    public function execute(array $data): Contact
    {
        return $this->contactRepo->create($data);
    }
}

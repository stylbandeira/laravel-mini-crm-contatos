<?php

namespace App\Application\Contact\UseCases;

use App\Domain\Contact\Repositories\ContactRepositoryInterface;
use App\Models\Contact;

class CreateContactUseCase
{
    public function __construct(
        private ContactRepositoryInterface $contactRepo
    ) {}

    public function execute(array $data): Contact
    {
        return $this->contactRepo->create($data);
    }
}

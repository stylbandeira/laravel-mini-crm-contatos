<?php

namespace App\Application\Contact\UseCases;

use App\Domain\Contact\Repositories\ContactRepositoryInterface;
use App\Models\Contact;

class UpdateContactUseCase
{
    public function __construct(
        private ContactRepositoryInterface $contactRepo
    ) {}

    public function execute(string $id, array $data): Contact
    {
        return $this->contactRepo->update($id, $data);
    }
}

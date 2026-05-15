<?php

namespace App\Application\Contact\UseCases;

use App\Models\Contact;
use App\Repositories\ContactRepository;

class UpdateContactUseCase
{
    public function __construct(
        private ContactRepository $contactRepo
    ) {}

    public function execute(string $id, array $data): Contact
    {
        return $this->contactRepo->update($id, $data);
    }
}

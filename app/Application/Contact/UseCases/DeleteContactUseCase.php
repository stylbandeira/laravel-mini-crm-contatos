<?php

namespace App\Application\Contact\UseCases;

use App\Models\Contact;
use App\Repositories\ContactRepository;

class DeleteContactUseCase
{
    public function __construct(
        private ContactRepository $contactRepo
    ) {}

    public function execute(string $id): Int
    {
        return $this->contactRepo->delete($id);
    }
}

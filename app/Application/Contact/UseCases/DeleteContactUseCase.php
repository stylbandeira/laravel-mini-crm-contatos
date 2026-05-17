<?php

namespace App\Application\Contact\UseCases;

use App\Domain\Contact\Repositories\ContactRepositoryInterface;

class DeleteContactUseCase
{
    public function __construct(
        private ContactRepositoryInterface $contactRepo
    ) {}

    public function execute(string $id): void
    {
        $this->contactRepo->delete($id);
    }
}

<?php

namespace App\Domain\Contact\Repositories;

use App\Models\Contact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ContactRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): Contact;

    public function create(array $data): Contact;

    public function update(int $id, array $data): Contact;

    public function delete(int $id): void;
}

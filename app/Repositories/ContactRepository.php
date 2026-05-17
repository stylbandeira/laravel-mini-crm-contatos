<?php

namespace App\Repositories;

use App\Domain\Contact\Repositories\ContactRepositoryInterface;
use App\Models\Contact;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactRepository implements ContactRepositoryInterface
{
    protected Contact $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function all()
    {
        return $this->contact->all();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->contact->paginate($perPage);
    }

    public function find($id): Contact
    {
        return $this->contact->findOrFail($id);
    }

    public function create(array $data): Contact
    {
        return $this->contact->create($data);
    }

    public function update($id, array $data): Contact
    {
        $record = $this->find($id);
        $record->update($data);
        return $record->fresh();
    }

    public function delete($id): void
    {
        $contact = $this->find($id);

        $contact->destroy($id);
    }
}

<?php

namespace App\Repositories;

use App\Models\Contact;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactRepository
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

    public function find($id)
    {
        return $this->contact->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->contact->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record->fresh();
    }

    public function delete($id)
    {
        $contact = $this->find($id);

        return $contact->destroy($id);
    }
}

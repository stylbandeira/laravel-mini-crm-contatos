<?php

namespace App\Repositories;

use App\Models\Contact;

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
        return $record;
    }

    public function delete($id)
    {
        return $this->contact->destroy($id);
    }
}

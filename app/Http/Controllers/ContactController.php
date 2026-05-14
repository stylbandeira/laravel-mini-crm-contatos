<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Repositories\ContactRepository;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    protected ContactRepository $contactRepo;

    public function __construct(ContactRepository $contactRepo)
    {
        $this->contactRepo = $contactRepo;
    }

    /**
     * Display a listing of the contact.
     */
    public function index()
    {
        $contacts = Contact::paginate();

        return response($contacts, 200);
    }

    /**
     * Store a newly created contact in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required | string',
            'email' => 'required | email | unique:contact,email',
            'phone' => 'required | string'
        ]);

        $contact = $this->contactRepo->create($request->all());


        return response([
            'message' => 'Contato criado',
            'contato' => $contact
        ], 201);
    }

    /**
     * Display the specified contact.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified contact in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified contact from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

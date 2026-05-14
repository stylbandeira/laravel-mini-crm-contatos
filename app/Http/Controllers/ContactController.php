<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Resources\BaseContactResource;
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

        return BaseContactResource::collection($contacts);
    }

    /**
     * Store a newly created contact in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $contact = $this->contactRepo->create($request->validated());


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

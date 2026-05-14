<?php

namespace App\Http\Controllers;

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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

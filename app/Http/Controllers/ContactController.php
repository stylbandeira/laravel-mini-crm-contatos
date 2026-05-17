<?php

namespace App\Http\Controllers;

use App\Application\Contact\UseCases\CreateContactUseCase;
use App\Application\Contact\UseCases\DeleteContactUseCase;
use App\Application\Contact\UseCases\ProcessContactUseCase;
use App\Application\Contact\UseCases\UpdateContactUseCase;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Resources\BaseContactResource;
use App\Repositories\ContactRepository;

class ContactController extends Controller
{
    protected ContactRepository $contactRepo;

    public function __construct(
        ContactRepository $contactRepo,
        private CreateContactUseCase $createContactUseCase,
        private UpdateContactUseCase $updateContactUseCase,
        private DeleteContactUseCase $deleteContactUseCase,
        private ProcessContactUseCase $processContactUseCase,
    ) {
        $this->contactRepo = $contactRepo;
    }

    /**
     * Display a listing of the contact.
     */
    public function index()
    {
        $contacts = $this->contactRepo->paginate();

        return BaseContactResource::collection($contacts);
    }

    /**
     * Store a newly created contact in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $contact = $this->createContactUseCase->execute($request->validated());


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
        $contact = $this->contactRepo->find($id);

        return new BaseContactResource($contact);
    }

    /**
     * Update the specified contact in storage.
     */
    public function update(UpdateContactRequest $request, string $id)
    {
        $contact = $this->updateContactUseCase->execute($id, $request->validated());

        return new BaseContactResource($contact);
    }

    /**
     * Remove the specified contact from storage.
     */
    public function destroy(string $id)
    {
        $this->deleteContactUseCase->execute($id);

        return response([
            'message' => 'Contato deletado com sucesso!'
        ]);
    }

    /**
     * Process score for an contact
     *
     * @param string $id
     * @return void
     */
    public function processScore(string $id)
    {
        $contact = $this->processContactUseCase->execute($id);

        return response([
            'message' => 'Processamento de score enfileirado com sucesso!',
            'data' => new BaseContactResource($contact)
        ], 202);
    }
}

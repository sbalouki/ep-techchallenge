<?php

namespace App\Http\Controllers;

use App\Actions\StoreClient;
use App\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Resources\ClientResource;
use App\Services\ClientService;
use Illuminate\Http\Response;

class ClientsController extends Controller
{
    public function index(ClientService $clientService)
    {
        $clients = Client::withCount('bookings')->whereUserId(auth()->id())->get();

        return view('clients.index', [
            'clients' => $clients
        ]);
    }

    public function create()
    {
        return view('clients.create');
    }

    public function show($clientId)
    {
        $client = Client::with('bookings')->find($clientId);

        $this->authorize('view', $client);

        return view('clients.show', [
            'client' => new ClientResource($client)
        ]);
    }

    public function store(StoreClientRequest $request)
    {
        $client = resolve(StoreClient::class)->execute(
            auth()->id(),
            $request->get('name'),
            $request->get('email'),
            $request->get('phone'),
            $request->get('address'),
            $request->get('city'),
            $request->get('postcode')
        );

        return response()->json([
            'client' => new ClientResource($client)
        ], Response::HTTP_CREATED);
    }

    public function destroy(int $clientId)
    {
        $client = Client::findOrFail($clientId);

        $this->authorize('delete', $client);

        $client->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}

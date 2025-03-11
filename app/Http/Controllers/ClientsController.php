<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\StoreClientRequest;
use App\Services\ClientService;

class ClientsController extends Controller
{
    public function index(ClientService $clientService)
    {
        $clients = $clientService->getClientsByUserId(auth()->id());

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

        return view('clients.show', ['client' => $client]);
    }

    public function store(StoreClientRequest $request)
    {
        $client = new Client;
        $client->name = $request->get('name');
        $client->email = $request->get('email');
        $client->phone = $request->get('phone');
        $client->address = $request->get('address');
        $client->city = $request->get('city');
        $client->postcode = $request->get('postcode');
        $client->user_id = auth()->id();
        $client->save();

        return $client;
    }

    public function destroy($clientId)
    {
        $client = Client::findOrFail($clientId);

        $this->authorize('delete', $client);

        $client->delete();
    }
}
